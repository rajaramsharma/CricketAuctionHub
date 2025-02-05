<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not authenticated
    header("Location: login.php");
    exit();
}

// Include database connection
$conn = new mysqli('localhost', 'root', '', 'cricket');

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $sportsType = $_POST['sportsType'];
    $season = $_POST['season'];
    $auctionName = $_POST['auctionName'];
    $auctionDate = $_POST['auctionDate'];
    $auctionTime = $_POST['auctionTime'];
    $pointsPerTeam = (int)$_POST['pointsPerTeam']; // Ensure integer
    $baseBid = (float)$_POST['baseBid'];           // Ensure float
    $bidIncreaseBy = (float)$_POST['bidIncreaseBy']; // Ensure float
    $playerPerTeamMax = (int)$_POST['playerPerTeamMax']; // Ensure integer
    $playerPerTeamMin = (int)$_POST['playerPerTeamMin']; // Ensure integer

    // Get the user ID from the session
    $userId = $_SESSION['user_id'];

    // Check if required fields are empty
    if (empty($sportsType) || empty($season) || empty($auctionName) || empty($auctionDate) || empty($auctionTime) ||
        empty($pointsPerTeam) || empty($baseBid) || empty($bidIncreaseBy) || empty($playerPerTeamMax) || empty($playerPerTeamMin)) {
        echo "<script>alert('All fields are required.');</script>";
        exit;
    }

    // Prepare SQL statement to insert form data into the auctions table
    $sql = "INSERT INTO auctions (user_id, sports_type, season, auction_name, auction_date, auction_time, points_per_team, base_bid, bid_increase_by, player_per_team_max, player_per_team_min) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the SQL statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters (updated types: s=string, i=integer, d=double)
        $stmt->bind_param(
            "isssssiddii", 
            $userId,         // User ID from session
            $sportsType, 
            $season, 
            $auctionName, 
            $auctionDate, 
            $auctionTime, 
            $pointsPerTeam, 
            $baseBid, 
            $bidIncreaseBy, 
            $playerPerTeamMax, 
            $playerPerTeamMin
        );

        // Execute the statement
        if ($stmt->execute()) {
            // Success: Show alert and redirect to dashboard
            echo "<script>
                    alert('Auction created successfully!');
                    window.location.href = 'dashboard.php';
                  </script>";
            exit;
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    // Close the database connection
    $conn->close();
}
?>
