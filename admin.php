<?php
require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';
require_admin();
$search = trim((string)($_GET['q'] ?? ''));
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;
if ($search !== '') {
    $like = '%' . $search . '%';
    $count = $conn->prepare('SELECT COUNT(*) AS total FROM members WHERE fullname LIKE ? OR christian_name LIKE ? OR phone LIKE ? OR department LIKE ?');
    $count->bind_param('ssss', $like, $like, $like, $like); $count->execute();
    $total = (int)$count->get_result()->fetch_assoc()['total'];
    $stmt = $conn->prepare('SELECT id, fullname, christian_name, phone, gender, age, department, created_at FROM members WHERE fullname LIKE ? OR christian_name LIKE ? OR phone LIKE ? OR department LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?');
    $stmt->bind_param('ssssii', $like, $like, $like, $like, $perPage, $offset); $stmt->execute();
    $members = $stmt->get_result();
} else {
    $total = (int)$conn->query('SELECT COUNT(*) AS total FROM members')->fetch_assoc()['total'];
    $stmt = $conn->prepare('SELECT id, fullname, christian_name, phone, gender, age, department, created_at FROM members ORDER BY id DESC LIMIT ? OFFSET ?');
    $stmt->bind_param('ii', $perPage, $offset); $stmt->execute(); $members = $stmt->get_result();
}
$pages = max(1, (int)ceil($total / $perPage));
$departments = department_options();
?>
<!DOCTYPE html><html lang="am"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>የአባላት አስተዳደር</title><link rel="stylesheet" href="assets/style.css"></head><body>
<header class="site-header"><div class="brand">✝</div><div><p class="eyebrow">የአስተዳዳሪ ክፍል</p><h1>የአባላት አስተዳደር</h1></div><a class="btn light" href="logout.php">ውጣ</a></header>
<main class="container wide"><div class="dashboard-top"><div><h2>ሰላም፣ <?= e($_SESSION['admin_username'] ?? 'አስተዳዳሪ') ?>!</h2><p>አጠቃላይ አባላት፦ <strong><?= number_format($total) ?></strong></p></div><div class="actions"><a class="btn primary" href="export.php">CSV አውርድ ↓</a><a class="btn secondary" href="index.php">የምዝገባ ገጽ ↗</a></div></div>
<section class="card"><form method="get" class="search-form"><input type="search" name="q" value="<?= e($search) ?>" placeholder="በስም፣ ስልክ ወይም ክፍል ፈልግ..."><button class="btn primary" type="submit">ፈልግ</button><?php if ($search !== ''): ?><a class="btn secondary" href="admin.php">አጽዳ</a><?php endif; ?></form>
<div class="table-wrap"><table><thead><tr><th>ተ.ቁ</th><th>ሙሉ ስም</th><th>የክርስትና ስም</th><th>ስልክ</th><th>ጾታ</th><th>ዕድሜ</th><th>ክፍል</th><th>የተመዘገበበት</th><th>ተግባር</th></tr></thead><tbody>
<?php if ($members->num_rows === 0): ?><tr><td colspan="9" class="empty">ምንም አባል አልተገኘም።</td></tr><?php endif; ?>
<?php while ($m = $members->fetch_assoc()): ?><tr><td><?= (int)$m['id'] ?></td><td><?= e($m['fullname']) ?></td><td><?= e($m['christian_name']) ?></td><td><?= e($m['phone']) ?></td><td><?= e($m['gender']) ?></td><td><?= $m['age'] === null ? '—' : (int)$m['age'] ?></td><td><?= e($departments[$m['department']] ?? $m['department']) ?></td><td><?= e($m['created_at'] ?? '') ?></td><td class="row-actions"><a href="edit_member.php?id=<?= (int)$m['id'] ?>">አስተካክል</a><form method="post" action="delete_member.php" onsubmit="return confirm('ይህን አባል መሰረዝ ይፈልጋሉ?');"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$m['id'] ?>"><button class="link-danger" type="submit">ሰርዝ</button></form></td></tr><?php endwhile; ?>
</tbody></table></div>
<nav class="pagination" aria-label="Pagination"><?php if ($page > 1): ?><a href="?q=<?= urlencode($search) ?>&page=<?= $page-1 ?>">← ቀዳሚ</a><?php endif; ?><span>ገጽ <?= $page ?> / <?= $pages ?></span><?php if ($page < $pages): ?><a href="?q=<?= urlencode($search) ?>&page=<?= $page+1 ?>">ቀጣይ →</a><?php endif; ?></nav>
</section></main><footer>© <?= date('Y') ?> ፍኖተ ጽድቅ</footer></body></html>
