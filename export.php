<?php
require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';
require_admin();
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="members-' . date('Y-m-d') . '.csv"');
header('X-Content-Type-Options: nosniff');
$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF");
fputcsv($out, ['ID','Full name','Christian name','Phone','Gender','Age','Department','Message','Registered at']);
$result = $conn->query('SELECT id, fullname, christian_name, phone, gender, age, department, message, created_at FROM members ORDER BY id DESC');
while ($row = $result->fetch_assoc()) {
    // Prefix formula-like values to reduce spreadsheet formula injection risk.
    foreach ($row as $key => $value) {
        if (is_string($value) && preg_match('/^[\=\+\-\@\t\r]/', $value)) $row[$key] = "'" . $value;
    }
    fputcsv($out, array_values($row));
}
fclose($out);
exit;
