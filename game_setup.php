<?php
session_start();
if(!isset($_SESSION['player_count'])) {
    header('Location: start_game.php');
    exit();
}

if(isset($_POST['start'])) {
    $_SESSION['player_names'] = $_POST['player_names'];
    $_SESSION['difficulty'] = $_POST['difficulty'];
    header('Location: game_board.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Game Setup - Snakes & Ladders</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Game Setup</h2>
        <form method="POST" class="setup-form">
            <div class="difficulty-select">
                <label>Select Difficulty:</label>
                <select name="difficulty" class="select-input" required>
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
            
            <div class="player-names">
                <label>Player Names:</label>
                <?php for($i = 1; $i <= $_SESSION['player_count']; $i++): ?>
                    <input type="text" 
                           name="player_names[]" 
                           placeholder="Player <?php echo $i; ?>" 
                           class="name-input"
                           required>
                <?php endfor; ?>
            </div>
            
            <button type="submit" name="start" class="btn">Start Game</button>
            <a href="start_game.php" class="btn back">Back</a>
        </form>
    </div>
</body>
</html>
