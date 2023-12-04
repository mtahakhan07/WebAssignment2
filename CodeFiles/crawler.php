<?php
// crawler.php

include 'config.php';
include 'utils.php';
include 'queue.php';
include 'db.php'; // Include the db.php file for database operations

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $seedUrl = isset($_POST['seedUrl']) ? $_POST['seedUrl'] : '';

    // Example: Validate and enqueue the seed URL
    if (!empty($seedUrl)) {
        enqueue($seedUrl);
    }
}

// Fetch HTML content and log messages
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

        // Persistent Storage: Insert data into the database
        insertData($url, $parsedData['title'], $parsedData['meta_description']);

        // Extract and enqueue URLs
        $links = extractLinks($html, $url, $depth);
        foreach ($links as $link) {
            enqueue($link, $depth + 1);
        }
    } else {
        logMessage("Error fetching URL: $url");
    }
}

// Function to fetch multiple URLs concurrently
function fetchUrlsConcurrently($urls) {
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
        $html = curl_multi_getcontent($ch);
        crawl($html); // Assuming parseHtml and extractLinks are still used
        curl_multi_remove_handle($mh, $ch);
    }

    curl_multi_close($mh);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Crawler</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        textarea {
            width: 100%;
            height: 300px;
        }
    </style>
</head>
<body>
    <h1>Web Crawler</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="seedUrl">Seed URL:</label>
        <input type="url" name="seedUrl" id="seedUrl" required>
        <button type="submit">Start Crawling</button>
    </form>

    <h2>Log Messages</h2>
    <textarea id="logTextArea" readonly></textarea>

    <script>
        // Fetch and display log messages using JavaScript
        setInterval(fetchLogMessages, 1000);

        function fetchLogMessages() {
            fetch('log.txt')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('logTextArea').value = data;
                })
                .catch(error => console.error('Error fetching log:', error));
        }
    </script>
</body>
</html>
