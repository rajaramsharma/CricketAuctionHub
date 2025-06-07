<?php
// db_connection.php
$conn = new mysqli('localhost', 'root', '', 'cricket');

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in by checking cookies
if (!isset($_COOKIE['team_id']) || !isset($_COOKIE['team_name'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Fetch team details from the database using the team_id stored in cookies
$team_id = $_COOKIE['team_id'];
$stmt = $conn->prepare("SELECT * FROM teams WHERE team_id = ?");
$stmt->bind_param("i", $team_id);
$stmt->execute();
$result = $stmt->get_result();
$team = $result->fetch_assoc();

// Fetch the player list whose status is 1 (active players) and show them
$player_stmt = $conn->prepare("SELECT * FROM player WHERE playerstatus = 1");
$player_stmt->execute();
$player_result = $player_stmt->get_result();

// Fetch the players for the logged-in team (team_id)
$team_players_stmt = $conn->prepare("SELECT * FROM player WHERE auction_id = ? AND playerstatus = 1");
$team_players_stmt->bind_param("i", $team_id);
$team_players_stmt->execute();
$team_players_result = $team_players_stmt->get_result();



$stmt->close();
$player_stmt->close();
$team_players_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Dashboard</title>
    <style>
        /* Global Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background-color: #f4f4f9; color: #333; display: flex; justify-content: center; align-items: flex-start; min-height: 100vh; margin: 0; flex-direction: column; }
        .container { background-color: #fff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 40px 30px; width: 80%; max-width: 700px; text-align: center; }
        h2 { font-size: 2.5rem; color: #007BFF; margin-bottom: 20px; }
        h3 { font-size: 1.5rem; color: #555; margin-bottom: 15px; }
        p { font-size: 1.2rem; color: #555; margin-bottom: 10px; }
        .team-logo { border-radius: 10px; margin: 10px 0; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); }
        .logout-link { display: inline-block; background-color: #007BFF; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-size: 1.2rem; margin-top: 30px; transition: background-color 0.3s; }
        .logout-link:hover { background-color: #0056b3; }
        .player-list { margin-top: 40px; text-align: left; }
        .player-cart { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; }
        .player-card { background-color: #fff; padding: 15px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); text-align: center; }
        .player-card h4 { font-size: 1.1rem; color: #007BFF; margin-bottom: 10px; }
        .player-card p { font-size: 1rem; color: #555; }
        .player-actions { margin-top: 10px; }
        .player-actions button { background-color: #007BFF; color: white; padding: 10px; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer; }
        .player-actions button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to Team Dashboard</h2>
        <h3>Team Details:</h3>
        <p><strong>Team Name:</strong> <?php echo $team['team_name']; ?></p>
        <p><strong>Team Short Name:</strong> <?php echo $team['team_short_name']; ?></p>
        <p><strong>Points:</strong> <?php echo $team['points']; ?></p>
        <p><strong>Auction ID:</strong> <?php echo $team['auction_id']; ?></p>
        <p><strong>Team Logo:</strong></p>
        <img class="team-logo" src="path_to_logos/<?php echo $team['team_logo']; ?>" alt="Team Logo" width="150">
        <br>
        <a class="logout-link" href="tlogout.php">Logout</a>
    </div>

    <!-- Player Cart Section (Active Players) -->
    <div class="container player-list">
        <h3>Active Players (Status 1):</h3>
        <div class="player-cart">
            <?php
            if ($player_result->num_rows > 0) {
                while ($player = $player_result->fetch_assoc()) {
                    echo '<div class="player-card">';
                    echo '<h4>' . $player['name'] . '</h4>';
                    echo '<p><strong>Age:</strong> ' . $player['age'] . '</p>';
                    echo '<p><strong>Playing Style:</strong> ' . $player['playing_style'] . '</p>';
                    echo '<p><strong>Base Value:</strong> ' . $player['base_value'] . '</p>';
                    echo '<img src="path_to_profiles/' . $player['profile_pic'] . '" alt="Profile Pic" width="100">';
                    echo '<div class="player-actions">';
                    echo '<form method="POST">';
                    echo '<input type="hidden" name="player_id" value="' . $player['id'] . '">';
                   
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No active players found.</p>';
            }
            ?>
        </div>
    </div>

    <!-- Team Players Cart Section -->
   
</body>
</html>







<?php
// db_connection.php
$conn = new mysqli('localhost', 'root', '', 'cricket');

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the last bidding_team and bid_price from the bidding table
$query = "SELECT bidding_team, bid_price FROM bidding ORDER BY id DESC LIMIT 1";
$result = $conn->query($query);

// Check if there is a result and fetch the data
if ($result->num_rows > 0) {
    $bidding_info = $result->fetch_assoc();
    $last_bidding_team = $bidding_info['bidding_team'];
    $last_bid_price = $bidding_info['bid_price'];
} else {
    $last_bidding_team = 'No bids yet';
    $last_bid_price = 0;  // Initialize as 0 if no bids yet
}

// Handle the rise or pass button actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['rise'])) {
        // Calculate the new bid price by increasing the last bid by 10
        $new_bid_price = $last_bid_price + 10; // Increase by 10
        
        // Insert the new bid price and team into the bidding table
        $insert_query = "INSERT INTO bidding (bid_price, bidding_team) VALUES ($new_bid_price, '$last_bidding_team')";
        $conn->query($insert_query);

        // Update the last bid info with the new values
        $last_bid_price = $new_bid_price;
    } elseif (isset($_POST['pass'])) {
        // Insert the team name into the biddingteam table in the pass_team_list column
        $insert_pass_query = "INSERT INTO bidding_pass_team (pass_team_list) VALUES ('$last_bidding_team')";
        if ($conn->query($insert_pass_query) === TRUE) {
            echo "The team '$last_bidding_team' has passed their bid.";
        } else {
            echo "Error: " . $insert_pass_query . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bidding Dashboard</title>
    <style>
        /* Basic Styling */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f4f4f9; color: #333; text-align: center; }
        .container { background-color: #fff; padding: 20px; margin: 20px auto; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); width: 80%; max-width: 700px; }
        .buttons { margin-top: 20px; }
        .buttons button { padding: 10px 20px; margin: 5px; cursor: pointer; background-color: #4CAF50; color: white; border: none; border-radius: 5px; }
        .buttons button:hover { background-color: #45a049; }
        .pass-button { background-color: #f44336; }
        .pass-button:hover { background-color: #e41c2b; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bidding Information</h2>
        <p><strong>Current Team Bidding:</strong> <?php echo $last_bidding_team; ?></p>
        <p><strong>Last Bid Price:</strong> <?php echo $last_bid_price; ?></p>
        
        <div class="buttons">
            <!-- Buttons to handle rise bid and pass bid -->
            <form method="POST">
                <button type="submit" name="rise">Rise Bid by 10</button>
                <button type="submit" name="pass" class="pass-button">Pass Bid</button>
            </form>
        </div>
    </div>
</body>
</html>














<?php
// db_connection.php

// Create a connection to the database
$conn = new mysqli('localhost', 'root', '', 'cricket');

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
// Check if the user is logged in by checking cookies
if (!isset($_COOKIE['team_id']) || !isset($_COOKIE['team_name'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Fetch team details from the database using the team_id stored in cookies
$team_id = $_COOKIE['team_id'];
$team_name = $_COOKIE['team_name'];  // Get the team name from cookies
$stmt = $conn->prepare("SELECT * FROM teams WHERE team_id = ?");
$stmt->bind_param("i", $team_id);
$stmt->execute();
$result = $stmt->get_result();
$team = $result->fetch_assoc();

// Fetch the player list for the specific team where sold_to matches team_name
$player_stmt = $conn->prepare("SELECT name FROM player WHERE sold_to = ?");
$player_stmt->bind_param("s", $team_name); // Compare the team name in sold_to column
$player_stmt->execute();
$player_result = $player_stmt->get_result();

// Close the prepared statement and connection
$stmt->close();
$player_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Dashboard</title>

    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            flex-direction: column;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
            width: 80%;
            max-width: 700px;
            text-align: center;
        }

        h2 {
            font-size: 2.5rem;
            color: #007BFF;
            margin-bottom: 20px;
        }

        h3 {
            font-size: 1.5rem;
            color: #555;
            margin-bottom: 15px;
        }

        p {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 10px;
        }

        .team-logo {
            border-radius: 10px;
            margin: 10px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .logout-link {
            display: inline-block;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.2rem;
            margin-top: 30px;
            transition: background-color 0.3s;
        }

        .logout-link:hover {
            background-color: #0056b3;
        }

        .logout-link:focus {
            outline: none;
        }

        /* Player Cart Styles */
        .player-list {
            margin-top: 40px;
            text-align: left;
        }

        .player-cart {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }

        .player-card {
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .player-card h4 {
            font-size: 1.1rem;
            color: #007BFF;
            margin-bottom: 10px;
        }

        .player-card p {
            font-size: 1rem;
            color: #555;
        }

    </style>
</head>
<body>
    

    <!-- Player Cart Section -->
    <div class="container player-list">
        <h3>Player List:</h3>
        <div class="player-cart">
            <?php
            if ($player_result->num_rows > 0) {
                // Loop through and display players who belong to the current team
                while ($player = $player_result->fetch_assoc()) {
                    echo '<div class="player-card">';
                    echo '<h4>' . $player['name'] . '</h4>';
                    echo '<p>Sold to: ' . $team['team_name'] . '</p>';
                    echo '</div>';
                }
            } else {
                // If no players found for the team, display a message
                echo '<p>No players found for this team.</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>
