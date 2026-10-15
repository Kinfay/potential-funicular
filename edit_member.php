<?php
require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';
require_admin();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400); exit('የአባል መለያ ትክክል አይደለም።'); }
$stmt = $conn->prepare('SELECT * FROM members WHERE id = ?'); $stmt->bind_param('i', $id); $stmt->execute();
$member = $stmt->get_result()->fetch_assoc();
if (!$member) { http_response_code(404); exit('አባሉ አልተገኘም።'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $fullname = trim((string)($_POST['fullname'] ?? ''));
    $christian = trim((string)($_POST['christian_name'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $gender = (string)($_POST['gender'] ?? '');
    $ageInput = trim((string)($_POST['age'] ?? ''));
    $department = (string)($_POST['department'] ?? '');
    $message = trim((string)($_POST['message'] ?? ''));
    if ($fullname === '' || mb_strlen($fullname) > 150 || $phone === '' || mb_strlen($phone) > 30 || !in_array($gender, ['ወንድ','ሴት'], true) || !array_key_exists($department, department_options()) || ($ageInput !== '' && (!ctype_digit($ageInput) || (int)$ageInput < 1 || (int)$ageInput > 120))) {
        $error = 'እባክዎ የግዴታ መረጃዎችን በትክክል ያስገቡ።';
    } else {
        $age = $ageInput === '' ? null : (int)$ageInput;
        $update = $conn->prepare('UPDATE members SET fullname=?, christian_name=?, phone=?, gender=?, age=?, department=?, message=? WHERE id=?');
        $update->bind_param('ssssissi', $fullname, $christian, $phone, $gender, $age, $department, $message, $id);
        $update->execute();
        header('Location: admin.php?updated=1'); exit;
    }
    $member = array_merge($member, ['fullname'=>$fullname,'christian_name'=>$christian,'phone'=>$phone,'gender'=>$gender,'age'=>$ageInput,'department'=>$department,'message'=>$message]);
}
$departments = department_options();
?>
<!DOCTYPE html><html lang="am"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>የአባል መረጃ ማስተካከያ</title><link rel="stylesheet" href="assets/style.css"></head><body><header class="site-header"><div class="brand">✝</div><div><p class="eyebrow">የአስተዳዳሪ ክፍል</p><h1>የአባል መረጃ ማስተካከያ</h1></div></header><main class="container narrow"><section class="card"><div class="card-heading"><h2>መረጃውን ያስተካክሉ</h2></div><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="member-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="form-grid">
<div class="form-group"><label>ሙሉ ስም *</label><input name="fullname" maxlength="150" required value="<?= e($member['fullname']) ?>"></div><div class="form-group"><label>የክርስትና ስም</label><input name="christian_name" maxlength="150" value="<?= e($member['christian_name']) ?>"></div>
<div class="form-group"><label>ስልክ *</label><input name="phone" maxlength="30" required value="<?= e($member['phone']) ?>"></div><div class="form-group"><label>ጾታ *</label><select name="gender" required><option value="ወንድ" <?= $member['gender']==='ወንድ'?'selected':'' ?>>ወንድ</option><option value="ሴት" <?= $member['gender']==='ሴት'?'selected':'' ?>>ሴት</option></select></div>
<div class="form-group"><label>ዕድሜ</label><input type="number" name="age" min="1" max="120" value="<?= e((string)$member['age']) ?>"></div><div class="form-group"><label>የአገልግሎት ክፍል *</label><select name="department" required><?php foreach ($departments as $key=>$label): ?><option value="<?= e($key) ?>" <?= $member['department']===$key?'selected':'' ?>><?= e($label) ?></option><?php endforeach; ?></select></div>
<div class="form-group full"><label>ተጨማሪ መልእክት</label><textarea name="message" rows="4" maxlength="2000"><?= e($member['message']) ?></textarea></div></div><button class="btn primary" type="submit">ለውጡን አስቀምጥ</button> <a class="btn secondary" href="admin.php">ተመለስ</a></form></section></main><footer>© <?= date('Y') ?> ፍኖተ ጽድቅ</footer></body></html>
