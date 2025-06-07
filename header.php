<?php 
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cricket');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Auction Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f9;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #003f88;
            color: white;
        }

        header .logo img {
            height: 40px;
        }

        header .navbar {
            display: flex;
            gap: 15px;
        }

        header .navbar button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        header .navbar button:hover {
            background: #0056b3;
        }

        header .user-dropdown {
            position: relative;
        }

        header .user-dropdown button {
            background:rgb(197, 79, 79);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        header .user-dropdown button img {
            width: 20px;
            height: 20px;
            object-fit: cover;
        }

        header .user-dropdown .dropdown-menu {
            position: absolute;
            right: 0;
            top: 40px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
            display: none;
            z-index: 1000;
        }

        header .user-dropdown:hover .dropdown-menu {
            display: block;
        }

        header .user-dropdown .dropdown-menu a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            border-bottom: 1px solid #f4f4f9;
        }

        header .user-dropdown .dropdown-menu a:hover {
            background: #f4f4f9;
        }
    </style>
</head>
<body>
<header>
    <div class="logo">
        <img src="logo.png" alt="Auction Hub Logo">
    </div>
    <div class="navbar">
        <button onclick="location.href='uploadmatch_schedule.php'">Match Schedule</button>
        <button onclick="location.href='liveScores.php'">Live Score</button>
        <button onclick="location.href='createauction.php'">CreateAuction</button>
        <button onclick="location.href='myauction.php'">MyAuction</button>

    </div>
    <div class="user-dropdown">
        <button>
            <?php echo htmlspecialchars($username); ?>
            <img src="user-icon.jpg" alt="User Icon">
        </button>
        <div class="dropdown-menu">
            <a href="dashboard.php">Dashboard</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</header>
