<?php
declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$dbName = 'gubre_db';

try {
    $conn = new mysqli($host, $dbUser, $dbPassword, $dbName);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    exit('የዳታቤዝ ግንኙነት አልተሳካም። config.php እና MySQL አገልግሎቱን ያረጋግጡ።');
}
