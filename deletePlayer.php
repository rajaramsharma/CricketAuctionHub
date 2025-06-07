<?php
// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    echo 'Unauthorized access';
    exit;
}

// Check if player_id is provided in the POST request
if (!isset($_POST['player_id']) || empty($_POST['player_id'])) {
    echo 'Invalid request';
    exit;
}

$playerId = intval($_POST['player_id']); // Sanitize player_id

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cricket');

// Check connection
if ($conn->connect_error) {
    echo 'Database connection failed';
    exit;
}

// Prepare DELETE statement
$sql = "DELETE FROM player WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo 'Failed to prepare statement';
    exit;
}

$stmt->bind_param("i", $playerId);

// Execute statement
if ($stmt->execute()) {
    echo 'success';
} else {
    echo 'Error deleting player';
}

// Clean up
$stmt->close();
$conn->close();
?>
