<?php
// logout.php

// Clear cookies by setting their expiration to the past
setcookie('team_id', '', time() - 3600, '/');
setcookie('team_name', '', time() - 3600, '/');

// Redirect to the login page
header("Location: userdashboard.php");
exit();
?>
