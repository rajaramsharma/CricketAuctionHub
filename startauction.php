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

// Fetch a random player whose "sold_to" column is NULL
$sql = "SELECT id, name, playing_style, base_value, profile_pic 
        FROM players 
        WHERE auction_id = ? AND sold_to IS NULL 
        ORDER BY RAND() LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $auctionId);
$stmt->execute();
$player = $stmt->get_result()->fetch_assoc();

// Check if any player is available for auction
if (!$player) {
    echo "<div style='text-align: center; margin-top: 20%; font-family: Arial, sans-serif;'>
            <h1>No players remain to be sold.</h1>
          </div>";
    exit;
}

// Fetch teams participating in the auction along with their points
$teamQuery = "
    SELECT 
        t.team_id, 
        t.team_name, 
        t.points 
    FROM 
        teams t 
    WHERE 
        t.auction_id = ?";
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
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .auction-container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .player-photo {
            width: 150px;
            height: 150px;
            background-size: cover;
            background-position: center;
            margin: 0 auto 20px;
            border-radius: 50%;
        }
        .form-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="auction-container">
    <h1>NPL Players Auction</h1>
    <div class="player-details">
        <div class="player-photo" style="background-image: url('uploads/<?php echo htmlspecialchars($player['profile_pic']); ?>');"></div>
        <div class="player-info">
            <h2><?php echo htmlspecialchars($player['name']); ?></h2>
            <p><strong>Playing Style:</strong> <?php echo htmlspecialchars($player['playing_style']); ?></p>
            <p><strong>Base Value:</strong> ₹<?php echo htmlspecialchars($player['base_value']); ?></p>
        </div>
    </div>
    <div class="form-container">
        <form method="POST" action="sellPlayer.php">
            <label for="team">Select Team:</label>
            <select name="team_id" id="team" required>
                <?php while ($team = $teams->fetch_assoc()) { ?>
                    <option value="<?php echo $team['team_id']; ?>">
                        <?php echo htmlspecialchars($team['team_name']); ?> (Points: <?php echo $team['points']; ?>)
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
// Close connections
$stmt->close();
$teamStmt->close();
$conn->close();
?>
