<?php
session_start();
if(isset($_POST['continue'])) {
    $_SESSION['player_count'] = $_POST['players'];
    header('Location: game_setup.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Start Game - Snakes & Ladders</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Start New Game</h2>
        <div class="game-options">
            <form method="POST">
                <div class="player-count">
                    <label for="players">Number of Players:</label>
                    <select name="players" id="players" class="select-input">
                        <option value="2">2 Players</option>
                        <option value="3">3 Players</option>
                        <option value="4">4 Players</option>
                    </select>
                </div>
                <button type="submit" name="continue" class="btn">Continue</button>
            </form>
            <a href="index.php" class="btn back">Back</a>
        </div>
    </div>
</body>
</html>