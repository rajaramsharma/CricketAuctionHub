<?php include 'header.php'; ?>
<?php
// Start the session


// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not authenticated
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$userId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Auctions</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your CSS file here -->
    <style>
        /* Global Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #fdfbfb, #ebedee);
            font-family: Arial, sans-serif;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        h1 {
            font-size: 2.5rem;
            color: #444;
        }

        .auction-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background-color: #fff;
            width: 300px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .card h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #4b79a1;
        }

        .card p {
            font-size: 1rem;
            margin: 5px 0;
            color: #555;
        }

        .card button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .card button:hover {
            background-color: #45a049;
        }
    </style>
    <script>
    function redirectToDetail(auctionId) {
        window.location.href = "viewdetail.php?auction_id=" + auctionId;
    }
    </script>
</head>
<body>
    <div class="header">
        <h1>My Auctions</h1>
    </div>

    <div class="auction-cards">
        <?php
        // Connect to the database
        $conn = new mysqli('localhost', 'root', '', 'cricket');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch auctions created by the logged-in user
        $sql = "SELECT auction_id, auction_name, sports_type, auction_date 
                FROM auctions 
                WHERE user_id = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $userId); // Bind the user ID from the session
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="card">';
                    echo '<h2>Auction Name: ' . htmlspecialchars($row['auction_name']) . '</h2>';
                    echo '<p><strong>Sports Type:</strong> ' . htmlspecialchars($row['sports_type']) . '</p>';
                    echo '<p><strong>Auction Date:</strong> ' . htmlspecialchars($row['auction_date']) . '</p>';
                    echo '<button onclick="redirectToDetail(' . $row['auction_id'] . ')">View Details</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>No auctions available.</p>';
            }

            $stmt->close(); // Close the prepared statement
        } else {
            echo "Error: " . $conn->error;
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>
</body>
</html>
