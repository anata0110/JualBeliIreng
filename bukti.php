<?php
// Ambil data dari jual.php
$jumlah_dl = $_POST['jumlah_dl'];
$whatsapp = $_POST['whatsapp'];
$metode = $_POST['metode'];
$nama = $_POST['nama'];
$rekening = $_POST['rekening'];
include "header.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti</title>

    <style>
        body {
            margin: 0;
            background: #0f0f0f;
            font-family: Arial;
            color: white;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
        }

        .btn {
            padding: 15px;
            width: 100%;
            background: #2e81ff;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            color: white;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>Upload Bukti DROP</h2>

        <form action="cekjual.php" method="POST" enctype="multipart/form-data">

            <!-- Kirim ulang semua data dari halaman sebelumnya -->
            <input type="hidden" name="jumlah_dl" value="<?= $jumlah_dl ?>">
            <input type="hidden" name="whatsapp" value="<?= $whatsapp ?>">
            <input type="hidden" name="metode" value="<?= $metode ?>">
            <input type="hidden" name="nama" value="<?= $nama ?>">
            <input type="hidden" name="rekening" value="<?= $rekening ?>">
            <br>    
            🌍 World: JAHARII
            👤 Owner: JAHARI
            <br>
            <br>
            note: Sertakan Nomor wa di Donation Box
            <br>
            <br>
            <div>Unggah Bukti (png/jpg/jpeg):</div>
            <input type="file" name="bukti" required>

            <button type="submit" class="btn">Kirim & Lanjut</button>

        </form>

    </div>

</body>

</html>