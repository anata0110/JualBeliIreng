<?php
// jual_satu_file.php
session_start();

// --- Konfigurasi harga ---
$price_dl = 115000;   // harga per Diamond Lock (DL)
$price_bgl = 125000;  // contoh harga per BGL (jika diperlukan)

// helper
function e($v)
{
    return htmlspecialchars(trim($v ?? ''), ENT_QUOTES);
}
function rupiah($n)
{
    return 'Rp ' . number_format((int) $n, 0, ',', '.');
}

// Pastikan folder uploads ada
$uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0755, true);
}

// langkah (step): 1=form, 2=upload konfirmasi, 3=finish/cek
$step = $_POST['step'] ?? ($_GET['step'] ?? '1');
$errors = [];

// Step 1: terima data form -> validasi -> simpan di session -> redirect ke step 2
if ($step === '1' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $world = e($_POST['world'] ?? '');
    $nama = e($_POST['nama'] ?? '');
    $grow = e($_POST['grow'] ?? '');
    $wa = preg_replace('/\D+/', '', $_POST['wa'] ?? ''); // hanya digit
    $jenis = e($_POST['jenis'] ?? 'dl'); // dl/bgl/ireng dsb
    $jumlah = (int) ($_POST['jumlah'] ?? 0);
    $metode = e($_POST['metode'] ?? '');

    if ($world === '')
        $errors[] = 'Masukkan World.';
    if ($nama === '')
        $errors[] = 'Masukkan Nama.';
    if ($grow === '')
        $errors[] = 'Masukkan Grow ID.';
    if (!preg_match('/^\d{8,15}$/', $wa))
        $errors[] = 'Masukkan nomor WhatsApp yang valid (8-15 digit).';
    if ($jumlah <= 0)
        $errors[] = 'Jumlah pembelian harus lebih dari 0.';
    if ($metode === '')
        $errors[] = 'Pilih metode pembayaran dengan mengklik ikon.';

    if (empty($errors)) {
        // hitung total (anggap jenis 'dl' => price_dl, 'bgl' => price_bgl)
        if ($jenis === 'dl') {
            $total = $price_dl * $jumlah;
            $dl_equiv = $jumlah;
            $bgl_equiv = round($jumlah / 100, 2);
        } else {
            // jika bgl
            $total = $price_bgl * $jumlah;
            $bgl_equiv = $jumlah;
            $dl_equiv = $jumlah * 100;
        }

        // buat kode transaksi sederhana
        $kode = '#CWL' . time() . rand(100, 999);

        // simpan ke session
        $_SESSION['order'] = [
            'kode' => $kode,
            'world' => $world,
            'nama' => $nama,
            'grow' => $grow,
            'wa' => $wa,
            'jenis' => $jenis,
            'jumlah' => $jumlah,
            'dl_equiv' => $dl_equiv,
            'bgl_equiv' => $bgl_equiv,
            'metode' => $metode,
            'total' => $total,
        
        ];

        // pindah ke step 2
        header('Location: ?step=2');
        exit;
    }
}

// Step 2: terima upload bukti -> simpan file -> update session -> redirect step 3
if ($step === '2' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_bukti'])) {
    if (!isset($_SESSION['order'])) {
        $errors[] = 'Order tidak ditemukan. Silakan isi form kembali.';
    } else {
        if (!isset($_FILES['bukti']) || $_FILES['bukti']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Upload gagal. Pastikan file dipilih dan ukurannya <= 10MB.';
        } else {
            $file = $_FILES['bukti'];
            // validasi tipe & ukuran
            $allowed = ['image/png', 'image/jpeg', 'image/jpg'];
            if ($file['size'] > 10 * 1024 * 1024)
                $errors[] = 'File terlalu besar (maks 10MB).';
            if (!in_array(mime_content_type($file['tmp_name']), $allowed))
                $errors[] = 'Format file tidak diijinkan (png/jpg/jpeg).';

            if (empty($errors)) {
                // sanitasi nama file
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $safe = preg_replace('/[^a-z0-9_\-\.]/i', '_', pathinfo($file['name'], PATHINFO_FILENAME));
                $newName = $safe . '_' . time() . '.' . $ext;
                $dest = $uploadDir . DIRECTORY_SEPARATOR . $newName;

                if (!move_uploaded_file($file['tmp_name'], $dest)) {
                    $errors[] = 'Gagal memindahkan file. Periksa permission folder uploads/.';
                } else {
                    // simpan path relatif di session
                    $_SESSION['order']['bukti'] = 'uploads/' . $newName;
                    // redirect ke step 3 (detail)
                    header('Location: ?step=3');
                    exit;
                }
            }
        }
    }
}

// Step 3: final view (cek order) - hanya tampilkan data session
if ($step === '3' && !isset($_SESSION['order'])) {
    $errors[] = 'Order tidak ditemukan. Mulai dari form terlebih dahulu.';
}
include "header.php"; 
?><!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Jual / Beli Diamond Lock</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f0f0f;
            --card: #1a1a1a;
            --muted: #9aa0a6;
            --accent: #0a84ff
        }

        * {
            box-sizing: border-box;
            font-family: Inter, system-ui, Arial
        }

        body {
            margin: 0;
            background: var(--bg);
            color: #eee;
            padding: 18px
        }

        .wrap {
            max-width: 900px;
            margin: 0 auto
        }

        .card {
            background: var(--card);
            border-radius: 10px;
            padding: 18px;
            border: 1px solid #222
        }

        h1 {
            margin: 0 0 12px;
            font-size: 20px
        }

        .hint {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 12px
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            color: var(--muted)
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.04);
            background: #0f0f10;
            color: #eee
        }

        .price-box {
            background: #0b0b0b;
            padding: 10px;
            border-radius: 6px;
            margin-top: 6px
        }

        .payments {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-top: 8px
        }

        .payments button {
            background: transparent;
            border: 1px solid transparent;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer
        }

        .payments img {
            width: 100%;
            height: 48px;
            object-fit: contain;
            filter: grayscale(100%) opacity(.6)
        }

        .payments .active img {
            filter: none;
            opacity: 1;
            border: 2px solid var(--accent);
            transform: scale(1.02)
        }

        .btn {
            display: inline-block;
            padding: 12px 16px;
            border-radius: 8px;
            background: var(--accent);
            color: #fff;
            border: none;
            cursor: pointer;
            font-weight: 600
        }

        .btn.secondary {
            background: #444;
            color: #fff
        }

        .errors {
            background: #3b1a1a;
            padding: 10px;
            border-radius: 6px;
            color: #ffd6d6;
            margin-bottom: 12px
        }

        .success {
            background: #163a20;
            padding: 10px;
            border-radius: 6px;
            color: #b8ffd8;
            margin-bottom: 12px
        }

        .info-box {
            background: #2497e3;
            padding: 14px;
            border-radius: 8px;
            color: #fff;
            margin-bottom: 12px
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        tr.rowline td {
            padding: 12px 0;
            border-bottom: 1px solid #333
        }

        .status {
            display: inline-block;
            background: #ffb400;
            color: #000;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700
        }

        @media (max-width:640px) {
            .row {
                grid-template-columns: 1fr
            }

            .payments {
                grid-template-columns: repeat(3, 1fr)
            }
        }
    </style>
</head>

<body>
    <div class="wrap">

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php echo implode('<br>', $errors); ?>
            </div>
        <?php endif; ?>

        <?php if ($step === '1'): ?>
            <div class="card">
                <h1>Form Penjualan / Pembelian Diamond Lock</h1>
                <div class="hint">Isi form di bawah. Pilih metode pembayaran dengan mengklik ikon.</div>

                <form method="post" id="form1">
                    <input type="hidden" name="step" value="1">
                    <div class="row">
                        <div>
                            <label>World</label>
                            <input type="text" name="world" required value="<?php echo e($_POST['world'] ?? ''); ?>">
                        </div>
                        <div>
                            <label>Nama Anda</label>
                            <input type="text" name="nama" required value="<?php echo e($_POST['nama'] ?? ''); ?>">
                        </div>
                    </div>

                    <div style="height:12px"></div>

                    <div class="row">
                        <div>
                            <label>Grow ID</label>
                            <input type="text" name="grow" required value="<?php echo e($_POST['grow'] ?? ''); ?>">
                        </div>
                        <div>
                            <label>Nomor Whatsapp (format 62...)</label>
                            <input type="text" name="wa" required value="<?php echo e($_POST['wa'] ?? ''); ?>">
                        </div>
                    </div>

                    <div style="height:12px"></div>

                    <label>Jenis (DL/BGL/IRANG)</label>
                    <div style="display:flex;gap:8px;margin-bottom:12px">
                        <label style="font-size:14px"><input type="radio" name="jenis" value="dl" <?php if (($_POST['jenis'] ?? 'dl') === 'dl')
                            echo 'checked'; ?>> Diamond Lock (DL)</label>
                        <label style="font-size:14px"><input type="radio" name="jenis" value="bgl" <?php if (($_POST['jenis'] ?? '') === 'bgl')
                            echo 'checked'; ?>> BGL</label>
                    </div>

                    <div class="row">
                        <div>
                            <label>Jumlah</label>
                            <input type="number" min="1" name="jumlah" id="jumlah"
                                value="<?php echo e($_POST['jumlah'] ?? '1'); ?>">
                        </div>
                        <div>
                            <label>Total Harga</label>
                            <div class="price-box" id="totalBox"><?php echo rupiah(0); ?></div>
                        </div>
                    </div>

                    <div style="height:12px"></div>

                    <label>Pilih Metode Pembayaran</label>
                    <!-- tombol gambar sebagai pilihan -->
                    <div class="payments" id="payments">
                        <button type="button" data-name="Gopay" class="" onclick="selectMethod(this,'Gopay')">
                            <img src="logo_gopay.png" alt="Gopay">
                        </button>
                        <button type="button" data-name="Dana" onclick="selectMethod(this,'Dana')">
                            <img src="logo_dana.png" alt="Dana">
                        </button>
                        <button type="button" data-name="OVO" onclick="selectMethod(this,'OVO')">
                            <img src="logo_ovo.png" alt="OVO">
                        </button>
                        <button type="button" data-name="Bank" onclick="selectMethod(this,'Bank')">
                            <img src="logo_bank.png" alt="Bank">
                        </button>
                    </div>

                    <input type="hidden" name="metode" id="metode">

                    <div style="height:18px"></div>

                    <div style="display:flex;gap:8px">
                        <button class="btn" type="submit">Lanjutkan</button>
                        <a href="?step=1" class="btn secondary"
                            style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center">Reset</a>
                    </div>
                </form>
            </div>

            <script>
                // Harga dari server
                const priceDL = <?php echo json_encode($price_dl); ?>;
                const priceBGL = <?php echo json_encode($price_bgl); ?>;
                const jumlahEl = document.getElementById('jumlah');
                const totalBox = document.getElementById('totalBox');
                const form1 = document.getElementById('form1');

                function updateTotal() {
                    const jenis = form1.elements['jenis'].value;
                    const jumlah = Number(jumlahEl.value) || 0;
                    let total = 0;
                    if (jenis === 'dl') total = jumlah * priceDL;
                    else total = jumlah * priceBGL;
                    totalBox.textContent = 'Rp ' + total.toLocaleString('id-ID');
                }
                jumlahEl.addEventListener('input', updateTotal);
                // radio listener
                Array.from(form1.elements['jenis']).forEach(r => r.addEventListener('change', updateTotal));
                updateTotal();

                // pilih metode
                function selectMethod(btn, name) {
                    document.getElementById('metode').value = name;
                    // highlight active
                    Array.from(document.querySelectorAll('#payments button')).forEach(x => x.classList.remove('active'));
                    btn.classList.add('active');
                }
            </script>

        <?php elseif ($step === '2'):
            // tampilkan konfirmasi + form upload file (ambil data dari session)
            $ord = $_SESSION['order'] ?? null;
            if (!$ord) {
                echo '<div class="card"><div class="errors">Order tidak ditemukan. Mulai ulang.</div></div>';
            } else {
                ?>
                <div class="card">
                    <h1>Upload Bukti Pembayaran / Drop</h1>
                    <div class="info-box">
                        <b>World:</b> <?php echo e($ord['world']); ?> &nbsp; &nbsp;
                        <b>Nama:</b> <?php echo e($ord['nama']); ?> <br>
                    </div>

                    <table style="width:100%;margin-bottom:12px">
                        <tr class="rowline">
                            <td>Jenis Transaksi</td>
                            <td><?php echo ($ord['jenis'] === 'Ireng' ? 'Pembelian Diamond Lock' : 'Pembelian Ireng'); ?></td>
                        </tr>
                        <tr class="rowline">
                            <td>Jumlah</td>
                            <td><?php echo e($ord['jumlah']) . ' Ireng  '; ?></td>
                        </tr>
                        <tr class="rowline">
                            <td>Total Harga</td>
                            <td><b><?php echo rupiah($ord['total']); ?></b></td>
                        </tr>
                        <tr class="rowline">
                            <td>Tujuan Transfer</td>
                            <td><b><?php echo e($ord['metode']); ?></b> (089523864818)</td>
                        </tr>
                    </table>

                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="step" value="2">
                        <input type="hidden" name="upload_bukti" value="1">
                        <div class="hint">Ukuran maksimal: 10MB. Format: png, jpg, jpeg.</div>
                        <input type="file" name="bukti" accept="image/png,image/jpeg" required>
                        <div style="height:12px"></div>
                        <div style="display:flex;gap:8px">
                            <a href="beli.php" class="btn secondary"
                                style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center">Kembali</a>
                            <button class="btn" type="submit">Kirim & Lihat Pesanan</button>
                        </div>
                    </form>
                </div>
            <?php } ?>

        <?php elseif ($step === '3'):
            $ord = $_SESSION['order'] ?? null;
            if (!$ord) {
                echo '<div class="card"><div class="errors">Order tidak ditemukan.</div></div>';
            } else {
                ?>
                <div class="card">
                    <table>
                        <tr class="rowline">
                            <td>Jenis Transaksi</td>
                            <td><?php echo ($ord['jenis'] === 'ireng' ? 'Pembelian Ireng' : 'Pembelian Ireng'); ?></td>
                        </tr>
                        <tr class="rowline">
                            <td>World</td>
                            <td><?php echo e($ord['world']); ?></td>
                        </tr>
                        <tr class="rowline">
                            <td>Grow ID</td>
                            <td><?php echo e($ord['grow']); ?></td>
                        </tr>
                        <tr class="rowline">
                            <td>Jumlah Pembelian</td>
                            <td><?php echo e($ord['jumlah']) . ' Ireng  '; ?></td>
                        </tr>
                        <tr class="rowline">
                            <td>Total Harga</td>
                            <td><?php echo rupiah($ord['total']); ?></td>
                        </tr>
                        <tr class="rowline">
                            <td>Tujuan Transfer</td>
                            <td><b><?php echo e($ord['metode']); ?></b></td>
                        </tr>
                        <tr class="rowline">
                            <td>Bukti Pembayaran</td>
                            <td>
                                <?php if (!empty($ord['bukti']) && file_exists(__DIR__ . '/' . $ord['bukti'])): ?>
                                    <img src="<?php echo e($ord['bukti']); ?>" alt="bukti"
                                        style="max-width:320px;border-radius:6px;border:1px solid #333">
                                <?php else: ?>
                                    <span class="hint">Belum ada bukti ter-upload.</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="status">
                            <td>TerimaKasih</td>
                        </tr>
                    </table>

                    <div style="height:14px"></div>
                    <a href="beli.php" class="btn secondary"
                        style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center">Buat Pesanan
                        Baru</a>
                    <a href="index.php" class="btn"
                        style="margin-left:8px;text-decoration:none;display:inline-flex;align-items:center;justify-content:center">Beranda</a>

                </div>
                <?php
            }
        endif;

        // optional: clear session when user click save
        if (isset($_GET['clear']) && $_GET['clear'] == '1') {
            session_unset();
            session_destroy();
            exit;
        }
        ?>

    </div>
</body>

</html>