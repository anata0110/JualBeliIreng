<?php
// =============================================
// FORM PEMBELIAN IRENG (1 FILE PHP)
// =============================================

// harga
$price_dl = 117000;

function e($v){ return htmlspecialchars(trim($v ?? '')); }

$errors = [];
$success = null;
$redirect = false; // trigger redirect

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $world  = e($_POST['world'] ?? '');
    $nama   = e($_POST['nama'] ?? '');
    $jenis  = e($_POST['jenis'] ?? 'ireng');
    $grow   = e($_POST['grow'] ?? '');
    $wa     = e($_POST['wa'] ?? '');
    $jumlah = (int) ($_POST['jumlah'] ?? 0);
    $metode = e($_POST['metode'] ?? '');

    if ($world == '')     $errors[] = "Masukkan World.";
    if ($nama == '')      $errors[] = "Masukkan Nama.";
    if ($grow == '')      $errors[] = "Masukkan Grow ID.";
    if (!preg_match('/^\d{8,15}$/', $wa)) $errors[] = "Nomor WhatsApp tidak valid.";
    if ($jumlah <= 0)     $errors[] = "Jumlah harus lebih dari 0.";
    if ($metode == '')    $errors[] = "Pilih metode pembayaran.";

    if (empty($errors)) {

        // redirect ke buktibeli.php
        $redirect = true;

        // siapkan data POST untuk halaman berikutnya
        $formData = [
            'world' => $world,
            'nama' => $nama,
            'jenis' => $jenis,
            'grow' => $grow,
            'wa' => $wa,
            'jumlah' => $jumlah,
            'metode' => $metode,
            'total' => $jumlah * $price_dl
        ];
    }
}
include "header.php"; 
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Form Pembelian Ireng</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    body { margin:0; background:#111; font-family: Inter; color:white; }
    .container { max-width:420px; margin:20px auto; padding:15px; }
    .card { background:#1d1d1f; padding:20px; border-radius:10px; box-shadow:0 6px 20px rgba(0,0,0,.6); }
    .label { font-size:12px; margin-top:10px; color:#bbb; }
    .input { width:100%; padding:10px; border-radius:6px; background:#0f0f10; border:1px solid #333; color:white; margin-top:4px; }
    .payments { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:10px; }
    .payments img { width:100%; height:55px; object-fit:contain; background:#000; padding:6px; border-radius:6px; cursor:pointer; opacity:0.6; transition:0.2s; }
    .selected-pay { opacity:1 !important; border:2px solid #00d084 !important; background:#022 !important; }
    .btn { width:100%; padding:12px; border:none; border-radius:8px; background:#00d084; margin-top:15px; font-weight:700; cursor:pointer; }
    .alert { padding:8px; border-radius:6px; margin-bottom:10px; }
    .alert.error { background:#3b1a1a; color:#ffdada; }
</style>
</head>

<body>

<?php if ($redirect): ?>
<form id="redirectForm" method="POST" action="buktibeli.php">
    <?php foreach ($formData as $key => $value): ?>
        <input type="hidden" name="<?= $key ?>" value="<?= $value ?>">
    <?php endforeach; ?>
</form>
<script>
    document.getElementById("redirectForm").submit();
</script>
<?php exit; ?>
<?php endif; ?>

<div class="container">
<div class="card">

<h1>Form Pembelian Ireng</h1>

<?php if (!empty($errors)): ?>
<div class="alert error"><?= implode("<br>", $errors) ?></div>
<?php endif; ?>

<form method="post" id="orderForm">

    <label class="label">World</label>
    <input class="input" name="world" value="<?= e($_POST['world'] ?? '') ?>">

    <label class="label">Nama Anda</label>
    <input class="input" name="nama" value="<?= e($_POST['nama'] ?? '') ?>">
<br>
<br>
    <label class="label">Jenis Pembelian</label>
    <input type="radio" name="jenis" value="ireng" checked> Ireng
<br>
<br>
    <label class="label">Grow ID</label>
    <input class="input" name="grow" value="<?= e($_POST['grow'] ?? '') ?>">

    <label class="label">Nomor WhatsApp (62xxxx)</label>
    <input class="input" name="wa" value="<?= e($_POST['wa'] ?? '') ?>">

    <label class="label">Jumlah Pembelian</label>
    <input type="number" class="input" id="jumlah" name="jumlah" min="1" value="<?= e($_POST['jumlah'] ?? '0') ?>">

    <label class="label">Total Harga</label>
    <div class="input" style="background:#0a0a0a;" id="totalBox">Rp 0</div>

    <label class="label">Pilih Metode Pembayaran</label>
    <input type="hidden" id="metode" name="metode">

    <div class="payments">
        <img src="logo gopay.png" id="gopay" onclick="pilih('Gopay','gopay')">
        <img src="logo dana.png" id="dana" onclick="pilih('Dana','dana')">
    </div>

    <button class="btn">BELI</button>
</form>

</div>
</div>

<script>
const price = <?= $price_dl ?>;

// update total
document.getElementById('jumlah').addEventListener('input', () => {
    let j = Number(document.getElementById('jumlah').value);
    if (!j) j = 0;
    document.getElementById('totalBox').textContent = "Rp " + (j * price).toLocaleString('id-ID');
});

// pilih metode pembayaran
function pilih(nama, id) {
    document.getElementById('metode').value = nama;
    document.querySelectorAll(".payments img").forEach(img => img.classList.remove("selected-pay"));
    document.getElementById(id).classList.add("selected-pay");
}
</script>

</body>
</html>
