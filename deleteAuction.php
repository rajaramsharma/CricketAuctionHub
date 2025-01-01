<?php
// Start session to check if the user is logged in
session_start();

// Check if the user is logged in by checking for 'user_id' in the session
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in first.'); window.location.href='login.php';</script>";
    exit;
}

// Get the logged-in user's user_id from session
$userId = $_SESSION['user_id'];

// Include database configuration
$host = 'localhost';
$dbname = 'cricket';
$username = 'root';
$password = '';

// Check if auctionId is provided in the URL
if (isset($_GET['auctionId'])) {
    $auctionId = $_GET['auctionId'];

    try {
        // Establish a PDO database connection
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL query to delete the auction for the logged-in user
        $stmt = $conn->prepare("DELETE FROM auctions WHERE auction_id = :auctionId AND user_id = :userId");
        $stmt->bindParam(':auctionId', $auctionId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                // Auction deleted successfully
                echo "<script>alert('Auction deleted successfully.'); window.location.href='dashboard.php';</script>";
            } else {
                // No matching auction found
                echo "<script>alert('Auction not found or you do not have permission to delete this auction.'); window.location.href='dashboard.php';</script>";
            }
        } else {
            // Error while executing the query
            echo "<script>alert('Error: Could not delete the auction.'); window.location.href='dashboard.php';</script>";
        }
    } catch (PDOException $e) {
        // Handle database connection or query errors
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='dashboard.php';</script>";
    }

    // Close the connection
    $conn = null;
} else {
    // Auction ID not provided in the URL
    echo "<script>alert('Invalid request. Auction ID not provided.'); window.location.href='dashboard.php';</script>";
    exit;
}
?>
