<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/helpers.php';

$jumlahMahasiswa = (int) $conn->query('SELECT COUNT(*) AS total FROM mahasiswa')->fetch_assoc()['total'];
$jumlahJadwal = (int) $conn->query('SELECT COUNT(*) AS total FROM jadwal_kuliah')->fetch_assoc()['total'];
$jumlahNilai = (int) $conn->query('SELECT COUNT(*) AS total FROM nilai_akademik')->fetch_assoc()['total'];

$pageTitle = 'Beranda';
require __DIR__ . '/includes/header.php';
?>

<section class="hero-panel mb-4 mb-lg-5">
    <div>
        <span class="eyebrow">Sistem Informasi Akademik</span>
        <h1>Kelola kebutuhan akademik dalam satu tempat.</h1>
        <p>Pilih menu untuk mengelola mahasiswa, jadwal kuliah, nilai akademik, atau melakukan perhitungan sederhana.</p>
    </div>
    <div class="hero-decoration" aria-hidden="true">📚</div>
</section>

<section aria-labelledby="menu-title">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Menu Utama</span>
            <h2 id="menu-title">Layanan Akademik</h2>
        </div>
    </div>

    <div class="feature-menu">
        <a class="feature-card" href="mahasiswa.php">
            <span class="feature-icon">👤</span>
            <span class="feature-copy">
                <strong>Data Mahasiswa</strong>
                <small>Tambah, ubah, dan lihat data mahasiswa</small>
            </span>
            <span class="feature-stat"><?= $jumlahMahasiswa ?> data</span>
            <span class="feature-arrow">›</span>
        </a>

        <a class="feature-card" href="jadwal.php">
            <span class="feature-icon">📅</span>
            <span class="feature-copy">
                <strong>Jadwal Kuliah</strong>
                <small>Atur mata kuliah, waktu, dan ruang kelas</small>
            </span>
            <span class="feature-stat"><?= $jumlahJadwal ?> jadwal</span>
            <span class="feature-arrow">›</span>
        </a>

        <a class="feature-card" href="nilai.php">
            <span class="feature-icon">📊</span>
            <span class="feature-copy">
                <strong>Nilai Akademik</strong>
                <small>Catat nilai tugas, UTS, UAS, dan nilai akhir</small>
            </span>
            <span class="feature-stat"><?= $jumlahNilai ?> nilai</span>
            <span class="feature-arrow">›</span>
        </a>

        <a class="feature-card" href="kalkulator.php">
            <span class="feature-icon">🔢</span>
            <span class="feature-copy">
                <strong>Kalkulator</strong>
                <small>Lakukan perhitungan aritmetika dasar</small>
            </span>
            <span class="feature-arrow">›</span>
        </a>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
