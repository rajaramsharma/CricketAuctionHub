<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cricket');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['team_id'])) {
    $team_id = intval($_POST['team_id']);
    
    // Prepare statement for secure deletion
    $sql = "DELETE FROM teams WHERE team_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $team_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Team deleted successfully!";
    } else {
        $_SESSION['message'] = "Failed to delete the team.";
    }
    $stmt->close();
}
$conn->close();
header('Location: teams.php');
exit;
?>
