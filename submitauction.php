<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

    // Check if required fields are empty
    if (empty($sportsType) || empty($season) || empty($auctionName) || empty($auctionDate) || empty($auctionTime) ||
        empty($pointsPerTeam) || empty($baseBid) || empty($bidIncreaseBy) || empty($playerPerTeamMax) || empty($playerPerTeamMin)) {
        echo "All fields are required.";
        exit;
    }

    // Prepare SQL statement to insert form data into the auctions table
    $sql = "INSERT INTO auctions (sports_type, season, auction_name, auction_date, auction_time, points_per_team, base_bid, bid_increase_by, player_per_team_max, player_per_team_min) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the SQL statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters (updated types: s=string, i=integer, d=double)
        $stmt->bind_param(
            "sssssiddii", 
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
            echo "Auction created successfully!";
        } else {
            echo "Error: " . $stmt->error; // This will show detailed SQL errors
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error: " . $conn->error; // This will show any connection issues or errors with preparing the statement
    }

    // Close the database connection
    $conn->close();
}
?>