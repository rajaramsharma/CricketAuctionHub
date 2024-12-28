<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'cricket');

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch match schedule from the database
$sql = "SELECT team1, team2, date, time FROM matchschedule ORDER BY date ASC";
$result = $conn->query($sql);

// Get server's current timestamp
$serverTime = time();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .remaining-time {
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Upcoming Match Schedule</h1>

    <?php
    if ($result->num_rows > 0) {
        // Display the schedule in a table
        echo "<table>";
        echo "<tr><th>Team 1</th><th>Team 2</th><th>Date</th><th>Time</th><th>Remaining Time</th></tr>";

        $index = 0; // To assign unique IDs for each match
        while ($row = $result->fetch_assoc()) {
            $matchDateTime = $row['date'] . ' ' . $row['time'];
            $timestamp = strtotime($matchDateTime); // Convert to Unix timestamp
            echo "<tr>
                    <td>" . htmlspecialchars($row['team1']) . "</td>
                    <td>" . htmlspecialchars($row['team2']) . "</td>
                    <td>" . htmlspecialchars($row['date']) . "</td>
                    <td>" . htmlspecialchars($row['time']) . "</td>
                    <td><span class='remaining-time' id='remaining-time-$index' data-timestamp='$timestamp'>Calculating...</span></td>
                  </tr>";
            $index++;
        }

        echo "</table>";
    } else {
        echo "<p style='text-align: center; color: red;'>No upcoming matches found.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>

</div>

<script>
    const serverTime = <?php echo $serverTime; ?> * 1000; // Convert PHP time to milliseconds
    const clientTime = new Date().getTime();
    const timeOffset = clientTime - serverTime; // Calculate offset between client and server

    console.log("Server Time (ms):", serverTime);
    console.log("Client Time (ms):", clientTime);
    console.log("Time Offset (ms):", timeOffset);

    // Function to calculate remaining time
    function calculateRemainingTime() {
        document.querySelectorAll('.remaining-time').forEach(element => {
            const matchTimestamp = parseInt(element.getAttribute('data-timestamp')) * 1000; // Convert seconds to ms
            const adjustedMatchTime = matchTimestamp + timeOffset; // Adjust match time for offset

            const currentTime = new Date().getTime();
            const timeDiff = adjustedMatchTime - currentTime;

            if (timeDiff <= 0) {
                element.innerText = "Match Started";
            } else {
                const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

                let remainingTime = "";
                if (days > 0) remainingTime += `${days}d `;
                remainingTime += `${hours}h ${minutes}m ${seconds}s`;

                element.innerText = remainingTime;
            }
        });
    }

    // Update remaining time every second
    setInterval(calculateRemainingTime, 1000);
    calculateRemainingTime(); // Initial calculation
</script>

</body>
</html>
