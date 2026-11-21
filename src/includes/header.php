<?php

require_once __DIR__ . '/helpers.php';

$pageTitle = $pageTitle ?? 'Portal Akademik';
$activePage = $activePage ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> | Portal Akademik</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-dark container py-3">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <span class="brand-mark">🎓</span>
            <span>Portal Akademik</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainMenu">
            <div class="navbar-nav ms-auto gap-lg-2">
                <a class="nav-link <?= $activePage === 'mahasiswa' ? 'active' : '' ?>" href="mahasiswa.php">Mahasiswa</a>
                <a class="nav-link <?= $activePage === 'jadwal' ? 'active' : '' ?>" href="jadwal.php">Jadwal</a>
                <a class="nav-link <?= $activePage === 'nilai' ? 'active' : '' ?>" href="nilai.php">Nilai</a>
                <a class="nav-link <?= $activePage === 'kalkulator' ? 'active' : '' ?>" href="kalkulator.php">Kalkulator</a>
            </div>
        </div>
    </nav>
</header>

<main class="container py-4 py-lg-5">
