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
        // Redirect to dashboard.php if successful
        echo "<script>
            alert('Match schedule uploaded successfully!');
            window.location.href = 'dashboard.php';
        </script>";
    } else {
        // Show an alert message for errors
        echo "<script>
            alert('Error: " . $stmt->error . "');
        </script>";
    }
    
    $stmt->close();
    $conn->close();
    } else {
        echo "<script>
            alert('Invalid request method.');
        </script>";
    }
    
?>
