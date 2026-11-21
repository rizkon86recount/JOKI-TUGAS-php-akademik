<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('jadwal.php');
}

verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    set_flash('danger', 'Data jadwal tidak valid.');
    redirect('jadwal.php');
}

$statement = $conn->prepare('DELETE FROM jadwal_kuliah WHERE id = ?');
$statement->bind_param('i', $id);
$statement->execute();

set_flash('success', 'Jadwal kuliah berhasil dihapus.');
redirect('jadwal.php');
