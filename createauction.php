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
    <header>
        <h1>Create Auction</h1>
    </header>

    <div class="container">
        <form action="submitauction.php" method="post" enctype="multipart/form-data">
            
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
        /* Global Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Style */
        body {
            background: linear-gradient(135deg, #a1c4fd, #c2e9fb);
            font-family: 'Helvetica Neue', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
            color: #333;
            padding-top: 30px;
        }

        /* Header */
        header {
            width: 100%;
            background-color: #4b79a1;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 36px;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Container */
        .container {
            background-color: #fff;
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 650px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
            transform: scale(1);
            transition: transform 0.3s ease-in-out;
            margin-top: 50px; /* Space from header */
        }

        .container:hover {
            transform: scale(1.02);
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        label {
            font-size: 16px;
            font-weight: 600;
            color: #666;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        select,
        input[type="file"] {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            border-radius: 10px;
            border: 1px solid #ddd;
            outline: none;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
        }

        /* Focus Effect */
        input:focus,
        select:focus {
            border: 1px solid #4b79a1;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(75, 121, 161, 0.3);
        }

        /* Button Styling */
        button {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border: none;
            width: 100%;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        /* Subtle Hover Effect */
        .form-group:hover {
            transform: translateX(5px);
            transition: transform 0.3s ease;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 28px;
            }

            button {
                font-size: 16px;
            }
        }
    </style>
</body>
</html>
