<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $auction_id = intval($_POST['auction_id']); // Get the auction ID
    $team_name = $_POST['team_name'];
    $team_short_name = $_POST['team_short_name'];
    $shortcut_key = $_POST['shortcut_key'];
    $tuser = $_POST['tuser'];
    $tpassword = password_hash($_POST['tpassword'], PASSWORD_BCRYPT); // Hash password for security
    $team_logo = NULL; // Default value if file upload fails

    // Handle file upload for team logo
    if (isset($_FILES['team_logo']) && $_FILES['team_logo']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
        }

        $target_file = $target_dir . basename($_FILES['team_logo']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is a valid image
        $check = getimagesize($_FILES['team_logo']['tmp_name']);
        if ($check !== false) {
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($imageFileType, $allowed_types)) {
                if ($_FILES['team_logo']['size'] <= 5000000) { // Limit file size to 5MB
                    // Sanitize file name
                    $unique_file_name = uniqid() . "." . $imageFileType;
                    $target_file = $target_dir . $unique_file_name;

                    if (move_uploaded_file($_FILES['team_logo']['tmp_name'], $target_file)) {
                        $team_logo = $unique_file_name; // Store only the file name
                    } else {
                        echo "Error moving uploaded file.";
                    }
                } else {
                    echo "Error: File size exceeds the allowed limit (5MB).";
                }
            } else {
                echo "Error: Only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            echo "Error: File is not an image.";
        }
    }

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'cricket');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch points_per_team from auctions table
    $pointsQuery = "SELECT points_per_team FROM auctions WHERE auction_id = ?";
    $pointsStmt = $conn->prepare($pointsQuery);
    $pointsStmt->bind_param("i", $auction_id);
    $pointsStmt->execute();
    $result = $pointsStmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $points = $row['points_per_team'];

        // Insert team data into teams table, including points, tuser, and tpassword
        $stmt = $conn->prepare("INSERT INTO teams (auction_id, team_logo, team_name, team_short_name, shortcut_key, points, tuser, tpassword) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssiss", $auction_id, $team_logo, $team_name, $team_short_name, $shortcut_key, $points, $tuser, $tpassword);
     
        if ($stmt->execute()) {
            // **Insert team name into the bidding table**
            $biddingStmt = $conn->prepare("INSERT INTO biddingteam (team_list) VALUES (?)");
            $biddingStmt->bind_param("s", $team_name);
            $biddingStmt->execute();
            $biddingStmt->close();

            echo "<script>alert('New team added successfully!'); window.location.href='teams.php?auction_id=$auction_id';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "<script>alert('Auction ID not found.'); window.location.href='createTeam.php';</script>";
    }

    $pointsStmt->close();
    $conn->close();
}
?>
