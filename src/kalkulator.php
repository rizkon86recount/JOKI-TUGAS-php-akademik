<?php

require_once __DIR__ . '/includes/helpers.php';

$angkaPertama = '';
$angkaKedua = '';
$operator = '+';
$hasil = null;
$pesanError = '';
$simbolOperator = [
    '+' => '+',
    '-' => '−',
    '*' => '×',
    '/' => '÷',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $angkaPertama = trim($_POST['angka_pertama'] ?? '');
    $angkaKedua = trim($_POST['angka_kedua'] ?? '');
    $operator = $_POST['operator'] ?? '+';

    $nilaiPertama = filter_var($angkaPertama, FILTER_VALIDATE_FLOAT);
    $nilaiKedua = filter_var($angkaKedua, FILTER_VALIDATE_FLOAT);

    if ($nilaiPertama === false || $nilaiKedua === false) {
        $pesanError = 'Masukkan dua angka yang valid.';
    } elseif (!array_key_exists($operator, $simbolOperator)) {
        $pesanError = 'Operator perhitungan tidak valid.';
    } elseif ($operator === '/' && (float) $nilaiKedua === 0.0) {
        $pesanError = 'Angka tidak dapat dibagi dengan nol.';
    } else {
        $hasil = match ($operator) {
            '+' => $nilaiPertama + $nilaiKedua,
            '-' => $nilaiPertama - $nilaiKedua,
            '*' => $nilaiPertama * $nilaiKedua,
            '/' => $nilaiPertama / $nilaiKedua,
        };
    }
}

function format_angka(float|int $angka): string
{
    return rtrim(rtrim(number_format($angka, 10, '.', ''), '0'), '.');
}

$pageTitle = 'Kalkulator';
$activePage = 'kalkulator';
require __DIR__ . '/includes/header.php';
?>

<div class="section-heading">
    <div>
        <span class="eyebrow">🔢 Kalkulator</span>
        <h1>Kalkulator Sederhana</h1>
    </div>
    <a class="btn btn-outline-secondary" href="index.php">← Kembali ke Beranda</a>
</div>

<div class="row justify-content-center g-4">
    <div class="col-lg-7">
        <div class="card content-card">
            <div class="card-header">
                <h2 class="card-title h5">Perhitungan Aritmetika</h2>
            </div>
            <div class="card-body">
                <?php if ($pesanError): ?>
                    <div class="alert alert-danger"><?= e($pesanError) ?></div>
                <?php endif; ?>

                <form method="post">
                    <?= csrf_field() ?>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label" for="angka_pertama">Angka Pertama</label>
                            <input class="form-control form-control-lg" id="angka_pertama" name="angka_pertama"
                                   type="number" step="any" required value="<?= e($angkaPertama) ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="operator">Operator</label>
                            <select class="form-select form-select-lg text-center" id="operator" name="operator">
                                <?php foreach ($simbolOperator as $nilai => $simbol): ?>
                                    <option value="<?= e($nilai) ?>" <?= $operator === $nilai ? 'selected' : '' ?>>
                                        <?= e($simbol) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label" for="angka_kedua">Angka Kedua</label>
                            <input class="form-control form-control-lg" id="angka_kedua" name="angka_kedua"
                                   type="number" step="any" required value="<?= e($angkaKedua) ?>">
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 mt-4 py-3" type="submit">Hitung Hasil</button>
                </form>

                <?php if ($hasil !== null): ?>
                    <div class="calculator-result mt-4">
                        <div>
                            <small>
                                <?= e($angkaPertama) ?>
                                <?= e($simbolOperator[$operator]) ?>
                                <?= e($angkaKedua) ?>
                            </small>
                            <strong><?= e(format_angka($hasil)) ?></strong>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
