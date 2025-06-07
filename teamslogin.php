<?php

// db_connection.php

// Create a connection to the database
$conn = new mysqli('localhost', 'root', '', 'cricket');

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture the form data
    $tuser = $_POST['tuser'];
    $tpassword = $_POST['tpassword'];

    // Prepare the query to check the credentials
    $stmt = $conn->prepare("SELECT team_name, tpassword, team_id FROM teams WHERE tuser = ?");
    $stmt->bind_param("s", $tuser);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($tpassword, $row['tpassword'])) {
            // Set cookies for 1 hour
            setcookie('team_id', $row['team_id'], time() + 3600, '/');
            setcookie('team_name', $row['team_name'], time() + 3600, '/');

            // Redirect to teamdashboard.php
            header("Location: teamdashboard.php");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "Invalid username.";
    }

    // Close the prepared statement
    $stmt->close();
    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Login</title>

    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f2f3f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        /* Heading Style */
        h2 {
            font-size: 2.5rem;
            color: #333;
            text-align: center;
            margin-bottom: 25px;
            animation: fadeInUp 1s ease-out;
        }

        /* Fade-in-up animation for heading */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Style */
        form {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
            width: 350px;
            text-align: center;
            animation: fadeIn 1.5s ease-out;
        }

        /* Fade-in animation for form */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Labels */
        label {
            display: block;
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 8px;
            text-align: left;
            font-weight: 500;
        }

        /* Input Fields */
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 20px;
            margin: 10px 0 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 1.1rem;
            outline: none;
            transition: all 0.3s ease;
        }

        /* Input Focus */
        input[type="text"]:focus,
        input[type="password"]:focus {
            border: 1px solid #007BFF;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Submit Button */
        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: #007BFF;
            color: #fff;
            border-radius: 8px;
            font-size: 1.2rem;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease, transform 0.2s ease-in-out;
        }

        /* Hover effect for button */
        input[type="submit"]:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        /* Error Message */
        .error {
            color: #ff4d4d;
            font-size: 1rem;
            margin-top: 15px;
            animation: shake 0.5s ease-in-out;
        }

        /* Shake animation for error */
        @keyframes shake {
            0% {
                transform: translateX(0);
            }
            25% {
                transform: translateX(-10px);
            }
            50% {
                transform: translateX(10px);
            }
            75% {
                transform: translateX(-10px);
            }
            100% {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Team Login</h2>
        <form action="" method="POST">
            <label for="tuser">Username:</label>
            <input type="text" id="tuser" name="tuser" required>
            
            <label for="tpassword">Password:</label>
            <input type="password" id="tpassword" name="tpassword" required>
            
            <input type="submit" value="Login">

            <?php
            // Display error message if any
            if (!empty($error_message)) {
                echo '<p class="error">' . $error_message . '</p>';
            }
            ?>
        </form>
    </div>

</body>
</html>
