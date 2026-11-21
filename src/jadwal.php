<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/helpers.php';

$editData = [
    'id' => 0,
    'kode_mk' => '',
    'mata_kuliah' => '',
    'hari' => 'Senin',
    'jam_mulai' => '08:00',
    'jam_selesai' => '10:00',
    'ruang' => '',
    'dosen' => '',
];

$editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
if ($editId) {
    $statement = $conn->prepare('SELECT * FROM jadwal_kuliah WHERE id = ?');
    $statement->bind_param('i', $editId);
    $statement->execute();
    $data = $statement->get_result()->fetch_assoc();

    if ($data) {
        $editData = $data;
    } else {
        set_flash('warning', 'Jadwal yang ingin diedit tidak ditemukan.');
        redirect('jadwal.php');
    }
}

$jadwal = $conn->query(
    "SELECT * FROM jadwal_kuliah
     ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), jam_mulai"
)->fetch_all(MYSQLI_ASSOC);

$flash = get_flash();
$pageTitle = 'Jadwal Kuliah';
$activePage = 'jadwal';
require __DIR__ . '/includes/header.php';
?>

<div class="section-heading">
    <div>
        <span class="eyebrow">📅 Jadwal Kuliah</span>
        <h1>Kelola Jadwal Kuliah</h1>
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
                <h2 class="card-title h5"><?= $editData['id'] ? 'Edit Jadwal' : 'Tambah Jadwal' ?></h2>
            </div>
            <div class="card-body">
                <form method="post" action="jadwal_simpan.php">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int) $editData['id'] ?>">

                    <div class="row g-3">
                        <div class="col-sm-5 col-lg-12">
                            <label class="form-label" for="kode_mk">Kode Mata Kuliah</label>
                            <input class="form-control" id="kode_mk" name="kode_mk" maxlength="20" required
                                   value="<?= e($editData['kode_mk']) ?>">
                        </div>
                        <div class="col-sm-7 col-lg-12">
                            <label class="form-label" for="mata_kuliah">Mata Kuliah</label>
                            <input class="form-control" id="mata_kuliah" name="mata_kuliah" maxlength="100" required
                                   value="<?= e($editData['mata_kuliah']) ?>">
                        </div>
                        <div class="col-sm-4 col-lg-12">
                            <label class="form-label" for="hari">Hari</label>
                            <select class="form-select" id="hari" name="hari" required>
                                <?php foreach (daftar_hari() as $hari): ?>
                                    <option value="<?= e($hari) ?>" <?= $editData['hari'] === $hari ? 'selected' : '' ?>>
                                        <?= e($hari) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-6">
                            <label class="form-label" for="jam_mulai">Jam Mulai</label>
                            <input class="form-control" id="jam_mulai" name="jam_mulai" type="time" required
                                   value="<?= e(substr($editData['jam_mulai'], 0, 5)) ?>">
                        </div>
                        <div class="col-6 col-sm-4 col-lg-6">
                            <label class="form-label" for="jam_selesai">Jam Selesai</label>
                            <input class="form-control" id="jam_selesai" name="jam_selesai" type="time" required
                                   value="<?= e(substr($editData['jam_selesai'], 0, 5)) ?>">
                        </div>
                        <div class="col-sm-6 col-lg-12">
                            <label class="form-label" for="ruang">Ruang</label>
                            <input class="form-control" id="ruang" name="ruang" maxlength="50" required
                                   value="<?= e($editData['ruang']) ?>">
                        </div>
                        <div class="col-sm-6 col-lg-12">
                            <label class="form-label" for="dosen">Dosen Pengampu</label>
                            <input class="form-control" id="dosen" name="dosen" maxlength="100" required
                                   value="<?= e($editData['dosen']) ?>">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button class="btn btn-primary" type="submit">
                            <?= $editData['id'] ? 'Simpan Perubahan' : 'Tambah Jadwal' ?>
                        </button>
                        <?php if ($editData['id']): ?>
                            <a class="btn btn-light" href="jadwal.php">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card content-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title h5">Daftar Jadwal</h2>
                <span class="badge text-bg-light"><?= count($jadwal) ?> jadwal</span>
            </div>
            <div class="card-body p-0 pt-3">
                <?php if (!$jadwal): ?>
                    <div class="empty-state"><span>📅</span>Belum ada jadwal kuliah.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                            <tr>
                                <th class="ps-4">Hari / Jam</th>
                                <th>Mata Kuliah</th>
                                <th>Ruang</th>
                                <th>Dosen</th>
                                <th class="pe-4 text-end">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($jadwal as $item): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold"><?= e($item['hari']) ?></div>
                                        <small class="text-secondary">
                                            <?= e(substr($item['jam_mulai'], 0, 5)) ?>–<?= e(substr($item['jam_selesai'], 0, 5)) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?= e($item['mata_kuliah']) ?></div>
                                        <small class="text-secondary"><?= e($item['kode_mk']) ?></small>
                                    </td>
                                    <td><?= e($item['ruang']) ?></td>
                                    <td><?= e($item['dosen']) ?></td>
                                    <td class="pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a class="btn btn-sm btn-outline-primary" href="jadwal.php?edit=<?= (int) $item['id'] ?>">Edit</a>
                                            <form method="post" action="jadwal_hapus.php"
                                                  onsubmit="return confirm('Hapus jadwal ini?')">
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
