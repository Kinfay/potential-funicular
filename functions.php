<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            exit('የደህንነት ማረጋገጫ አልተሳካም። ገጹን አድስና እንደገና ይሞክሩ።');
        }
    }
}
function require_admin(): void {
    if (empty($_SESSION['admin_id'])) {
        header('Location: admin_login.php');
        exit;
    }
}
function department_options(): array {
    return [
        'ትምህርት' => 'የትምህርትና ስብከት ክፍል',
        'መዝሙር' => 'የመዝሙርና ዜማ ክፍል',
        'ልማት' => 'የልማትና በጎ አድራጎት ክፍል',
        'ኪነ-ጥበብ' => 'የኪነ-ጥበብ ክፍል',
        'ሌላ' => 'ሌላ'
    ];
}
