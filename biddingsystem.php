<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cricket"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch teams from biddingteam table
$teams = [];
$sql = "SELECT team_list FROM biddingteam";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teams[] = $row['team_list'];
    }
}

// Handle bid submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_bid'])) {
    $bid_price = $_POST['bid_price'];
    $bidding_team = $_POST['bidding_team'];

    $sql = "INSERT INTO bidding (bid_price, bidding_team) VALUES ('$bid_price', '$bidding_team')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle table reset
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_bidding'])) {
    $sql = "DELETE FROM bidding";
    if ($conn->query($sql) === TRUE) {
        echo "All bids have been reset.";
    } else {
        echo "Error resetting bids: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bid Form</title>
</head>
<body>
    <h2>Post Your Bid</h2>
    <form method="POST" action="">
        <label for="bid_price">Bid Price: </label>
        <input type="text" id="bid_price" name="bid_price" required><br><br>

        <label for="bidding_team">Bidding Team Name: </label>
        <select id="bidding_team" name="bidding_team" required>
            <option value="">Select a Team</option>
            <?php foreach ($teams as $team) { ?>
                <option value="<?php echo htmlspecialchars($team); ?>"><?php echo htmlspecialchars($team); ?></option>
            <?php } ?>
        </select><br><br>

        <button type="submit" name="submit_bid">Post Bid</button>
    </form>

    <h2>Reset Bidding Table</h2>
    <form method="POST" action="">
        <button type="submit" name="reset_bidding" style="background-color: red; color: white;">Reset Bidding</button>
    </form>
</body>
</html>











<?php
// db_connection.php
$conn = new mysqli('localhost', 'root', '', 'cricket');

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the list of passed teams from the bidding_pass_team table
$query = "SELECT pass_team_list FROM bidding_pass_team";
$result = $conn->query($query);

// Handle delete all button click action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_all'])) {
    // Delete all rows from the bidding_pass_team table
    $delete_query = "DELETE FROM bidding_pass_team";
    if ($conn->query($delete_query) === TRUE) {
        echo "All passed teams have been deleted.";
    } else {
        echo "Error: " . $delete_query . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passed Teams List</title>
    <style>
        /* Basic Styling */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f4f4f9; color: #333; text-align: center; }
        .container { background-color: #fff; padding: 20px; margin: 20px auto; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); width: 80%; max-width: 700px; }
        .buttons { margin-top: 20px; }
        .buttons button { padding: 10px 20px; margin: 5px; cursor: pointer; background-color: #f44336; color: white; border: none; border-radius: 5px; }
        .buttons button:hover { background-color: #e41c2b; }
    </style>
</head>
<body>
    <div class="container">
        <h2>List of Passed Teams</h2>
        <ul>
            <?php
            // Display the list of passed teams
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li>" . $row['pass_team_list'] . "</li>";
                }
            } else {
                echo "<li>No teams have passed their bid yet.</li>";
            }
            ?>
        </ul>
        
        <div class="buttons">
            <!-- Button to delete all passed teams -->
            <form method="POST">
                <button type="submit" name="delete_all">Delete All Passed Teams</button>
            </form>
        </div>
    </div>
</body>
</html>
