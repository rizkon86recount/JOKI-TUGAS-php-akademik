<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('nilai.php');
}

verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    set_flash('danger', 'Data nilai tidak valid.');
    redirect('nilai.php');
}

$statement = $conn->prepare('DELETE FROM nilai_akademik WHERE id = ?');
$statement->bind_param('i', $id);
$statement->execute();

set_flash('success', 'Nilai akademik berhasil dihapus.');
redirect('nilai.php');
