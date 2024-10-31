<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Difficulty</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Select Difficulty</h2>
        <div class="menu">
            <a href="game_setup.php?difficulty=easy" class="btn">Easy</a>
            <a href="game_setup.php?difficulty=medium" class="btn">Medium</a>
            <a href="game_setup.php?difficulty=hard" class="btn">Hard</a>
            <a href="index.php" class="btn back">Back</a>
        </div>
    </div>
</body>
</html>
