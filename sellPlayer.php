<?php
// Start session and validate user
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cricket');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate POST data
$auctionId = intval($_POST['auction_id'] ?? 0);
$teamId = intval($_POST['team_id'] ?? 0);
$playerId = intval($_POST['player_id'] ?? 0);
$bids = intval($_POST['bids'] ?? 0);

if ($auctionId === 0 || $teamId === 0 || $playerId === 0 || $bids <= 0) {
    echo "<script>alert('Invalid input data.'); window.location.href='startauction.php?auction_id=$auctionId';</script>";
    exit;
}

// Fetch player's base value
$playerQuery = "SELECT base_value FROM players WHERE id = ?";
$playerStmt = $conn->prepare($playerQuery);
$playerStmt->bind_param("i", $playerId);
$playerStmt->execute();
$player = $playerStmt->get_result()->fetch_assoc();

if (!$player) {
    echo "<script>alert('Player not found.'); window.location.href='startauction.php?auction_id=$auctionId';</script>";
    exit;
}

$baseValue = $player['base_value'];
$finalPrice = $baseValue * $bids;

// Fetch team's remaining points from the teams table
$teamQuery = "SELECT points FROM teams WHERE team_id = ? AND auction_id = ?";
$teamStmt = $conn->prepare($teamQuery);
$teamStmt->bind_param("ii", $teamId, $auctionId);
$teamStmt->execute();
$team = $teamStmt->get_result()->fetch_assoc();

if (!$team) {
    echo "<script>alert('Team not found.'); window.location.href='startauction.php?auction_id=$auctionId';</script>";
    exit;
}

$remainingPoints = $team['points'];

// Check if the team has enough points
if ($finalPrice > $remainingPoints) {
    echo "<script>alert('The team does not have enough points to purchase this player.'); window.location.href='startauction.php?auction_id=$auctionId';</script>";
    exit;
}

// Deduct points and assign the player to the team
$conn->begin_transaction();

try {
    // Update the player's team
    $updatePlayerQuery = "UPDATE players SET team_id = ? WHERE id = ?";
    $updatePlayerStmt = $conn->prepare($updatePlayerQuery);
    $updatePlayerStmt->bind_param("ii", $teamId, $playerId);
    $updatePlayerStmt->execute();

    // Deduct points from the team's points column
    $updatePointsQuery = "UPDATE teams SET points = points - ? WHERE team_id = ? AND auction_id = ?";
    $updatePointsStmt = $conn->prepare($updatePointsQuery);
    $updatePointsStmt->bind_param("iii", $finalPrice, $teamId, $auctionId);
    $updatePointsStmt->execute();

    // Commit the transaction
    $conn->commit();

    echo "<script>alert('Player sold successfully!'); window.location.href='startauction.php?auction_id=$auctionId';</script>";
} catch (Exception $e) {
    // Rollback the transaction in case of an error
    $conn->rollback();
    echo "<script>alert('An error occurred while selling the player.'); window.location.href='startauction.php?auction_id=$auctionId';</script>";
}

// Close the connection
$conn->close();
?>
