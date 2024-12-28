<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cricket Match Portal</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007bff;
            padding: 10px 20px;
            color: white;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-size: 16px;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
        }
        .navbar .nav-links {
            display: flex;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo"><a href="userdashboard.php">Cricket Portal</a></div>
        <div class="nav-links">
            <a href="matches.php">Matches</a>
            <a href="scoreboard.php">Scoreboard</a>
            <a href="teams.php">Teams</a>
        </div>
    </div>
</body>
</html>
