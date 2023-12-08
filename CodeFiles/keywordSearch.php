<?php
//This is keywordSearch.php
if(isset($_GET['filename'])){
    $filename=urlencode($_GET['filename']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Interface</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(to left, #9BB8CD, #FFF7D4);
        }

        h2 {
            position: absolute;
            top: 100px;
            color: #333;
            font-size: 2.5em; 
        }

        form {
            position: relative;
            top: 20px;
            text-align: center;
        }

        label {
            font-size: 1.2em;
        }

        input,
        button {
            font-size: 1em;
            padding: 8px;
            margin: 5px;
        }

        input[type="text"] {
            width: 300px;
        }
    </style>
</head>
<body>
    <h2>Search Interface</h2>
    <form action="keywordResults.php" method="post">
        <label for="keyword">Enter Keyword:</label>
        <input type="text" name="keyword" id="keyword" placeholder="Type your keyword" required>
        <input type="hidden" name="filename" value="<?php echo $filename; ?>">
        <br>
        <button type="submit" style="background-color: #EEC759; color: black;">Search</button>
    </form>
</body>
</html>

<?php
}else{
    header("location: index.php");
}
?>