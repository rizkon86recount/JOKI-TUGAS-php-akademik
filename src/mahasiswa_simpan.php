<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('mahasiswa.php');
}

verify_csrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
$nim = trim($_POST['nim'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$programStudi = trim($_POST['program_studi'] ?? '');
$semester = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);
$email = trim($_POST['email'] ?? '');
$alamatKembali = 'mahasiswa.php' . ($id ? '?edit=' . $id : '');

if ($nim === '' || $nama === '' || $programStudi === '' || !$semester) {
    set_flash('danger', 'NIM, nama, program studi, dan semester wajib diisi.');
    redirect($alamatKembali);
}

if ($semester < 1 || $semester > 14) {
    set_flash('danger', 'Semester harus berada di antara 1 sampai 14.');
    redirect($alamatKembali);
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('danger', 'Format email tidak valid.');
    redirect($alamatKembali);
}

try {
    if ($id) {
        $statement = $conn->prepare(
            'UPDATE mahasiswa SET nim = ?, nama = ?, program_studi = ?, semester = ?, email = ? WHERE id = ?'
        );
        $statement->bind_param('sssisi', $nim, $nama, $programStudi, $semester, $email, $id);
        $statement->execute();
        set_flash('success', 'Data mahasiswa berhasil diperbarui.');
    } else {
        $statement = $conn->prepare(
            'INSERT INTO mahasiswa (nim, nama, program_studi, semester, email) VALUES (?, ?, ?, ?, ?)'
        );
        $statement->bind_param('sssis', $nim, $nama, $programStudi, $semester, $email);
        $statement->execute();
        set_flash('success', 'Mahasiswa baru berhasil ditambahkan.');
    }
} catch (mysqli_sql_exception $exception) {
    $pesan = $exception->getCode() === 1062
        ? 'NIM tersebut sudah digunakan.'
        : 'Data mahasiswa gagal disimpan.';
    set_flash('danger', $pesan);
    redirect($alamatKembali);
}

redirect('mahasiswa.php');
