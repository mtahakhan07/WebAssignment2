<?php
// index.php

include 'crawler.php';
include 'search.php';

$startUrl = 'https://example.com'; 
crawl($startUrl);

?>