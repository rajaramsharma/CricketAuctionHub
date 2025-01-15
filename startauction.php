<?php
// Start session and validate user
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cricket');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get auction ID
$auctionId = intval($_GET['auction_id'] ?? 0);
if ($auctionId === 0) {
    echo "<script>alert('Auction ID is missing or invalid.'); window.location.href='auctionList.php';</script>";
    exit;
}

// Fetch a random player from the players table
$sql = "SELECT id, name, playing_style, base_value, profile_pic FROM players WHERE auction_id = ? ORDER BY RAND() LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $auctionId);
$stmt->execute();
$player = $stmt->get_result()->fetch_assoc();

if (!$player) {
    echo "<p>No players available for auction.</p>";
    exit;
}

// Fetch teams participating in the auction along with their points from the auction table
$teamQuery = "
    SELECT 
        t.team_id, 
        t.team_name, 
        a.points_per_team 
    FROM 
        teams t 
    JOIN 
        auctions a 
    ON 
        t.auction_id = a.auction_id 
    WHERE 
        a.auction_id = ?";
$teamStmt = $conn->prepare($teamQuery);
$teamStmt->bind_param("i", $auctionId);
$teamStmt->execute();
$teams = $teamStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Auction</title>
    <style>
        /* Same CSS styles as before */
    </style>
</head>
<body>

<div class="auction-container">
    <div class="auction-header">NPL Players Auction</div>
    <div class="player-details">
        <div class="player-photo" style="background-image: url('uploads/<?php echo htmlspecialchars($player['profile_pic']); ?>');"></div>
        <div class="player-info">
            <h2><?php echo htmlspecialchars($player['name']); ?></h2>
            <p><strong>Playing Style:</strong> <?php echo htmlspecialchars($player['playing_style']); ?></p>
            <p><strong>Base Value:</strong> <?php echo htmlspecialchars($player['base_value']); ?></p>
        </div>
    </div>
    <div class="form-container">
        <form method="POST" action="sellPlayer.php">
            <label for="team">Select Team:</label>
            <select name="team_id" id="team" required>
                <?php while ($team = $teams->fetch_assoc()) { ?>
                    <option value="<?php echo $team['team_id']; ?>">
                        <?php echo htmlspecialchars($team['team_name']); ?> (Points: <?php echo $team['points_per_team']; ?>)
                    </option>
                <?php } ?>
            </select>
            <label for="bids">Number of Bids:</label>
            <input type="number" name="bids" id="bids" min="1" required>
            <input type="hidden" name="player_id" value="<?php echo $player['id']; ?>">
            <input type="hidden" name="auction_id" value="<?php echo $auctionId; ?>">
            <button type="submit">Sold</button>
        </form>
    </div>
</div>

</body>
</html>

<?php
$stmt->close();
$teamStmt->close();
$conn->close();
?>
