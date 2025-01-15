<?php include 'header.php'; ?>

<?php
// Start session and check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if auction_id is provided in the GET parameter
if (!isset($_GET['auction_id']) || empty($_GET['auction_id'])) {
    echo "<script>alert('Auction ID is missing or invalid.'); window.location.href='auctionList.php';</script>";
    exit;
}

$auctionId = intval($_GET['auction_id']); // Sanitize auction_id
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Players</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #e6f7ff, #f0ffff);
            margin: 0;
            padding: 0;
            color: #003366;
        }

        .header {
            text-align: center;
            padding: 20px;
            background: #cce7ff;
            color: #003366;
            font-size: 1.5em;
            border-bottom: 3px solid #99ccff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header h1 {
            margin: 0;
        }

        .header .add-player {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: #99ccff;
            color: white;
            text-decoration: none;
            font-size: 1em;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease, transform 0.2s;
        }

        .header .add-player:hover {
            background: #66b2ff;
            transform: translateY(-2px);
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .card {
            background: #f0ffff;
            border: 2px solid #cce7ff;
            border-radius: 15px;
            width: 250px;
            text-align: center;
            padding: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }

        .card h2 {
            margin: 15px 0 10px;
            font-size: 1.5em;
            color: #003366;
        }

        .card p {
            margin: 5px 0;
            font-size: 1em;
            color: #00509e;
        }

        .card .delete-btn {
            margin-top: 10px;
            padding: 10px 20px;
            background: #99ccff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s;
        }

        .card .delete-btn:hover {
            background: #007acc;
            transform: translateY(-2px);
        }

        .card .delete-btn:active {
            transform: translateY(0);
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<div class="header">
    <h1>Players</h1>
    <a href="add_Player.php?auction_id=<?php echo $auctionId; ?>" class="add-player">Add New Player</a>
</div>

<div class="container">
    <?php
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'cricket');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch data from the database based on auction_id
    $sql = "SELECT id, profile_pic, name, playing_style, base_value, sold_to
            FROM players 
            WHERE auction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $auctionId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Display each player as a card
        while ($row = $result->fetch_assoc()) {
            $id = $row['id']; // Changed 'player_id' to 'id'
            $profile_pic = $row['profile_pic'] ? "uploads/" . $row['profile_pic'] : "default-profile.png"; // Default profile if none exists
            $name = htmlspecialchars($row['name']);
            $playing_style = htmlspecialchars($row['playing_style']);
            $base_value = htmlspecialchars($row['base_value']); // Changed 'base_price' to 'base_value'
            $sold_to = htmlspecialchars($row['sold_to']);

            echo "
            <div class='card' data-player-id='$id'>
                <img src='$profile_pic' alt='$name Profile Pic'>
                <h2>$name</h2>
                <p><strong>Playing Style:</strong> $playing_style</p>
                <p><strong>Base Value:</strong> $base_value</p> <!-- Updated 'Base Price' to 'Base Value' -->
                 <p><strong>SOLD TO:</strong> $sold_to</p>
                <button type='button' class='delete-btn'>Delete</button>
            </div>";
        }
    } else {
        echo "<p style='text-align: center;'>No players found for this auction.</p>";
    }

    $stmt->close();
    $conn->close();
    ?>
</div>

<script>
    $(document).ready(function(){
        $(".delete-btn").click(function(){
            var player_id = $(this).closest('.card').data('player-id');

            if (confirm("Are you sure you want to delete this player?")) {
                $.ajax({
                    url: 'deletePlayer.php',
                    type: 'POST',
                    data: { player_id: player_id },
                    success: function(response) {
                        if (response === 'success') {
                            alert("Player deleted successfully.");
                            location.reload();
                        } else {
                            alert("Error deleting player.");
                        }
                    }
                });
            }
        });
    });
</script>
</body>
</html>
