<?php
session_start();

// Clear game state on page refresh while keeping player setup info
if (isset($_SESSION['game'])) {
    $playerCount = $_SESSION['player_count'];
    $difficulty = $_SESSION['difficulty'];
    $playerNames = $_SESSION['player_names'] ?? [];
    unset($_SESSION['game']);
}

if (!isset($_SESSION['difficulty']) || !isset($_SESSION['player_count'])) {
    header('Location: index.php');
    exit;
}

class SnakesAndLadders {
    private $players = [];
    private $currentPlayer = 0;
    private $gameOver = false;
    private $lastRoll = 0;
    private $message = '';
    private $snakesAndLadders = [];
    
    public function __construct($playerCount, $difficulty, $playerNames) {
        // Initialize players
        for ($i = 0; $i < $playerCount; $i++) {
            $this->players[] = [
                'position' => 1,
                'name' => $playerNames[$i] ?? "Player " . ($i + 1)
            ];
        }
        
        $this->message = "Game started! " . $this->players[0]['name'] . "'s turn.";
        $this->setupBoard($difficulty);
    }
    
    private function setupBoard($difficulty) {
        switch($difficulty) {
            case 'easy':
                $this->snakesAndLadders = [
                    // Ladders (start => end)
                    4 => 14,   // Small ladder
                    9 => 31,   // Medium ladder
                    20 => 38,  // Medium ladder
                    28 => 84,  // Large ladder
                    40 => 59,  // Medium ladder
                    // Snakes (start => end)
                    17 => 7,   // Small snake
                    54 => 34,  // Medium snake
                    62 => 19,  // Large snake
                    64 => 60   // Small snake
                ];
                break;
            case 'medium':
                $this->snakesAndLadders = [
                    // More balanced snakes and ladders
                    8 => 30,   // Ladder
                    21 => 42,  // Ladder
                    28 => 76,  // Ladder
                    50 => 67,  // Ladder
                    71 => 92,  // Ladder
                    // Snakes
                    32 => 10,  // Snake
                    48 => 26,  // Snake
                    56 => 53,  // Snake
                    88 => 24,  // Snake
                    95 => 75   // Snake
                ];
                break;
            case 'hard':
                $this->snakesAndLadders = [
                    // Fewer ladders, more snakes
                    4 => 14,   // Ladder
                    13 => 31,  // Ladder
                    33 => 85,  // Ladder
                    // Many snakes
                    98 => 8,   // Large snake
                    92 => 41,  // Large snake
                    75 => 28,  // Medium snake
                    47 => 16,  // Medium snake
                    36 => 6,   // Medium snake
                    29 => 9,   // Small snake
                    55 => 31   // Small snake
                ];
                break;
        }
    }
    
    public function rollDice() {
        if ($this->gameOver) return;
        
        $this->lastRoll = rand(1, 6);
        $currentPos = $this->players[$this->currentPlayer]['position'];
        $newPos = $currentPos + $this->lastRoll;
        
        if ($newPos > 100) {
            $this->message = "Roll too high! You need exactly " . (100 - $currentPos) . " to win!";
            $this->nextTurn();
            return;
        }
        
        $this->players[$this->currentPlayer]['position'] = $newPos;
        $this->message = $this->players[$this->currentPlayer]['name'] . " rolled a " . $this->lastRoll;
        
        if (isset($this->snakesAndLadders[$newPos])) {
            $finalPos = $this->snakesAndLadders[$newPos];
            $this->players[$this->currentPlayer]['position'] = $finalPos;
            
            if ($finalPos > $newPos) {
                $this->message .= " and climbed a ladder to " . $finalPos . "!";
            } else {
                $this->message .= " and got bitten by a snake! Moved to " . $finalPos . "!";
            }
        }
        
        if ($this->players[$this->currentPlayer]['position'] === 100) {
            $this->gameOver = true;
            $this->message = $this->players[$this->currentPlayer]['name'] . " wins the game!";
            return;
        }
        
        $this->nextTurn();
    }
    
    private function nextTurn() {
        $this->currentPlayer = ($this->currentPlayer + 1) % count($this->players);
        if (!$this->gameOver) {
            $this->message .= "<br>" . $this->players[$this->currentPlayer]['name'] . "'s turn.";
        }
    }
    
    // Getter methods
    public function getCurrentPlayer() { return $this->players[$this->currentPlayer]; }
    public function getMessage() { return $this->message; }
    public function getPlayers() { return $this->players; }
    public function isGameOver() { return $this->gameOver; }
    public function getLastRoll() { return $this->lastRoll; }
    public function getSnakesAndLadders() { return $this->snakesAndLadders; }
}

// Initialize new game
$_SESSION['game'] = new SnakesAndLadders(
    $_SESSION['player_count'],
    $_SESSION['difficulty'],
    $_SESSION['player_names'] ?? []
);

// Handle actions
if (isset($_POST['roll'])) {
    $_SESSION['game']->rollDice();
}

if (isset($_POST['new_game'])) {
    unset($_SESSION['game']);
    header('Location: start_game.php');
    exit;
}

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
        <div class="game-info">
            <h2>🎲 Current Turn</h2>
            <div class="player-info <?php echo 'active'; ?>">
                <div class="player-color" style="background: linear-gradient(45deg, <?php 
                    echo $currentPlayer === 0 ? '#ff4757, #ff6b81' : 
                        ($currentPlayer === 1 ? '#2196f3, #54a0ff' : 
                        ($currentPlayer === 2 ? '#2ed573, #7bed9f' : '#ffd32a, #ffda79')); 
                ?>)"></div>
                <?php echo $_SESSION['game']->getCurrentPlayer()['name']; ?>
            </div>
            
            <?php if ($_SESSION['game']->getLastRoll()): ?>
                <div class="dice-roll">🎲 <?php echo $_SESSION['game']->getLastRoll(); ?></div>
            <?php endif; ?>

            <p class="message <?php echo strpos($_SESSION['game']->getMessage(), 'wins') !== false ? 'winner-message' : ''; ?>">
                <?php echo $_SESSION['game']->getMessage(); ?>
            </p>

            <h3>👥 Players</h3>
            <?php foreach ($_SESSION['game']->getPlayers() as $index => $player): ?>
                <div class="player-info">
                    <div class="player-color" style="background: linear-gradient(45deg, <?php 
                        echo $index === 0 ? '#ff4757, #ff6b81' : 
                            ($index === 1 ? '#2196f3, #54a0ff' : 
                            ($index === 2 ? '#2ed573, #7bed9f' : '#ffd32a, #ffda79')); 
                    ?>)"></div>
                    <?php echo $player['name']; ?> - Position: <?php echo $player['position']; ?>
                </div>
            <?php endforeach; ?>

            <div class="controls">
                <form method="POST">
                    <?php if (!$_SESSION['game']->isGameOver()): ?>
                        <button type="submit" name="roll" class="btn">🎲 Roll Dice</button>
                    <?php endif; ?>
                    <button type="submit" name="new_game" class="btn">🔄 New Game</button>
                    <a href="pause_menu.php" class="btn">⏸️ Pause</a>
                </form>
            </div>
        </div>

        <div class="game-board">
            <?php
            $snakesAndLadders = $_SESSION['game']->getSnakesAndLadders();
            for ($i = 100; $i > 0; $i--) {
                $cellContent = $i;
                
                if (isset($snakesAndLadders[$i])) {
                    if ($snakesAndLadders[$i] > $i) {
                        $cellContent .= '<span class="ladder-indicator">⬆️</span>';
                    } else {
                        $cellContent .= '<span class="snake-indicator">⬇️</span>';
                    }
                }
                
                foreach ($_SESSION['game']->getPlayers() as $index => $player) {
                    if ($player['position'] === $i) {
                        $cellContent .= "<div class='player player-" . ($index + 1) . "'></div>";
                    }
                }
                
                $cellClass = 'cell';
                if (isset($snakesAndLadders[$i])) {
                    $cellClass .= $snakesAndLadders[$i] > $i ? ' ladder' : ' snake';
                }
                
                echo "<div class='$cellClass'>$cellContent</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>