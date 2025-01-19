<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cricket";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get auction_id from the URL (assuming it's passed via query string)
    $auction_id = isset($_GET['auction_id']) ? intval($_GET['auction_id']) : 0;

    // Get player details from the form
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
    $mobile_no = isset($_POST['mobile_no']) ? $conn->real_escape_string($_POST['mobile_no']) : '';
    $form_no = isset($_POST['form_no']) ? $conn->real_escape_string($_POST['form_no']) : '';
    $father_name = isset($_POST['father_name']) ? $conn->real_escape_string($_POST['father_name']) : '';
    $age = isset($_POST['age']) ? intval($_POST['age']) : 0;
    $playing_style = isset($_POST['playing_style']) ? $conn->real_escape_string($_POST['playing_style']) : '';
    $tshirt_size = isset($_POST['tshirt_size']) ? $conn->real_escape_string($_POST['tshirt_size']) : '';
    $jersey_name = isset($_POST['jersey_name']) ? $conn->real_escape_string($_POST['jersey_name']) : '';
    $jersey_number = isset($_POST['jersey_number']) ? intval($_POST['jersey_number']) : 0;
    $trouser_size = isset($_POST['trouser_size']) ? $conn->real_escape_string($_POST['trouser_size']) : '';
    $detail = isset($_POST['detail']) ? $conn->real_escape_string($_POST['detail']) : '';
    $base_value = isset($_POST['base_value']) ? floatval($_POST['base_value']) : 0.0;

    // Initialize profile_pic variable
    $profile_pic = null;

    // Handle file upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
        }

        $imageFileType = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
        $unique_file_name = uniqid() . "." . $imageFileType;
        $target_file = $target_dir . $unique_file_name;

        // Validate the file
        $check = getimagesize($_FILES['profile_pic']['tmp_name']);
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if ($check !== false && in_array($imageFileType, $allowed_types) && $_FILES['profile_pic']['size'] <= 5000000) {
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
                $profile_pic = $unique_file_name; // Store file name for database
            } else {
                echo "Error moving uploaded file.";
            }
        } else {
            echo "Error: Invalid file. Only JPG, JPEG, PNG, GIF are allowed, and size must be <= 5MB.";
        }
    }

    // Insert player into database with auction_id
    $sql = "INSERT INTO players 
            (name, mobile_no, form_no, father_name, age, playing_style, tshirt_size, jersey_name, jersey_number, trouser_size, detail, base_value, auction_id, profile_pic)
            VALUES 
            ('$name', '$mobile_no', '$form_no', '$father_name', '$age', '$playing_style', '$tshirt_size', '$jersey_name', '$jersey_number', '$trouser_size', '$detail', '$base_value', '$auction_id', '$profile_pic')";

    if ($conn->query($sql) === TRUE) {
        echo "Player added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Players</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .form-container { width: 80%; margin: auto; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; margin-top: 5px; }
        .button { background-color: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; margin-top: 20px; }
        .inline { display: inline-block; width: 48%; }
        .inline:nth-child(odd) { margin-right: 4%; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add Players</h1>
        <form method="POST" enctype="multipart/form-data" action="">
            <label>Profile Pic</label>
            <input type="file" name="profile_pic">
            
            <div class="inline">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="inline">
                <label>Mobile No</label>
                <input type="text" name="mobile_no" required>
            </div>

            <div class="inline">
                <label>Form No</label>
                <input type="text" name="form_no" required>
            </div>
            <div class="inline">
                <label>Age</label>
                <input type="number" name="age">
            </div>

            <div class="inline">
                <label>Father Name</label>
                <input type="text" name="father_name">
            </div>
            
            <label>Playing Style</label>
            <select name="playing_style">
                <option value="Batsman">Batsman</option>
                <option value="Bowler">Bowler</option>
                <option value="All-Rounder">All-Rounder</option>
                <option value="Wicket-Keeper-Batsman">Wicket-Keeper-Batsman</option>
            </select>

            <div class="inline">
                <label>T-shirt Size</label>
                <select name="tshirt_size">
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                </select>
            </div>
            <div class="inline">
                <label>Trouser Size</label>
                <select name="trouser_size">
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                </select>
            </div>

            <label>Detail</label>
            <textarea name="detail"></textarea>

            <div class="inline">
                <label>Jersey Name</label>
                <input type="text" name="jersey_name">
            </div>
            <div class="inline">
                <label>Jersey Number</label>
                <input type="number" name="jersey_number">
            </div>

            <label>Base Value</label>
            <input type="number" name="base_value">

            <button type="submit" class="button">Add Player</button>
            <button type="reset" class="button">Save and Add New</button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
