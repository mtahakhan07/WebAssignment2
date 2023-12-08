<?php
//This is keywordResults.php
if (isset($_POST['filename']) && isset($_POST['keyword'])) {
    $filename = "data/" . urldecode($_POST['filename']);
    $keyword = $_POST['keyword'];

    // Reading the file content
    $fileContent = file_get_contents($filename);
    if ($fileContent !== false) {
        // Decoding the JSON content
        $data = json_decode($fileContent, true);
        if ($data !== null) {
            if (is_array($data['data'])) {
                echo '<h3>Matching URLs:</h3>';
                echo '<ul>';
                foreach ($data['data'] as $value) {
                    if (is_string($value) && stripos($value, $keyword) !== false) {
                        echo '<li><a href="' . $value . '" target="_blank">' . $value . '</a></li>';
                        break; // Break the inner loop if a match is found in the data
                    }
                    
                }
                echo '</ul>';
            } else {
                echo '<p>No matching URLs found.</p>';
            }
    }
    } else {
        echo '<p>Error reading file content.</p>';
    }
} else {
    header("location: index.php");
}
?>
