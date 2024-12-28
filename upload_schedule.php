<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'cricket');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $team1 = $_POST['team1'];
    $team2 = $_POST['team2'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO matchschedule (team1, team2, date, time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $team1, $team2, $date, $time);

    if ($stmt->execute()) {
        echo "<p style='text-align: center; color: green;'>Match schedule uploaded successfully!</p>";
    } else {
        echo "<p style='text-align: center; color: red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p style='text-align: center; color: red;'>Invalid request method.</p>";
}
?>
