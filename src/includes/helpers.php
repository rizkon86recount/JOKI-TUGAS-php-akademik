<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $location): never
{
    header('Location: ' . $location);
    exit;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return $flash;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals(csrf_token(), $token)) {
        http_response_code(419);
        exit('Sesi formulir tidak valid. Silakan muat ulang halaman.');
    }
}

function nilai_akhir(float $tugas, float $uts, float $uas): float
{
    return round(($tugas * 0.30) + ($uts * 0.30) + ($uas * 0.40), 2);
}

function nilai_huruf(float $nilai): string
{
    return match (true) {
        $nilai >= 85 => 'A',
        $nilai >= 80 => 'A-',
        $nilai >= 75 => 'B+',
        $nilai >= 70 => 'B',
        $nilai >= 65 => 'C+',
        $nilai >= 60 => 'C',
        $nilai >= 50 => 'D',
        default => 'E',
    };
}

function daftar_hari(): array
{
    return ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
}
