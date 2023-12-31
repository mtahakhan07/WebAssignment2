<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Web Crawler</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(to left, #9BB8CD, #FFF7D4); /* Gradient from light blue to light soothing color */; 
        }

        h1 {
            position: absolute;
            top: 100px;
            color: #333;
            font-size: 3em; 
            
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

        input[type="url"],
        input[type="text"] {
            width: 300px;
        }

        textarea {
            width: 100%;
            height: 300px;
        }

        #logTextArea {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <h1>Welcome to the Web Crawler</h1>

    <form id="crawlerForm" action="crawler.php" method="POST">
        <label for="seedUrl">Seed URL:</label>
        <input type="url" name="seedUrl" id="seedUrl" placeholder="Enter seed URL" required>
        <br>
        <label for="maxDepth">Max Depth:</label>
        <input type="text" name="maxDepth" id="maxDepth" placeholder="Enter max depth" required>
        <br>
        <button type="submit" style="background-color: #EEC759; color: black;">Start Crawling</button>
    </form>

</body>

</html>


