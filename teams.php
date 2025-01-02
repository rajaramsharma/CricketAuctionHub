<?php
session_start();
// Assuming session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Teams</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #007bff;
    color: #fff;
    padding: 20px;
}

.header h1 {
    margin: 0;
}

.add-team {
    text-decoration: none;
    padding: 10px 20px;
    background: linear-gradient(90deg, #4caf50, #00c853);
    color: white;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 25px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    text-align: center;
}

.add-team:hover {
    background: linear-gradient(90deg, #00c853, #4caf50);
    box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.2);
    transform: scale(1.05);
}

.container {
    width: 90%;
    margin: auto;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.card {
    position: relative;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    width: 300px;
    overflow: hidden;
    text-align: center;
}

.card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.card h2 {
    margin: 10px 0;
    font-size: 1.5em;
    color: #333;
}

.card p {
    color: #666;
    margin: 5px 0;
}

.delete-btn {
    position: absolute;
    bottom: 10px;
    left: 10px;
    padding: 8px 15px;
    background-color: #e74c3c;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.delete-btn:hover {
    background-color: #c0392b;
}

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="header">
        <h1>Teams</h1>
        <a href="addTeam.php" class="add-team">Add New Team</a>
    </div>
    
    <div class="container">
        <?php
        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'cricket');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch data from the database
        $sql = "SELECT team_id, team_logo, team_name, team_short_name, shortcut_key FROM teams";
        $result = $conn->query($sql);

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
            echo "<p style='text-align: center;'>No teams found.</p>";
        }

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
