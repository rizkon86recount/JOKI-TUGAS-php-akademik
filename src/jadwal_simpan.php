<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('jadwal.php');
}

verify_csrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
$kodeMk = trim($_POST['kode_mk'] ?? '');
$mataKuliah = trim($_POST['mata_kuliah'] ?? '');
$hari = trim($_POST['hari'] ?? '');
$jamMulai = trim($_POST['jam_mulai'] ?? '');
$jamSelesai = trim($_POST['jam_selesai'] ?? '');
$ruang = trim($_POST['ruang'] ?? '');
$dosen = trim($_POST['dosen'] ?? '');
$alamatKembali = 'jadwal.php' . ($id ? '?edit=' . $id : '');

if (in_array('', [$kodeMk, $mataKuliah, $hari, $jamMulai, $jamSelesai, $ruang, $dosen], true)) {
    set_flash('danger', 'Seluruh data jadwal wajib diisi.');
    redirect($alamatKembali);
}

if (!in_array($hari, daftar_hari(), true)) {
    set_flash('danger', 'Hari kuliah tidak valid.');
    redirect($alamatKembali);
}

if ($jamMulai >= $jamSelesai) {
    set_flash('danger', 'Jam selesai harus lebih akhir dari jam mulai.');
    redirect($alamatKembali);
}

try {
    if ($id) {
        $statement = $conn->prepare(
            'UPDATE jadwal_kuliah SET kode_mk = ?, mata_kuliah = ?, hari = ?, jam_mulai = ?,
             jam_selesai = ?, ruang = ?, dosen = ? WHERE id = ?'
        );
        $statement->bind_param(
            'sssssssi',
            $kodeMk,
            $mataKuliah,
            $hari,
            $jamMulai,
            $jamSelesai,
            $ruang,
            $dosen,
            $id
        );
        $statement->execute();
        set_flash('success', 'Jadwal kuliah berhasil diperbarui.');
    } else {
        $statement = $conn->prepare(
            'INSERT INTO jadwal_kuliah
             (kode_mk, mata_kuliah, hari, jam_mulai, jam_selesai, ruang, dosen)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $statement->bind_param(
            'sssssss',
            $kodeMk,
            $mataKuliah,
            $hari,
            $jamMulai,
            $jamSelesai,
            $ruang,
            $dosen
        );
        $statement->execute();
        set_flash('success', 'Jadwal kuliah berhasil ditambahkan.');
    }
} catch (mysqli_sql_exception $exception) {
    set_flash('danger', 'Jadwal kuliah gagal disimpan.');
    redirect($alamatKembali);
}

redirect('jadwal.php');
