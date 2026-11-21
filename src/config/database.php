<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$dbHost = getenv('DB_HOST') ?: 'host.docker.internal';
$dbPort = (int) (getenv('DB_PORT') ?: 3306);
$dbName = getenv('DB_NAME') ?: 'akademik';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: 'root123';

try {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $exception) {
    http_response_code(500);
    exit(
        '<h1>Koneksi database gagal</h1>' .
        '<p>Pastikan layanan MySQL sudah berjalan dan konfigurasi database sudah benar.</p>'
    );
}
