<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Auction</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your CSS file here -->
</head>
<body>
    <div class="container">
        <h1>Create Auction</h1>
        <form action="submitauction.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="auctionLogo">Auction Logo</label>
                <input type="file" id="auctionLogo" name="auctionLogo" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="sportsType">Sports Type*</label>
                <select id="sportsType" name="sportsType" required>
                    <option value="Cricket">Cricket</option>
                    <!-- Add more options if needed -->
                </select>
            </div>

            <div class="form-group">
                <label for="season">Season</label>
                <select id="season" name="season" required>
                    <option value="">--Select--</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <!-- Add more seasons if needed -->
                </select>
            </div>

            <div class="form-group">
                <label for="auctionName">Auction Name*</label>
                <input type="text" id="auctionName" name="auctionName" placeholder="Auction Name" required>
            </div>

            <div class="form-group">
                <label for="auctionDate">Auction Date*</label>
                <input type="date" id="auctionDate" name="auctionDate" required>
            </div>

            <div class="form-group">
                <label for="auctionTime">Auction Time*</label>
                <input type="time" id="auctionTime" name="auctionTime" required>
            </div>

            <div class="form-group">
                <label for="pointsPerTeam">Points Per Team*</label>
                <input type="number" id="pointsPerTeam" name="pointsPerTeam" placeholder="Points" required>
            </div>

            <div class="form-group">
                <label for="baseBid">Base Bid*</label>
                <input type="number" id="baseBid" name="baseBid" placeholder="Minimum Bid" required>
            </div>

            <div class="form-group">
                <label for="bidIncreaseBy">Bid Increase by*</label>
                <input type="number" id="bidIncreaseBy" name="bidIncreaseBy" placeholder="Bid Increase" required>
            </div>

            <div class="form-group">
                <label for="playerPerTeamMax">Player Per Team (Max Limit)*</label>
                <input type="number" id="playerPerTeamMax" name="playerPerTeamMax" placeholder="Max Limit" required>
            </div>

            <div class="form-group">
                <label for="playerPerTeamMin">Player Per Team (Min Limit)*</label>
                <input type="number" id="playerPerTeamMin" name="playerPerTeamMin" placeholder="Min Limit" required>
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>

    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        /* Container */
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 650px;
        }

        /* Heading */
        h1 {
            font-size: 32px;
            color: #2b3e50;
            margin-bottom: 30px;
            text-align: center;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        select,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-top: 5px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        /* Focus Effect */
        input:focus, select:focus {
            border-color: #5c6bc0;
            box-shadow: 0 0 5px rgba(92, 107, 192, 0.5);
            outline: none;
        }

        /* Button Styling */
        button {
            background-color: #4caf50;
            color: white;
            padding: 15px;
            border: none;
            width: 100%;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Media Query for Responsive Design */
        @media (max-width: 600px) {
            h1 {
                font-size: 28px;
            }

            .container {
                padding: 20px;
            }

            button {
                font-size: 16px;
            }
        }
    </style>
</body>
</html>
