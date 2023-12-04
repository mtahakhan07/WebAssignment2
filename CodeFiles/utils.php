<?php
// utils.php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;

function fetchHtml($url) {
    try {
        $client = new Client();
        $response = $client->request('GET', $url);
        return $response->getBody()->getContents();
    } catch (Exception $e) {
        return false;
    }
}

function parseHtml($html) {
    $dom = new DOMDocument;
    @$dom->loadHTML($html);

    $parsedData = [];

    // Extract title
    $titleElement = $dom->getElementsByTagName('title')->item(0);
    $title = $titleElement ? $titleElement->nodeValue : null;
    $parsedData['title'] = $title;

    // Extract meta description
    $metaDescription = '';
    $metaTags = $dom->getElementsByTagName('meta');
    foreach ($metaTags as $metaTag) {
        if ($metaTag->getAttribute('name') == 'description') {
            $metaDescription = $metaTag->getAttribute('content');
            break;
        }
    }
    $parsedData['meta_description'] = $metaDescription;

    // Extract all paragraphs
    $paragraphs = [];
    $pTags = $dom->getElementsByTagName('p');
    foreach ($pTags as $pTag) {
        $paragraphs[] = $pTag->nodeValue;
    }
    $parsedData['paragraphs'] = $paragraphs;

    // Extract all links
    $links = [];
    $aTags = $dom->getElementsByTagName('a');
    foreach ($aTags as $aTag) {
        $link = [
            'href' => $aTag->getAttribute('href'),
            'text' => $aTag->nodeValue,
        ];
        $links[] = $link;
    }
    $parsedData['links'] = $links;

    // Implement additional logic to extract other relevant information
    // ...

    return $parsedData;
}


function logMessage($message) {
    file_put_contents('log.txt', $message . PHP_EOL, FILE_APPEND);
}

?>