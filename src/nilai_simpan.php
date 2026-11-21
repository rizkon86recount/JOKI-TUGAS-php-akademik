<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('nilai.php');
}

verify_csrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
$mahasiswaId = filter_input(INPUT_POST, 'mahasiswa_id', FILTER_VALIDATE_INT);
$mataKuliah = trim($_POST['mata_kuliah'] ?? '');
$semester = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);
$nilaiTugas = filter_input(INPUT_POST, 'nilai_tugas', FILTER_VALIDATE_FLOAT);
$nilaiUts = filter_input(INPUT_POST, 'nilai_uts', FILTER_VALIDATE_FLOAT);
$nilaiUas = filter_input(INPUT_POST, 'nilai_uas', FILTER_VALIDATE_FLOAT);
$alamatKembali = 'nilai.php' . ($id ? '?edit=' . $id : '');

$nilaiValid = static function (float|false|null $nilai): bool {
    return $nilai !== false && $nilai !== null && $nilai >= 0 && $nilai <= 100;
};

if (!$mahasiswaId || $mataKuliah === '' || !$semester) {
    set_flash('danger', 'Mahasiswa, mata kuliah, dan semester wajib diisi.');
    redirect($alamatKembali);
}

if ($semester < 1 || $semester > 14) {
    set_flash('danger', 'Semester harus berada di antara 1 sampai 14.');
    redirect($alamatKembali);
}

if (!$nilaiValid($nilaiTugas) || !$nilaiValid($nilaiUts) || !$nilaiValid($nilaiUas)) {
    set_flash('danger', 'Nilai tugas, UTS, dan UAS harus berada di antara 0 sampai 100.');
    redirect($alamatKembali);
}

$nilaiAkhir = nilai_akhir($nilaiTugas, $nilaiUts, $nilaiUas);
$nilaiHuruf = nilai_huruf($nilaiAkhir);

try {
    if ($id) {
        $statement = $conn->prepare(
            'UPDATE nilai_akademik SET mahasiswa_id = ?, mata_kuliah = ?, semester = ?,
             nilai_tugas = ?, nilai_uts = ?, nilai_uas = ?, nilai_akhir = ?, nilai_huruf = ?
             WHERE id = ?'
        );
        $statement->bind_param(
            'isiddddsi',
            $mahasiswaId,
            $mataKuliah,
            $semester,
            $nilaiTugas,
            $nilaiUts,
            $nilaiUas,
            $nilaiAkhir,
            $nilaiHuruf,
            $id
        );
        $statement->execute();
        set_flash('success', 'Nilai akademik berhasil diperbarui.');
    } else {
        $statement = $conn->prepare(
            'INSERT INTO nilai_akademik
             (mahasiswa_id, mata_kuliah, semester, nilai_tugas, nilai_uts, nilai_uas, nilai_akhir, nilai_huruf)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $statement->bind_param(
            'isidddds',
            $mahasiswaId,
            $mataKuliah,
            $semester,
            $nilaiTugas,
            $nilaiUts,
            $nilaiUas,
            $nilaiAkhir,
            $nilaiHuruf
        );
        $statement->execute();
        set_flash('success', 'Nilai akademik berhasil ditambahkan.');
    }
} catch (mysqli_sql_exception $exception) {
    $pesan = $exception->getCode() === 1062
        ? 'Nilai mahasiswa untuk mata kuliah dan semester tersebut sudah ada.'
        : 'Nilai akademik gagal disimpan.';
    set_flash('danger', $pesan);
    redirect($alamatKembali);
}

redirect('nilai.php');
