<?php
require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';

$success = '';
$error = '';
$fullname = $christian_name = $phone = $gender = $age = $department = $message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $fullname = trim((string)($_POST['fullname'] ?? ''));
    $christian_name = trim((string)($_POST['christian_name'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $gender = (string)($_POST['gender'] ?? '');
    $ageInput = trim((string)($_POST['age'] ?? ''));
    $department = (string)($_POST['department'] ?? '');
    $message = trim((string)($_POST['message'] ?? ''));

    if ($fullname === '' || mb_strlen($fullname) > 150) {
        $error = 'ሙሉ ስም ያስገቡ (ከ150 ቁምፊ አይበልጥ).';
    } elseif ($phone === '' || mb_strlen($phone) > 30) {
        $error = 'ትክክለኛ ስልክ ቁጥር ያስገቡ።';
    } elseif (!in_array($gender, ['ወንድ', 'ሴት'], true)) {
        $error = 'ጾታ ይምረጡ።';
    } elseif ($ageInput !== '' && (!ctype_digit($ageInput) || (int)$ageInput < 1 || (int)$ageInput > 120)) {
        $error = 'ዕድሜ ከ1 እስከ 120 መሆን አለበት።';
    } elseif (!array_key_exists($department, department_options())) {
        $error = 'የአገልግሎት ክፍል ይምረጡ።';
    } else {
        $ageValue = $ageInput === '' ? null : (int)$ageInput;
        $stmt = $conn->prepare('INSERT INTO members (fullname, christian_name, phone, gender, age, department, message) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssiss', $fullname, $christian_name, $phone, $gender, $ageValue, $department, $message);
        try {
            $stmt->execute();
            header('Location: index.php?registered=1');
            exit;
        } catch (mysqli_sql_exception $ex) {
            $error = 'መረጃውን ማስቀመጥ አልተቻለም። እባክዎ እንደገና ይሞክሩ።';
        }
    }
}
if (isset($_GET['registered'])) $success = 'መረጃዎ በትክክል ተመዝግቧል! እናመሰግናለን።';
$countResult = $conn->query('SELECT COUNT(*) AS total FROM members');
$total = (int)$countResult->fetch_assoc()['total'];
$departments = department_options();
?>
<!DOCTYPE html>
<html lang="am">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>የአባላት መመዝገቢያ | ፍኖተ ጽድቅ</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header"><div class="brand">✝</div><div><p class="eyebrow">ጉብሬ ደብረ ብርሃን ቅድስት ሥላሴ ቤተክርስቲያን</p><h1>ፍኖተ ጽድቅ ሰንበት ትምህርት ቤት</h1></div></header>
<section class="hero"><p class="eyebrow">እንኳን በደህና መጡ</p><h2>በአንድነት እናገልግል፣ በእምነት እናድግ</h2><p>የሰንበት ትምህርት ቤታችን አባል ለመሆን የሚከተለውን ፎርም ይሙሉ።</p></section>
<main class="container">
<div class="stats"><span class="stat-icon">👥</span><div><strong><?= number_format($total) ?></strong><span>አጠቃላይ የተመዘገቡ አባላት</span></div></div>
<section class="card"><div class="card-heading"><span class="section-tag">የአባልነት ምዝገባ</span><h2>የአባላት መመዝገቢያ ፎርም</h2><p>እባክዎ መረጃዎን በትክክል ያስገቡ።</p></div>
<?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<form method="post" action="index.php" class="member-form">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="form-grid">
<div class="form-group"><label for="fullname">ሙሉ ስም (ከነአያት) *</label><input id="fullname" name="fullname" maxlength="150" value="<?= e($fullname) ?>" placeholder="ሙሉ ስምዎን ያስገቡ" required></div>
<div class="form-group"><label for="christian_name">የክርስትና ስም</label><input id="christian_name" name="christian_name" maxlength="150" value="<?= e($christian_name) ?>" placeholder="የክርስትና ስምዎን ያስገቡ"></div>
<div class="form-group"><label for="phone">ስልክ ቁጥር *</label><input type="tel" id="phone" name="phone" maxlength="30" value="<?= e($phone) ?>" placeholder="09..." required></div>
<div class="form-group"><label for="gender">ጾታ *</label><select id="gender" name="gender" required><option value="">ይምረጡ</option><option value="ወንድ" <?= $gender==='ወንድ'?'selected':'' ?>>ወንድ</option><option value="ሴት" <?= $gender==='ሴት'?'selected':'' ?>>ሴት</option></select></div>
<div class="form-group"><label for="age">ዕድሜ</label><input type="number" id="age" name="age" min="1" max="120" value="<?= e($age) ?>" placeholder="ዕድሜዎን ያስገቡ"></div>
<div class="form-group"><label for="department">ማገልገል የሚፈልጉበት ክፍል *</label><select id="department" name="department" required><option value="">ክፍል ይምረጡ</option><?php foreach ($departments as $key=>$label): ?><option value="<?= e($key) ?>" <?= $department===$key?'selected':'' ?>><?= e($label) ?></option><?php endforeach; ?></select></div>
<div class="form-group full"><label for="message">ተጨማሪ አስተያየት / መልእክት</label><textarea id="message" name="message" rows="4" maxlength="2000" placeholder="መልእክትዎን እዚህ ይጻፉ..."><?= e($message) ?></textarea></div>
</div><button class="btn primary full-btn" type="submit">መረጃውን ላክ <span>→</span></button>
</form></section>
<p class="admin-link"><a href="admin_login.php">የአስተዳዳሪ መግቢያ</a></p>
</main><footer>© <?= date('Y') ?> ፍኖተ ጽድቅ ሰንበት ት/ቤት · ጉብሬ ደብረ ብርሃን ቅድስት ሥላሴ ቤተክርስቲያን</footer>
</body></html>
