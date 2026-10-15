<?php
require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: admin.php'); exit; }
verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id) {
    $stmt = $conn->prepare('DELETE FROM members WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
}
header('Location: admin.php?deleted=1');
exit;
