<?php
// Start session and validate user
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cricket');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auctionId = intval($_POST['auction_id']);
    $playerId = intval($_POST['player_id']);
    $teamId = intval($_POST['team_id']);
    $bidValue = intval($_POST['bids']);

    // Validate inputs
    if ($auctionId <= 0 || $playerId <= 0 || $teamId <= 0 || $bidValue <= 0) {
        echo "<script>alert('Invalid input data. Please try again.'); window.location.href='startAuction.php?auction_id=$auctionId';</script>";
        exit;
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Get the team's current points and name
        $teamQuery = "SELECT points, team_name FROM teams WHERE team_id = ?";
        $teamStmt = $conn->prepare($teamQuery);
        if (!$teamStmt) {
            throw new Exception("Error preparing team query: " . $conn->error);
        }
        $teamStmt->bind_param("i", $teamId);
        $teamStmt->execute();
        $team = $teamStmt->get_result()->fetch_assoc();
        $teamPoints = intval($team['points']);
        $teamName = $team['team_name'];

        // Check if the team has enough points to bid
        if ($teamPoints < $bidValue) {
            echo "<script>alert('Insufficient points for this bid.'); window.location.href='startAuction.php?auction_id=$auctionId';</script>";
            exit;
        }

        // Update the player's "sold_to" column with the team name
        $updatePlayerQuery = "UPDATE players SET sold_to = ? WHERE id = ?";
        $updatePlayerStmt = $conn->prepare($updatePlayerQuery);
        if (!$updatePlayerStmt) {
            throw new Exception("Error preparing player update query: " . $conn->error);
        }
        $updatePlayerStmt->bind_param("si", $teamName, $playerId);
        $updatePlayerStmt->execute();

        // Deduct the bid value from the team's points
        $updateTeamQuery = "UPDATE teams SET points = points - ? WHERE team_id = ?";
        $updateTeamStmt = $conn->prepare($updateTeamQuery);
        if (!$updateTeamStmt) {
            throw new Exception("Error preparing team update query: " . $conn->error);
        }
        $updateTeamStmt->bind_param("ii", $bidValue, $teamId);
        $updateTeamStmt->execute();

        // Commit the transaction
        $conn->commit();

        // Redirect back to the auction page
        echo "<script>alert('Player sold successfully to $teamName for $bidValue points.'); window.location.href='startAuction.php?auction_id=$auctionId';</script>";
    } catch (Exception $e) {
        // Rollback the transaction in case of any error
        $conn->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='startAuction.php?auction_id=$auctionId';</script>";
    } finally {
        // Close prepared statements only if initialized
        if (isset($teamStmt)) $teamStmt->close();
        if (isset($updatePlayerStmt)) $updatePlayerStmt->close();
        if (isset($updateTeamStmt)) $updateTeamStmt->close();
    }
}

// Close the database connection
$conn->close();
?>
