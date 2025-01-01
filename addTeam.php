<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Team</title>
</head>
<body>
    <h2>Add a New Team</h2>
    <form action="add_team.php" method="POST" enctype="multipart/form-data">
        <label for="team_logo">Team Logo (Image):</label><br>
        <input type="file" id="team_logo" name="team_logo" accept="image/*"><br><br>
        
        <label for="team_name">Team Full Name:</label><br>
        <input type="text" id="team_name" name="team_name" required><br><br>
        
        <label for="team_short_name">Team Short Name:</label><br>
        <input type="text" id="team_short_name" name="team_short_name" required><br><br>
        
        <label for="shortcut_key">Shortcut Key:</label><br>
        <input type="text" id="shortcut_key" name="shortcut_key" required><br><br>
        
        <input type="submit" value="Add Team">
    </form>
</body>
</html>
