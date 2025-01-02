<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $team_name = $_POST['team_name'];
    $team_short_name = $_POST['team_short_name'];
    $shortcut_key = $_POST['shortcut_key'];
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
                if ($_FILES['team_logo']['size'] <= 5000000) {
                    // Sanitize file name
                    $unique_file_name = uniqid() . "." . $imageFileType;
                    $target_file = $target_dir . $unique_file_name;

                    if (move_uploaded_file($_FILES['team_logo']['tmp_name'], $target_file)) {
                        echo "File uploaded successfully: " . $target_file;
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
    } else {
        echo "Error with file upload: " . $_FILES['team_logo']['error'];
    }

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'cricket');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use prepared statement to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO teams (team_logo, team_name, team_short_name, shortcut_key) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $team_logo, $team_name, $team_short_name, $shortcut_key);

    if ($stmt->execute()) {
        echo "New team added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
