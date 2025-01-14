<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Team</title>
    <style>
        /* Romantic Theme CSS */
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #ffcccc, #ffe6e6);
            margin: 0;
            padding: 0;
            color: #4d004d;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            font-size: 2.5em;
            color: #800040;
            text-shadow: 1px 1px 2px #ffe6f2;
        }

        form {
            max-width: 500px;
            margin: 30px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            border: 2px solid #ffc0cb;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 1.2em;
            color: #99004d;
        }

        input[type="text"],
        input[type="file"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #ff99cc;
            border-radius: 10px;
            font-size: 1em;
            background: #fff0f5;
            color: #4d004d;
        }

        input[type="text"]:focus,
        input[type="file"]:focus {
            outline: none;
            border-color: #ff66a3;
            box-shadow: 0 0 5px #ff99cc;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, #ff6699, #ff99cc);
            border: none;
            color: white;
            font-size: 1.2em;
            border-radius: 10px;
            cursor: pointer;
            text-shadow: 1px 1px 2px #800040;
            transition: transform 0.2s, background 0.3s;
        }

        input[type="submit"]:hover {
            background: linear-gradient(135deg, #ff3366, #ff6699);
            transform: translateY(-3px);
        }

        input[type="submit"]:active {
            transform: translateY(0);
        }

        .alert {
            color: #ff0000;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Add a New Team</h2>
    <?php
    // Get the auction_id from the query string
    if (!isset($_GET['auction_id']) || empty($_GET['auction_id'])) {
        echo "<script>alert('Auction ID is missing or invalid.'); window.location.href='auctionList.php';</script>";
        exit;
    }
    $auction_id = intval($_GET['auction_id']); // Sanitize the auction ID
    ?>
    <form action="add_team.php" method="POST" enctype="multipart/form-data">
        <!-- Hidden input to pass auction ID -->
        <input type="hidden" name="auction_id" value="<?php echo $auction_id; ?>">
        
        <label for="team_logo">Team Logo (Image):</label>
        <input type="file" id="team_logo" name="team_logo" accept="image/*">
        
        <label for="team_name">Team Full Name:</label>
        <input type="text" id="team_name" name="team_name" required>
        
        <label for="team_short_name">Team Short Name:</label>
        <input type="text" id="team_short_name" name="team_short_name" required>
        
        <label for="shortcut_key">Shortcut Key:</label>
        <input type="text" id="shortcut_key" name="shortcut_key" required>
        
        <input type="submit" value="Add Team">
    </form>
</body>
</html>
