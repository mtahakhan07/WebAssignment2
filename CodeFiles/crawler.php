<?php
// crawler.php

include 'config.php';
include 'utils.php';
include 'queue.php';

function crawl($url, $depth = 0) {
    global $maxDepth;

    if ($depth > $maxDepth) {
        return;
    }

    // Fetch HTML content
    $html = fetchHtml($url);

    if ($html) {
        // Parse HTML to extract relevant information
        $parsedData = parseHtml($html);

        // Log the extracted information
        logMessage("URL: $url\n" . json_encode($parsedData, JSON_PRETTY_PRINT));

        // Extract and enqueue URLs
        $links = extractLinks($html, $url, $depth);
        foreach ($links as $link) {
            enqueue($link, $depth + 1);
        }
    } else {
        logMessage("Error fetching URL: $url");
    }
}

function extractLinks($html, $baseUrl, $depth) {
    $links = [];

    $dom = new DOMDocument;
    @$dom->loadHTML($html);

    $xpath = new DOMXPath($dom);
    $hrefs = $xpath->evaluate("/html/body//a");

    for ($i = 0; $i < $hrefs->length; $i++) {
        $link = $hrefs->item($i);
        $url = $link->getAttribute('href');

        // Ensure the URL is absolute
        $url = filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED | FILTER_FLAG_QUERY_REQUIRED);
        if ($url) {
            $links[] = $url;
        }
    }

    return $links;
}

?>