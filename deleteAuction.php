<?php
// Include database configuration
$host = 'localhost';
$dbname = 'cricket';
$username = 'root';
$password = '';

// Check if you really want to delete all auctions
if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {

    try {
        // Establish a PDO database connection
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the delete query to remove all auctions
        $stmt = $conn->prepare("DELETE FROM auctions");

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('All auctions deleted successfully!'); window.location.href='auctionList.php';</script>";
        } else {
            echo "<script>alert('Failed to delete auctions.'); window.location.href='auctionList.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='auctionList.php';</script>";
    }

    // Close the connection
    $conn = null;
} else {
    // If confirmation is not given, show a warning and ask for confirmation
    echo "<script>
            if (confirm('Are you sure you want to delete all auctions? This action cannot be undone.')) {
                window.location.href='deleteauction.php?confirm=yes';
            } else {
                window.location.href='auctionList.php';
            }
          </script>";
}
?>
