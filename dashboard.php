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

        header .logo {
            font-size: 24px;
            font-weight: bold;
        }

        header .user-dropdown {
            position: relative;
        }

        header .user-dropdown button {
            background: #d32f2f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center; /* Ensures icon and text are aligned */
            gap: 10px; /* Adds space between icon and text */
            position: relative;
        }

        header .user-dropdown button img {
            width: 20px;
            height: 20px;
            object-fit: cover; /* Ensures image scales properly */
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

        .dashboard-container {
            padding: 20px;
        }

        .dashboard-container h1 {
            color: #333;
        }

        .dashboard-container p {
            font-size: 1.2em;
            color: #555;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">Auction Dashboard</div>
        <div class="user-dropdown">
            <button>
                <?php echo htmlspecialchars($username); ?>
                <img src="user-icon.png" alt="User Icon">
            </button>
            <div class="dropdown-menu">
                <a href="dashboard.php">Dashboard</a>
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </header>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <h1>Welcome to the Auction Dashboard</h1>
        <p>Here you can manage all auction-related activities.</p>
    </div>
</body>
</html>
