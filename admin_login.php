<?php
require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';
if (!empty($_SESSION['admin_id'])) { header('Location: admin.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $stmt = $conn->prepare('SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();
    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: admin.php'); exit;
    }
    $error = 'የተጠቃሚ ስም ወይም የይለፍ ቃል ትክክል አይደለም።';
}
?>
<!DOCTYPE html><html lang="am"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>አስተዳዳሪ መግቢያ</title><link rel="stylesheet" href="assets/style.css"></head><body>
<header class="site-header"><div class="brand">✝</div><div><p class="eyebrow">የአስተዳዳሪ ክፍል</p><h1>ፍኖተ ጽድቅ ሰንበት ትምህርት ቤት</h1></div></header>
<main class="container narrow"><section class="card"><div class="card-heading"><h2>ወደ አስተዳዳሪ ይግቡ</h2><p>የአባላትን መረጃ ለማየት ይግቡ።</p></div>
<?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="member-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="form-group"><label for="username">የተጠቃሚ ስም</label><input id="username" name="username" autocomplete="username" required></div><div class="form-group"><label for="password">የይለፍ ቃል</label><input type="password" id="password" name="password" autocomplete="current-password" required></div><button class="btn primary full-btn" type="submit">ግባ</button></form><p class="admin-link"><a href="index.php">← ወደ ዋናው ገጽ ተመለስ</a></p></section></main><footer>© <?= date('Y') ?> ፍኖተ ጽድቅ</footer></body></html>
