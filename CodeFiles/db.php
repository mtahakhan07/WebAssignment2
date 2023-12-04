<?php
// db.php

$servername = "localhost";
$username = "root";
$password = "151208";
$dbname = "my_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create table if not exists
$sql = "CREATE TABLE IF NOT EXISTS crawled_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255) NOT NULL,
    content TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$conn->query($sql);

// Function to insert data into the database
function insertData($url, $title, $metaDescription) {
    global $conn;

    $url = $conn->real_escape_string($url);
    $title = $conn->real_escape_string($title);
    $metaDescription = $conn->real_escape_string($metaDescription);

    $sql = "INSERT INTO crawled_data (url, title, meta_description) VALUES ('$url', '$title', '$metaDescription')";
    $conn->query($sql);
}

?>