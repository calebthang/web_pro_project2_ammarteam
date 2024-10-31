<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Game Paused - Snakes & Ladders</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Game Paused</h2>
        <div class="menu">
            <a href="game_board.php" class="btn">Resume Game</a>
            <a href="index.php" class="btn">Quit to Menu</a>
        </div>
    </div>
    <div id="pauseOverlay" class="pause-overlay <?php echo isset($_GET['paused']) ? 'active' : ''; ?>">
    <div class="pause-menu">
        <h2>Game Paused</h2>
        <div class="pause-buttons">
            <a href="?resume=true" class="btn pause-btn">Resume Game</a>
            <a href="index.php" class="btn pause-btn">Quit to Menu</a>
        </div>
    </div>
</div>

<?php
// Add this to the top of game_board.php with the other PHP code:
if (isset($_GET['paused'])) {
    // Game is paused
    $isPaused = true;
} elseif (isset($_GET['resume'])) {
    // Game is resumed
    header('Location: game_board.php');
    exit;
}
?>
</body>
</html>