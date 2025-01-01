<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $team_name = $_POST['team_name'];
    $team_short_name = $_POST['team_short_name'];
    $shortcut_key = $_POST['shortcut_key'];
    
    // Handle file upload for team logo
    if (isset($_FILES['team_logo']) && $_FILES['team_logo']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['team_logo']['name']);
        
        // Get the file extension
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is a valid image
        $check = getimagesize($_FILES['team_logo']['tmp_name']);
        if ($check !== false) {
            // File is an image, now check if the format is allowed
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif']; // Allowed file types
            if (in_array($imageFileType, $allowed_types)) {
                // Validate file size (e.g., 5MB)
                if ($_FILES['team_logo']['size'] <= 5000000) {
                    // Move the uploaded file to the target directory
                    if (move_uploaded_file($_FILES['team_logo']['tmp_name'], $target_file)) {
                        echo "File uploaded successfully: " . $target_file;
                        $team_logo = $target_file;
                    } else {
                        echo "Error moving uploaded file.";
                        $team_logo = NULL;
                    }
                } else {
                    echo "Error: File size exceeds the allowed limit (5MB).";
                    $team_logo = NULL;
                }
            } else {
                echo "Error: Only JPG, JPEG, PNG & GIF files are allowed.";
                $team_logo = NULL;
            }
        } else {
            echo "Error: File is not an image.";
            $team_logo = NULL;
        }
    } else {
        if ($_FILES['team_logo']['error'] != 0) {
            echo "Error with file upload: " . $_FILES['team_logo']['error'];
        }
        $team_logo = NULL;
    }

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'cricket');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert query
    $sql = "INSERT INTO teams (team_logo, team_name, team_short_name, shortcut_key) 
            VALUES ('$team_logo', '$team_name', '$team_short_name', '$shortcut_key')";

    if ($conn->query($sql) === TRUE) {
        echo "New team added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
