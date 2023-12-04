<?php
// queue.php

$urlQueue = [];

function enqueue($url, $depth) {
    global $urlQueue;
    $urlQueue[] = ['url' => $url, 'depth' => $depth];
}

function dequeue() {
    global $urlQueue;
    return array_shift($urlQueue);
}
?>
