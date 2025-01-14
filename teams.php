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
    <title>View Teams</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #ffe6e6, #fff0f5);
            margin: 0;
            padding: 0;
            color: #4d004d;
        }

        .header {
            text-align: center;
            padding: 20px;
            background: #ffcccb;
            color: #800040;
            font-size: 1.5em;
            border-bottom: 3px solid #ff99cc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header h1 {
            margin: 0;
        }

        .header .add-team {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: #ff99cc;
            color: white;
            text-decoration: none;
            font-size: 1em;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease, transform 0.2s;
        }

        .header .add-team:hover {
            background: #ff6699;
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
            background: #fff0f5;
            border: 2px solid #ffc0cb;
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
            color: #800040;
        }

        .card p {
            margin: 5px 0;
            font-size: 1em;
            color: #99004d;
        }

        .card .delete-btn {
            margin-top: 10px;
            padding: 10px 20px;
            background: #ff99cc;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s;
        }

        .card .delete-btn:hover {
            background: #ff3366;
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
    <h1>Teams</h1>
    <a href="addTeam.php?auction_id=<?php echo $auctionId; ?>" class="add-team">Add New Team</a>
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
        $sql = "SELECT team_id, team_logo, team_name, team_short_name, shortcut_key 
                FROM teams 
                WHERE auction_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $auctionId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Display each team as a card
            while ($row = $result->fetch_assoc()) {
                $team_id = $row['team_id'];
                $team_logo = $row['team_logo'] ? "uploads/" . $row['team_logo'] : "default-logo.png"; // Default logo if none exists
                $team_name = htmlspecialchars($row['team_name']);
                $team_short_name = htmlspecialchars($row['team_short_name']);
                $shortcut_key = htmlspecialchars($row['shortcut_key']);

                echo "
                <div class='card' data-team-id='$team_id'>
                    <img src='$team_logo' alt='$team_name Logo'>
                    <h2>$team_name</h2>
                    <p><strong>Short Name:</strong> $team_short_name</p>
                    <p><strong>Shortcut Key:</strong> $shortcut_key</p>
                    <button type='button' class='delete-btn'>Delete</button>
                </div>";
            }
        } else {
            echo "<p style='text-align: center;'>No teams found for this auction.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>

    <script>
        $(document).ready(function(){
            $(".delete-btn").click(function(){
                var team_id = $(this).closest('.card').data('team-id'); // Get the team_id from data attribute

                if (confirm("Are you sure you want to delete this team?")) {
                    // Perform AJAX request
                    $.ajax({
                        url: 'deleteteam.php',
                        type: 'POST',
                        data: { team_id: team_id }, // Send the team_id to deleteteam.php
                        success: function(response) {
                            if (response === 'success') {
                                alert("Team deleted successfully.");
                                location.reload(); // Reload the page to update the team list
                            } else {
                                alert("Error deleting team.");
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
