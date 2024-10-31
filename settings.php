<?php
session_start();

// Default settings
$defaultSettings = [
    'sound' => true,
    'music' => true,
    'theme' => 'classic'
];

// Load or initialize settings
if (!isset($_SESSION['settings'])) {
    $_SESSION['settings'] = $defaultSettings;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['settings'] = [
        'sound' => isset($_POST['sound']),
        'music' => isset($_POST['music']),
        'theme' => $_POST['theme']
    ];
    // Optional: Add save confirmation message
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Settings - Snakes & Ladders</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Game Settings</h2>
        <form method="POST" class="settings-form">
            <div class="setting-item">
                <label>
                    <input type="checkbox" name="sound" 
                           <?php echo $_SESSION['settings']['sound'] ? 'checked' : ''; ?>>
                    Sound Effects
                </label>
            </div>
            
            <div class="setting-item">
                <label>
                    <input type="checkbox" name="music"
                           <?php echo $_SESSION['settings']['music'] ? 'checked' : ''; ?>>
                    Background Music
                </label>
            </div>
                        
            <div class="setting-item">
                <label>Board Theme:</label>
                <select name="theme" class="select-input">
                    <option value="classic" <?php echo $_SESSION['settings']['theme'] === 'classic' ? 'selected' : ''; ?>>Classic</option>
                    <option value="jungle" <?php echo $_SESSION['settings']['theme'] === 'jungle' ? 'selected' : ''; ?>>Jungle</option>
                    <option value="space" <?php echo $_SESSION['settings']['theme'] === 'space' ? 'selected' : ''; ?>>Space</option>
                </select>
            </div>
            
            <button type="submit" class="btn">Save Settings</button>
            <a href="index.php" class="btn back">Back</a>
        </form>
    </div>
</body>
</html>