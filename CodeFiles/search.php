<?php
// search.php

include 'config.php';
include 'utils.php';

function searchContent($content, $url) {
    global $searchString;

    if (stripos($content, $searchString) !== false) {
        logMessage("Search String Found in $url:\n$content");
    }
}
?>