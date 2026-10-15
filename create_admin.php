<?php
// Run once locally, then DELETE this file from the server.
require __DIR__ . '/config.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if (!preg_match('/^[A-Za-z0-9_.-]{4,80}$/', $username)) {
        $message = 'Username must be 4-80 characters: letters, numbers, dot, underscore or dash.';
    } elseif (strlen($password) < 12) {
        $message = 'Use a password with at least 12 characters.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $conn->prepare('INSERT INTO admins (username, password_hash) VALUES (?, ?)');
            $stmt->bind_param('ss', $username, $hash);
            $stmt->execute();
            $message = 'Admin created. DELETE create_admin.php now.';
        } catch (mysqli_sql_exception $e) {
            $message = 'Could not create admin. The username may already exist.';
        }
    }
}
?>
<!doctype html><html lang="en"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Create admin</title><body style="font:16px Arial;max-width:480px;margin:50px auto;padding:20px"><h1>Create administrator</h1><p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p><form method="post"><p><label>Username<br><input name="username" minlength="4" maxlength="80" required></label></p><p><label>Password (12+ characters)<br><input type="password" name="password" minlength="12" required></label></p><button>Create admin</button></form></body></html>
