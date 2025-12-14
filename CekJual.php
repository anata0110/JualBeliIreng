<?php
// CekJual.php — menerima POST dari form jual.php dan menangani upload serta menampilkan ringkasan

// konfigurasi
$uploadDir = __DIR__ . '/uploads/'; // pastikan path absolut
$maxSize   = 10 * 1024 * 1024;       // 10 MB
$allowedMime = ['image/png','image/jpeg','image/jpg'];

// buat folder uploads jika belum ada
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        die('Gagal membuat folder upload. Pastikan permission folder web server memungkinkan penulisan.');
    }
}

// ambil data POST (gunakan null-coalescing untuk mencegah notice)
$jumlah_dl = $_POST['jumlah_dl'] ?? '';
$whatsapp  = $_POST['whatsapp'] ?? '';
$metode    = $_POST['metode'] ?? '';
$nama      = $_POST['nama'] ?? '';
$rekening  = $_POST['rekening'] ?? '';

// Inisialisasi variabel upload
$uploadOk = false;
$namaFileBaru = '';
$uploadErrorMsg = '';

// cek apakah ada file di input bernama "bukti"
if (!empty($_FILES['bukti']) && $_FILES['bukti']['error'] !== UPLOAD_ERR_NO_FILE) {
    $f = $_FILES['bukti'];

    // cek error dasar
    if ($f['error'] !== UPLOAD_ERR_OK) {
        $uploadErrorMsg = "Upload error (kode {$f['error']}).";
    } elseif ($f['size'] > $maxSize) {
        $uploadErrorMsg = "File terlalu besar. Maksimal 10 MB.";
    } else {
        // validasi mime type dengan finfo (lebih aman daripada ekstensi)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($f['tmp_name']);
        if (!in_array($mime, $allowedMime)) {
            $uploadErrorMsg = "Tipe file tidak diperbolehkan. Hanya PNG/JPEG.";
        } else {
            // buat nama file unik untuk menghindari overwrite dan path traversal
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $namaFileBaru = uniqid('bukti_', true) . '.' . strtolower($ext);
            $target = $uploadDir . $namaFileBaru;

            // pindahkan file
            if (!move_uploaded_file($f['tmp_name'], $target)) {
                $uploadErrorMsg = "Gagal memindahkan file ke folder uploads. Periksa permission.";
            } else {
                $uploadOk = true;
            }
        }
    }
} else {
    $uploadErrorMsg = "Tidak ada file bukti yang diunggah.";
}

// fungsi helper untuk men-escape output
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
include "header.php"; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Cek Jual</title>
<style>
    body { background:#0f0f0f; color:#fff; font-family:Arial,Helvetica,sans-serif; margin:0; padding:0; }
    .container { width:92%; max-width:900px; margin:30px auto; }
    .box { background:#1a1a1a; border:1px solid #222; padding:20px; border-radius:8px; }
    .row { display:grid; grid-template-columns:220px 1fr; gap:10px; padding:12px 0; border-bottom:1px solid #2b2b2b; }
    .row:last-child { border-bottom:none; }
    .left { color:#bdbdbd; }
    .right { color:#fff; }
    .status { display:inline-block; background:#ffb400; padding:10px 18px; color:#000; border-radius:8px; font-weight:bold; }
    img.preview { max-width:300px; height:auto; border-radius:6px; border:1px solid #333; display:block; margin-top:8px; }
    .err { background:#3b1a1a; color:#ffb3b3; padding:12px; border-radius:6px; margin-bottom:12px; }
    @media (max-width:600px) {
        .row { grid-template-columns: 1fr; }
    }
</style>
</head>
<body>
<div class="container">
    <h1>Detail Transaksi</h1>

    <?php if (!$uploadOk): ?>
        <div class="err">
            <strong>Perhatian:</strong><br>
            <?= e($uploadErrorMsg) ?>
        </div>
    <?php endif; ?>

    <div class="box">
        <div class="row"><div class="left">Jumlah DL</div><div class="right"><?= e($jumlah_dl) ?: '-' ?></div></div>
        <div class="row"><div class="left">WhatsApp</div><div class="right"><?= e($whatsapp) ?: '-' ?></div></div>
        <div class="row"><div class="left">Metode</div><div class="right"><?= e($metode) ?: '-' ?></div></div>
        <div class="row"><div class="left">Nama</div><div class="right"><?= e($nama) ?: '-' ?></div></div>
        <div class="row"><div class="left">Rekening / E-Wallet</div><div class="right"><?= e($rekening) ?: '-' ?></div></div>

        <div class="row">
            <div class="left">Bukti Upload</div>
            <div class="right">
                <?php if ($uploadOk): ?>
                    <!-- gunakan path relatif ketika menampilkan di browser -->
                    <img class="preview" src="<?= 'uploads/' . e($namaFileBaru) ?>" alt="Bukti Upload">
                    <div style="margin-top:8px;color:#bdbdbd; font-size:13px;"><?= e($namaFileBaru) ?></div>
                <?php else: ?>
                    <div style="color:#bdbdbd;">Tidak ada bukti yang valid diupload.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="right"><span class="status">TerimaKasih</span></div>
        </div>
    </div>

    
</div>
</body>
</html>
