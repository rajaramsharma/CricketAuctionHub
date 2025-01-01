<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your password
$dbname = "cricket";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all teams from the database
$sql = "SELECT * FROM teams";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .team-card {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .card img {
            width: 100%;
            height: auto;
            border-radius: 4px;
        }
        .card h3 {
            font-size: 18px;
            margin: 10px 0;
        }
        .card p {
            font-size: 14px;
            color: #555;
        }
        .add-team {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .add-team:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Teams</h1>
            <a href="addTeam.php" class="add-team">Add New Team</a>
        </div>
        
        <div class="team-card">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <img src="<?php echo $row['team_logo']; ?>" alt="Team Logo">
                        <h3><?php echo $row['team_name']; ?> (<?php echo $row['team_short_name']; ?>)</h3>
                        <p>Shortcut Key: <?php echo $row['shortcut_key']; ?></p>
                        <p>Created At: <?php echo $row['created_at']; ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No teams found. Add a team to get started!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
