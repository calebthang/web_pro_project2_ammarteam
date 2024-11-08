<?php
session_start();

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
        // Initialize players with proper IDs and positions
        for ($i = 0; $i < $playerCount; $i++) {
            $this->players[$i] = [
                'position' => 1,
                'name' => $playerNames[$i] ?? "Player " . ($i + 1),
                'id' => $i
            ];
        }
        
        $this->message = "Game started! " . $this->players[0]['name'] . "'s turn.";
        $this->setupBoard($difficulty);
    }
    
    public function rollDice() {
        if ($this->gameOver) return;
        
        $this->lastRoll = rand(1, 6);
        $currentPlayer = $this->players[$this->currentPlayer];
        $currentPos = $currentPlayer['position'];
        $newPos = $currentPos + $this->lastRoll;
        
        if ($newPos > 100) {
            $this->message = "{$currentPlayer['name']} rolled {$this->lastRoll}. Too high! Needs exactly " . (100 - $currentPos) . " to win!";
            $this->nextTurn();
            return;
        }
        
        // Update position in the players array
        $this->players[$this->currentPlayer]['position'] = $newPos;
        $this->message = "{$currentPlayer['name']} rolled {$this->lastRoll} and moved to {$newPos}";
        
        if (isset($this->snakesAndLadders[$newPos])) {
            $finalPos = $this->snakesAndLadders[$newPos];
            $this->players[$this->currentPlayer]['position'] = $finalPos;
            
            if ($finalPos > $newPos) {
                $this->message .= " and climbed a ladder to {$finalPos}!";
            } else {
                $this->message .= " and got bitten by a snake! Moved to {$finalPos}!";
            }
        }
        
        if ($this->players[$this->currentPlayer]['position'] === 100) {
            $this->gameOver = true;
            $this->message = "{$currentPlayer['name']} wins the game! 🎉";
            return;
        }
        
        $this->nextTurn();
    }
    
    private function nextTurn() {
        $this->currentPlayer = ($this->currentPlayer + 1) % count($this->players);
        if (!$this->gameOver) {
            $this->message .= "<br>{$this->players[$this->currentPlayer]['name']}'s turn.";
        }
    }

    // Getter methods
    public function getCurrentPlayer() {
        return $this->players[$this->currentPlayer];
    }
    
    public function getPlayers() {
        return $this->players;
    }
    
    public function getCurrentPlayerId() {
        return $this->currentPlayer;
    }
    
    public function getMessage() { return $this->message; }
    public function isGameOver() { return $this->gameOver; }
    public function getLastRoll() { return $this->lastRoll; }
    public function getSnakesAndLadders() { return $this->snakesAndLadders; }
    
    private function setupBoard($difficulty) {
        // Keep your existing setupBoard code here
    }
}

// Initialize new game or get existing game
if (!isset($_SESSION['game']) || isset($_POST['new_game'])) {
    $_SESSION['game'] = new SnakesAndLadders(
        $_SESSION['player_count'],
        $_SESSION['difficulty'],
        $_SESSION['player_names'] ?? []
    );
}

// Handle dice roll
if (isset($_POST['roll'])) {
    $_SESSION['game']->rollDice();
}

// Handle new game
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
            <?php 
            $currentPlayer = $_SESSION['game']->getCurrentPlayer();
            $playerColors = [
                0 => '#ff4757, #ff6b81',
                1 => '#2196f3, #54a0ff',
                2 => '#2ed573, #7bed9f',
                3 => '#ffd32a, #ffda79'
            ];
            ?>
            <div class="player-info active">
                <div class="player-color" style="background: linear-gradient(45deg, <?php echo $playerColors[$currentPlayer['id']]; ?>)"></div>
                <?php echo $currentPlayer['name']; ?>
            </div>
            
            <?php if ($_SESSION['game']->getLastRoll()): ?>
                <div class="dice-roll">🎲 <?php echo $_SESSION['game']->getLastRoll(); ?></div>
            <?php endif; ?>

            <p class="message <?php echo strpos($_SESSION['game']->getMessage(), 'wins') !== false ? 'winner-message' : ''; ?>">
                <?php echo $_SESSION['game']->getMessage(); ?>
            </p>

            <h3>👥 Players</h3>
            <?php foreach ($_SESSION['game']->getPlayers() as $player): ?>
                <div class="player-info <?php echo $player['id'] === $currentPlayer['id'] ? 'active' : ''; ?>">
                    <div class="player-color" style="background: linear-gradient(45deg, <?php echo $playerColors[$player['id']]; ?>)"></div>
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
                
                // Add player tokens
                foreach ($_SESSION['game']->getPlayers() as $player) {
                    if ($player['position'] === $i) {
                        $isCurrentPlayer = $player['id'] === $currentPlayer['id'];
                        $cellContent .= "<div class='player player-" . ($player['id'] + 1) . 
                                      ($isCurrentPlayer ? " current" : "") . "'></div>";
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