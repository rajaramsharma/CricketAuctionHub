<?php include 'header.php'; ?>
<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session


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

// Check if auction_id is passed via GET and store it in the session
if (isset($_GET['auction_id'])) {
    $auction_id = intval($_GET['auction_id']);
    $_SESSION['auction_id'] = $auction_id; // Save auction_id in the session
} elseif (isset($_SESSION['auction_id'])) {
    $auction_id = $_SESSION['auction_id']; // Retrieve auction_id from session
} else {
    die("Error: Auction ID not provided. Please access this page from the auction list.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        echo "<script>
            alert('Player added successfully!');
            window.location.href = 'player.php?auction_id=$auction_id';
        </script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
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
        /* Global Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to bottom right, #f4f8fb, #ffffff);
            color: #333;
            line-height: 1.6;
        }

        /* Form Container */
        .form-container {
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #007bff;
            text-align: center;
        }

        /* Form Group Styling */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: #555;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 6px rgba(0, 123, 255, 0.3);
            outline: none;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input[type="file"] {
            padding: 4px;
        }

        /* Submit Button Styling */
        .button {
            display: inline-block;
            width: 100%;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            padding: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-align: center;
        }

        .button:hover {
            background-color: #0056b3;
            transform: scale(1.03);
        }

        .button:active {
            transform: scale(0.98);
        }

        /* Error Messages (Optional Example) */
        .error {
            color: #e63946;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }

            label, input, select, textarea {
                font-size: 13px;
            }

            .button {
                font-size: 14px;
            }
        }
    </style>
    </head>
<body>
    <div class="form-container">

        <form method="POST" enctype="multipart/form-data" action="">
            <label>Profile Pic</label>
            <input type="file" name="profile_pic">
            
            <label>Name</label>
            <input type="text" name="name" required>
            
            <label>Mobile No</label>
            <input type="text" name="mobile_no" required>
            
            <label>Form No</label>
            <input type="text" name="form_no" required>
            
            <label>Father Name</label>
            <input type="text" name="father_name">
            
            <label>Age</label>
            <input type="number" name="age">
            
            <label>Playing Style</label>
            <select name="playing_style">
                <option value="Batsman">Batsman</option>
                <option value="Bowler">Bowler</option>
                <option value="All-Rounder">All-Rounder</option>
                <option value="Wicket-Keeper-Batsman">Wicket-Keeper-Batsman</option>
            </select>
            
            <label>T-shirt Size</label>
            <select name="tshirt_size">
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
            </select>
            
            <label>Trouser Size</label>
            <select name="trouser_size">
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
            </select>
            
            <label>Detail</label>
            <textarea name="detail"></textarea>
            
            <label>Jersey Name</label>
            <input type="text" name="jersey_name">
            
            <label>Jersey Number</label>
            <input type="number" name="jersey_number">
            
            <label>Base Value</label>
            <input type="number" name="base_value">
            
            <button type="submit" class="button">Add Player</button>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>
