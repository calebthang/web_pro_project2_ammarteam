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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="game-title">Snakes & Ladders</h1>
        <div class="main-menu">
            <a href="start_game.php" class="btn btn-start">🎮 Start Game</a>
            <a href="settings.php" class="btn btn-settings">⚙️ Settings</a>
            <a href="instructions.php" class="btn btn-instructions">📖 Instructions</a>
        </div>
    </div>
</body>
</html>
