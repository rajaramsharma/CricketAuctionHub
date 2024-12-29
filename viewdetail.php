<?php
// Start session to check if the user is logged in
session_start();

// Check if the user is logged in by checking for 'user_id' in the session
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in first.'); window.location.href='login.php';</script>";
    exit;
}

// Debugging: Output user_id from session to check if it's set
if (!isset($_SESSION['user_id'])) {
    die("Debug: user_id is not set in session.");
}

// Get the logged-in user's user_id from session
$userId = $_SESSION['user_id'];

// Debugging: Output user_id to confirm it's correct
// echo "User ID: " . htmlspecialchars($userId);

// Include database configuration
$host = 'localhost';
$dbname = 'cricket';
$username = 'root';
$password = '';

// Check if auctionId is provided in the URL
if (isset($_GET['auction_id'])) {
    $auctionId = $_GET['auction_id'];

    try {
        // Establish a PDO database connection
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch auction details for the given auctionId
        $stmt = $conn->prepare("SELECT * FROM auctions WHERE auction_id = :auctionId AND user_id = :userId");
        $stmt->bindParam(':auctionId', $auctionId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Check if the auction exists and belongs to the logged-in user
        if ($stmt->rowCount() > 0) {
            // Fetch auction data
            $auction = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "<script>alert('You do not have permission to view this auction or it does not exist.'); window.location.href='auctionList.php';</script>";
            exit;
        }

    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='auctionList.php';</script>";
        exit;
    }

    // Close the connection
    $conn = null;
} else {
    echo "<script>alert('Invalid request. Auction ID not provided.'); window.location.href='auctionList.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .auction-detail-card {
            background: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: auto;
        }
        .auction-detail-card h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .auction-detail-card p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .auction-detail-card .actions {
            margin-top: 20px;
            text-align: center;
        }
        .auction-detail-card .actions a {
            text-decoration: none;
            color: white;
            background-color: red;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        .auction-detail-card .actions a:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

<div class="auction-detail-card">
    <h2>Auction Details: <?php echo htmlspecialchars($auction['auction_name']); ?></h2>

    <p><strong>Sports Type:</strong> <?php echo htmlspecialchars($auction['sports_type']); ?></p>
    <p><strong>Season:</strong> <?php echo htmlspecialchars($auction['season']); ?></p>
    <p><strong>Auction Name:</strong> <?php echo htmlspecialchars($auction['auction_name']); ?></p>
    <p><strong>Auction Date:</strong> <?php echo htmlspecialchars($auction['auction_date']); ?></p>
    <p><strong>Auction Time:</strong> <?php echo htmlspecialchars($auction['auction_time']); ?></p>
    <p><strong>Points Per Team:</strong> <?php echo htmlspecialchars($auction['points_per_team']); ?></p>
    <p><strong>Base Bid:</strong> $<?php echo htmlspecialchars($auction['base_bid']); ?></p>
    <p><strong>Bid Increase By:</strong> $<?php echo htmlspecialchars($auction['bid_increase_by']); ?></p>
    <p><strong>Max Players Per Team:</strong> <?php echo htmlspecialchars($auction['player_per_team_max']); ?></p>
    <p><strong>Min Players Per Team:</strong> <?php echo htmlspecialchars($auction['player_per_team_min']); ?></p>

    <div class="actions">
        <a href="deleteAuction.php?auctionId=<?php echo $auction['auction_id']; ?>" onclick="return confirmDelete()">Delete Auction</a>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this auction?");
    }
</script>

</body>
</html>
