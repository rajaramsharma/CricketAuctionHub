<?php
// db_connection.php
$conn = new mysqli('localhost', 'root', '', 'cricket');

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the last bid price from the bidding table
$query = "SELECT bid_price FROM bidding ORDER BY id DESC LIMIT 1";
$result = $conn->query($query);

// Check if there is a result and fetch the data
if ($result->num_rows > 0) {
    $bidding_info = $result->fetch_assoc();
    $last_bid_price = $bidding_info['bid_price'];
} else {
    $last_bid_price = 'No bids yet';  // If no bid exists
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Last Bid Price</title>
    
    <style>
        /* Basic Styling */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f4f4f9; color: #333; text-align: center; }
        .container { background-color: #fff; padding: 20px; margin: 20px auto; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); width: 80%; max-width: 700px; }
        h2 { color: #333; }
        .bid-price { font-size: 2em; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Last Bid Price</h2>
        <p class="bid-price"><?php echo $last_bid_price; ?></p>
    </div>
</body>
</html>
