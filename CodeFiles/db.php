<?php
// db.php

$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

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
function insertData($url, $content) {
    global $conn;

    $url = $conn->real_escape_string($url);
    $content = $conn->real_escape_string($content);

    $sql = "INSERT INTO crawled_data (url, content) VALUES ('$url', '$content')";
    $conn->query($sql);
}
?>