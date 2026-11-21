<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/helpers.php';

$editData = [
    'id' => 0,
    'mahasiswa_id' => '',
    'mata_kuliah' => '',
    'semester' => 1,
    'nilai_tugas' => '',
    'nilai_uts' => '',
    'nilai_uas' => '',
];

$editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
if ($editId) {
    $statement = $conn->prepare('SELECT * FROM nilai_akademik WHERE id = ?');
    $statement->bind_param('i', $editId);
    $statement->execute();
    $data = $statement->get_result()->fetch_assoc();

    if ($data) {
        $editData = $data;
    } else {
        set_flash('warning', 'Data nilai yang ingin diedit tidak ditemukan.');
        redirect('nilai.php');
    }
}

$mahasiswa = $conn->query('SELECT id, nim, nama FROM mahasiswa ORDER BY nama')->fetch_all(MYSQLI_ASSOC);
$daftarNilai = $conn->query(
    'SELECT nilai_akademik.*, mahasiswa.nim, mahasiswa.nama
     FROM nilai_akademik
     JOIN mahasiswa ON mahasiswa.id = nilai_akademik.mahasiswa_id
     ORDER BY nilai_akademik.id DESC'
)->fetch_all(MYSQLI_ASSOC);

$flash = get_flash();
$pageTitle = 'Nilai Akademik';
$activePage = 'nilai';
require __DIR__ . '/includes/header.php';
?>

<div class="section-heading">
    <div>
        <span class="eyebrow">📊 Nilai Akademik</span>
        <h1>Kelola Nilai Akademik</h1>
    </div>
    <a class="btn btn-outline-secondary" href="index.php">← Kembali ke Beranda</a>
</div>

<?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
        <?= e($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card content-card">
            <div class="card-header">
                <h2 class="card-title h5"><?= $editData['id'] ? 'Edit Nilai' : 'Tambah Nilai' ?></h2>
            </div>
            <div class="card-body">
                <?php if (!$mahasiswa): ?>
                    <div class="alert alert-warning mb-0">
                        Tambahkan <a href="mahasiswa.php">data mahasiswa</a> terlebih dahulu sebelum mengisi nilai.
                    </div>
                <?php else: ?>
                    <form method="post" action="nilai_simpan.php">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $editData['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label" for="mahasiswa_id">Mahasiswa</label>
                            <select class="form-select" id="mahasiswa_id" name="mahasiswa_id" required>
                                <option value="">Pilih mahasiswa</option>
                                <?php foreach ($mahasiswa as $item): ?>
                                    <option value="<?= (int) $item['id'] ?>"
                                        <?= (int) $editData['mahasiswa_id'] === (int) $item['id'] ? 'selected' : '' ?>>
                                        <?= e($item['nim'] . ' — ' . $item['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row g-3">
                            <div class="col-sm-8 col-lg-12">
                                <label class="form-label" for="mata_kuliah">Mata Kuliah</label>
                                <input class="form-control" id="mata_kuliah" name="mata_kuliah" maxlength="100" required
                                       value="<?= e($editData['mata_kuliah']) ?>">
                            </div>
                            <div class="col-sm-4 col-lg-12">
                                <label class="form-label" for="semester">Semester</label>
                                <input class="form-control" id="semester" name="semester" type="number"
                                       min="1" max="14" required value="<?= (int) $editData['semester'] ?>">
                            </div>
                            <div class="col-4">
                                <label class="form-label" for="nilai_tugas">Tugas</label>
                                <input class="form-control" id="nilai_tugas" name="nilai_tugas" type="number"
                                       min="0" max="100" step="0.01" required value="<?= e($editData['nilai_tugas']) ?>">
                            </div>
                            <div class="col-4">
                                <label class="form-label" for="nilai_uts">UTS</label>
                                <input class="form-control" id="nilai_uts" name="nilai_uts" type="number"
                                       min="0" max="100" step="0.01" required value="<?= e($editData['nilai_uts']) ?>">
                            </div>
                            <div class="col-4">
                                <label class="form-label" for="nilai_uas">UAS</label>
                                <input class="form-control" id="nilai_uas" name="nilai_uas" type="number"
                                       min="0" max="100" step="0.01" required value="<?= e($editData['nilai_uas']) ?>">
                            </div>
                        </div>

                        <p class="small text-secondary mt-3 mb-4">Nilai akhir: 30% tugas + 30% UTS + 40% UAS.</p>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">
                                <?= $editData['id'] ? 'Simpan Perubahan' : 'Simpan Nilai' ?>
                            </button>
                            <?php if ($editData['id']): ?>
                                <a class="btn btn-light" href="nilai.php">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card content-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title h5">Daftar Nilai</h2>
                <span class="badge text-bg-light"><?= count($daftarNilai) ?> nilai</span>
            </div>
            <div class="card-body p-0 pt-3">
                <?php if (!$daftarNilai): ?>
                    <div class="empty-state"><span>📊</span>Belum ada nilai akademik.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                            <tr>
                                <th class="ps-4">Mahasiswa</th>
                                <th>Mata Kuliah</th>
                                <th>Tugas</th>
                                <th>UTS</th>
                                <th>UAS</th>
                                <th>Akhir</th>
                                <th>Huruf</th>
                                <th class="pe-4 text-end">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($daftarNilai as $item): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold"><?= e($item['nama']) ?></div>
                                        <small class="text-secondary"><?= e($item['nim']) ?></small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?= e($item['mata_kuliah']) ?></div>
                                        <small class="text-secondary">Semester <?= (int) $item['semester'] ?></small>
                                    </td>
                                    <td><?= number_format((float) $item['nilai_tugas'], 0) ?></td>
                                    <td><?= number_format((float) $item['nilai_uts'], 0) ?></td>
                                    <td><?= number_format((float) $item['nilai_uas'], 0) ?></td>
                                    <td class="fw-semibold"><?= number_format((float) $item['nilai_akhir'], 2) ?></td>
                                    <td><span class="grade-badge"><?= e($item['nilai_huruf']) ?></span></td>
                                    <td class="pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a class="btn btn-sm btn-outline-primary" href="nilai.php?edit=<?= (int) $item['id'] ?>">Edit</a>
                                            <form method="post" action="nilai_hapus.php"
                                                  onsubmit="return confirm('Hapus data nilai ini?')">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
