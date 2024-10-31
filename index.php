<?php
session_start();
// Clear any existing game session
session_unset();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Snakes & Ladders</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Snakes & Ladders</h1>
        <div class="menu">
            <a href="start_game.php" class="btn">Start Game</a>
            <a href="settings.php" class="btn">Settings</a>
            <a href="instructions.php" class="btn">Instructions</a>
        </div>
    </div>
</body>
</html>
