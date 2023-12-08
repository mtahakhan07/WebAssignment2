<?php
//This is crawler.php
class Crawler {
    private $baseUrl;
    private $fetchedUrls = [];
    private $crawledUrls = [];
    private $maxDepth;

    public function __construct($url,$maxDepth = 5) {
        $this->baseUrl=parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
        $this->maxDepth = $maxDepth;
        $this->crawl($url,0);
    }

    private function isRelativeUrl($url) {
        // Checking if the URL starts with a protocol or a slash
        return !preg_match('/^(https?:|\/)/', $url);
    }

    private function convertToAbsoluteUrl($baseUrl, $url) {
        // If the URL is already absolute, returning it as is
        if (!$this->isRelativeUrl($url)) {
            return $url;
        }

        // Parse the base URL to handle cases like 'https://example.com/path/'
        $parsedBaseUrl = parse_url($baseUrl);

        // Combine the base URL with the relative path
        $absoluteUrl = $parsedBaseUrl['scheme'] . '://' . $parsedBaseUrl['host'] . '/' . ltrim($url, '/');

        return $absoluteUrl;
    }

    private function fetchHtml($url) {
        // Initializing cURL session
        $ch = curl_init($url);
    
        // Setting cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'MyWebCrawler/1.0');
    
        // Executing cURL session
        $html = curl_exec($ch);
    
        // Checking for cURL errors
        if (curl_errno($ch)) {
            // Handling cURL errors
            return false;
        }
    
        // Getting HTTP status code
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        // Closing cURL session
        curl_close($ch);
    
        // Checking for HTTP status code indicating an error
        if ($statusCode >= 400) {
            // Handling HTTP errors
            return false;
        }
    
        return $html;
    }
    
    

    public function fetchUrlsConcurrently($urls) {
        $multiHandles = [];
        $mh = curl_multi_init();
    
        foreach ($urls as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
            curl_multi_add_handle($mh, $ch);
            $multiHandles[] = $ch;
        }
    
        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while ($running > 0);
    
        foreach ($multiHandles as $ch) {
            $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            $html = curl_multi_getcontent($ch);
    
            // Processing the fetched content (you can modify this part based on your needs)
            processFetchedContent($url, $html);
    
            curl_multi_remove_handle($mh, $ch);
        }
    
        curl_multi_close($mh);
    }

    private  function parseHtml($html, $url,$depth) {
        $dom = new DOMDocument;
        @$dom->loadHTML($html);

        $xpath = new DOMXPath($dom);

        $parsedData = [];

        // Extracting headings and paragraphs
        $tags = $xpath->query('//h1 | //h2 | //h3 | //h4 | //h5 | //h6 | //p');
        foreach ($tags as $tag) {
            $parsedData[] = $tag->nodeValue;
        }

        // Extracting links
        $links = [];
        $aTags = $xpath->query('//a');
        foreach ($aTags as $aTag) {
            $href = $aTag->getAttribute('href');
            $absoluteUrl = $this->convertToAbsoluteUrl($this->baseUrl, $href);
            $this->crawl($absoluteUrl,$depth+1);
        }
        // Storing the URL and parsed data in a separate array
        $result = [
            'url' => $url,
            'data' => $parsedData,
        ];

        return $result;
    }

        private function parseRobotsTxt($url) {
        $baseUrl = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
        $robotsUrl = rtrim($baseUrl, '/') . '/robots.txt';
        $robotsContent = $this->fetchHtml($robotsUrl);
    
        // If robots.txt is not found, assume all paths are allowed
        if ($robotsContent === false) {
            return true;
        }
    
        $path = parse_url($url, PHP_URL_PATH);
    
        $lines = explode("\n", $robotsContent);
        foreach ($lines as $line) {
            // Ignoring comments and empty lines
            if (empty($line) || $line[0] === '#' || $line[0] === ';') {
                continue;
            }
    
            // Parsing disallowed paths
            if (strpos($line, 'Disallow:') === 0) {
                $disallowedPath = trim(substr($line, strlen('Disallow:')));
    
                // Checking if the URL path matches the disallowed path
                if (strpos($path, $disallowedPath) === 0) {
                    return false; // URL is disallowed
                }
            }
        }
    
        return true; // URL is allowed
    }
    

    public function crawl($url, $depth) {
        
        if ($depth > $this->maxDepth || in_array($url, $this->crawledUrls)) {
            return;
        }
        $this->depth=$depth;

        if (!$this->parseRobotsTxt($url)) {
            return;
        }
        $html = $this->fetchHtml($url);

        if ($html !== false) {
            $parsedData = $this->parseHtml($html, $url,$depth);
            $this->crawledUrls[]=$url;
            $this->storeData($parsedData);
        } else {
            echo "Error fetching URL: $url\n";
        }
    }

    
    private function storeData($data) {
        try {
            $filename = 'data/' . parse_url($this->baseUrl, PHP_URL_HOST) . '.txt';    
            // Encode the combined data and write it back to the file
            file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
            
            // Optionally, you can log or handle success here
        } catch (Exception $e) {
            // Handle exceptions
            echo 'Error: ' . $e->getMessage();
        }
    }
      
    
    
}

if(isset($_POST['seedUrl']) && isset($_POST['maxDepth'])){
    $url=rtrim($_POST['seedUrl'],'/');
    $maxDepth=$_POST['maxDepth'];
    $filename=urlencode(parse_url($url, PHP_URL_HOST)).".txt";
    $filePath="data/".parse_url($url, PHP_URL_HOST).".txt";
    if(file_exists($filePath)){
        
        header("location: keywordSearch.php?filename=".$filename);
    }else{
        $crawler=new Crawler($url,$maxDepth);
        header("location: keywordSearch.php?filename=".$filename);
    }
}else{
    header("location: index.php");
}

//$crawler->crawl();
?>
