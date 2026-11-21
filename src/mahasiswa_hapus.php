<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('mahasiswa.php');
}

verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    set_flash('danger', 'Data mahasiswa tidak valid.');
    redirect('mahasiswa.php');
}

$statement = $conn->prepare('DELETE FROM mahasiswa WHERE id = ?');
$statement->bind_param('i', $id);
$statement->execute();

set_flash('success', 'Data mahasiswa berhasil dihapus.');
redirect('mahasiswa.php');
