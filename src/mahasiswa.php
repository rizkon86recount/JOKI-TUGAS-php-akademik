<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/helpers.php';

$editData = [
    'id' => 0,
    'nim' => '',
    'nama' => '',
    'program_studi' => '',
    'semester' => 1,
    'email' => '',
];

$editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
if ($editId) {
    $statement = $conn->prepare('SELECT * FROM mahasiswa WHERE id = ?');
    $statement->bind_param('i', $editId);
    $statement->execute();
    $data = $statement->get_result()->fetch_assoc();

    if ($data) {
        $editData = $data;
    } else {
        set_flash('warning', 'Data mahasiswa yang ingin diedit tidak ditemukan.');
        redirect('mahasiswa.php');
    }
}

$mahasiswa = $conn->query('SELECT * FROM mahasiswa ORDER BY id DESC')->fetch_all(MYSQLI_ASSOC);
$flash = get_flash();
$pageTitle = 'Data Mahasiswa';
$activePage = 'mahasiswa';
require __DIR__ . '/includes/header.php';
?>

<div class="section-heading">
    <div>
        <span class="eyebrow">👤 Data Mahasiswa</span>
        <h1>Kelola Mahasiswa</h1>
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
                <h2 class="card-title h5"><?= $editData['id'] ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' ?></h2>
            </div>
            <div class="card-body">
                <form method="post" action="mahasiswa_simpan.php">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int) $editData['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label" for="nim">NIM</label>
                        <input class="form-control" id="nim" name="nim" maxlength="20" required
                               value="<?= e($editData['nim']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="nama">Nama Lengkap</label>
                        <input class="form-control" id="nama" name="nama" maxlength="100" required
                               value="<?= e($editData['nama']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="program_studi">Program Studi</label>
                        <input class="form-control" id="program_studi" name="program_studi" maxlength="100" required
                               value="<?= e($editData['program_studi']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="semester">Semester</label>
                        <input class="form-control" id="semester" name="semester" type="number"
                               min="1" max="14" required value="<?= (int) $editData['semester'] ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="email">Email <span class="text-secondary fw-normal">(opsional)</span></label>
                        <input class="form-control" id="email" name="email" type="email" maxlength="100"
                               value="<?= e($editData['email']) ?>">
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" type="submit">
                            <?= $editData['id'] ? 'Simpan Perubahan' : 'Tambah Data' ?>
                        </button>
                        <?php if ($editData['id']): ?>
                            <a class="btn btn-light" href="mahasiswa.php">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card content-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title h5">Daftar Mahasiswa</h2>
                <span class="badge text-bg-light"><?= count($mahasiswa) ?> mahasiswa</span>
            </div>
            <div class="card-body p-0 pt-3">
                <?php if (!$mahasiswa): ?>
                    <div class="empty-state"><span>👤</span>Belum ada data mahasiswa.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                            <tr>
                                <th class="ps-4">NIM</th>
                                <th>Mahasiswa</th>
                                <th>Program Studi</th>
                                <th>Semester</th>
                                <th class="pe-4 text-end">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($mahasiswa as $item): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold"><?= e($item['nim']) ?></td>
                                    <td>
                                        <div class="fw-semibold"><?= e($item['nama']) ?></div>
                                        <small class="text-secondary"><?= e($item['email'] ?: '-') ?></small>
                                    </td>
                                    <td><?= e($item['program_studi']) ?></td>
                                    <td><?= (int) $item['semester'] ?></td>
                                    <td class="pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a class="btn btn-sm btn-outline-primary" href="mahasiswa.php?edit=<?= (int) $item['id'] ?>">Edit</a>
                                            <form method="post" action="mahasiswa_hapus.php"
                                                  onsubmit="return confirm('Hapus mahasiswa ini beserta seluruh nilainya?')">
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
