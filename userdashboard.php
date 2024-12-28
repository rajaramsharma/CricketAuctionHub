<?php
// Include the header file
include 'userheader.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cricket Auction Hub</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        .container {
            text-align: center;
            padding: 20px;
        }
        .links {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .links a {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        .links a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        Welcome to CricketAuctionHub
    </header>

    <div class="container">
        <p>Here you can explore:</p>
        MatchSchedule   Teams   ScoreBoard
    </div>
</body>
</html>
