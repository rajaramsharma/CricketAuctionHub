<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cricket');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get auction ID
$auctionId = intval($_GET['auction_id'] ?? 0);
if ($auctionId === 0) {
    echo "<script>alert('Auction ID is missing or invalid.'); window.location.href='auctionList.php';</script>";
    exit;
}

// Pick Player: Updates player's status to "picked" (status = 1) and refreshes the page
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pick_player'])) {
    $playerId = intval($_POST['player_id'] ?? 0);
    if ($playerId > 0) {
        $updateQuery = "UPDATE player SET playerstatus = 1 WHERE id = ? AND auction_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ii", $playerId, $auctionId);
        $stmt->execute();
        $stmt->close();
        echo "<script>window.location.href=window.location.href;</script>"; // Refresh the page
        exit;
    }
}

// Sell Player: Assigns player to a team, updates points, and resets all players' status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sell_player'])) {
    $playerId = intval($_POST['player_id'] ?? 0);
    $teamId = intval($_POST['team_id'] ?? 0);
    $bids = intval($_POST['bids'] ?? 1);

    if ($playerId > 0 && $teamId > 0) {
        // Fetch the team name using the team ID
        $teamQuery = "SELECT team_name FROM teams WHERE team_id = ?";
        $teamStmt = $conn->prepare($teamQuery);
        $teamStmt->bind_param("i", $teamId);
        $teamStmt->execute();
        $teamResult = $teamStmt->get_result();
        $team = $teamResult->fetch_assoc();
        $teamName = $team['team_name'];
        $teamStmt->close();

        // Assign player to team and mark as sold (update sold_to with team name)
        $sellQuery = "UPDATE player SET sold_to = ?, playerstatus = 1 WHERE id = ? AND auction_id = ?";
        $stmt = $conn->prepare($sellQuery);
        $stmt->bind_param("sii", $teamName, $playerId, $auctionId);  // Use teamName instead of teamId
        $stmt->execute();
        $stmt->close();

        // Deduct points from team
        $updatePoints = "UPDATE teams SET points = points - ? WHERE team_id = ?";
        $pointsStmt = $conn->prepare($updatePoints);
        $pointsStmt->bind_param("ii", $bids, $teamId);
        $pointsStmt->execute();
        $pointsStmt->close();

        // Reset all players' status to 0 after selling a player
        $resetStatus = "UPDATE player SET playerstatus = 0 WHERE auction_id = ?";
        $resetStmt = $conn->prepare($resetStatus);
        $resetStmt->bind_param("i", $auctionId);
        $resetStmt->execute();
        $resetStmt->close();
    }
}

// Fetch the next unsold player in *top-down order*
$playerQuery = "SELECT p.id, p.name, p.playing_style, p.base_value, p.profile_pic, t.team_name 
                FROM player p 
                LEFT JOIN teams t ON p.sold_to = t.team_id
                WHERE p.auction_id = ? 
                AND p.sold_to IS NULL 
                ORDER BY p.id ASC LIMIT 1";

$playerStmt = $conn->prepare($playerQuery);
$playerStmt->bind_param("i", $auctionId);
$playerStmt->execute();
$player = $playerStmt->get_result()->fetch_assoc();

// Fetch teams for dropdown
$teamQuery = "SELECT team_id, team_name, points FROM teams WHERE auction_id = ?";
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
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; padding: 20px; }
        .container { width: 50%; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .player-photo { width: 150px; height: 150px; background-size: cover; border-radius: 50%; margin: 10px auto; }
        form { margin-top: 20px; }
        select, input, button { padding: 10px; margin: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h1>NPL Players Auction</h1>

    <?php if ($player): ?>
        <div class="player-details">
            <div class="player-photo" style="background-image: url('uploads/<?php echo htmlspecialchars($player['profile_pic']); ?>');"></div>
            <h2><?php echo htmlspecialchars($player['name']); ?></h2>
            <p><strong>Playing Style:</strong> <?php echo htmlspecialchars($player['playing_style']); ?></p>
            <p><strong>Base Value:</strong> ₹<?php echo htmlspecialchars($player['base_value']); ?></p>
            
            <?php if (!empty($player['team_name'])): ?>
                <p><strong>Sold To:</strong> <?php echo htmlspecialchars($player['team_name']); ?></p>
            <?php else: ?>
                <p><strong>Sold To:</strong> Not Sold Yet</p>
            <?php endif; ?>
        </div>

        <!-- Pick Player Form -->
        <form method="POST">
            <input type="hidden" name="player_id" value="<?php echo $player['id']; ?>">
            <button type="submit" name="pick_player">Pick Player</button>
        </form>

        <!-- Sell Player Form -->
        <form method="POST">
            <label for="team">Select Team:</label>
            <select name="team_id" required>
                <?php while ($team = $teams->fetch_assoc()): ?>
                    <option value="<?php echo $team['team_id']; ?>">
                        <?php echo htmlspecialchars($team['team_name']); ?> (Points: <?php echo $team['points']; ?>)
                    </option>
                <?php endwhile; ?>
            </select>
            <label for="bids">Number of Bids:</label>
            <input type="number" name="bids" min="1" required>
            <input type="hidden" name="player_id" value="<?php echo $player['id']; ?>">
            <button type="submit" name="sell_player">Sell Player</button>
        </form>
    
    <?php else: ?>
        <h2>No players remain to be sold.</h2>
    <?php endif; ?>
</div>

</body>
</html>

<?php
// Close connections
$playerStmt->close();
$teamStmt->close();
$conn->close();
?>
<?php include 'biddingsystem.php'; ?>
<?php include 'lastbid.php'; ?>