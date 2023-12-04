<?php
// search.php

include 'config.php';
include 'utils.php';

function searchContent($content, $url, $searchString, $caseSensitive = false) {
    $searchContent = $caseSensitive ? $content : strtolower($content);
    $searchString = $caseSensitive ? $searchString : strtolower($searchString);

    if (strpos($searchContent, $searchString) !== false) {
        logMessage("Search String Found in $url:\n$content");
    }
}

?>