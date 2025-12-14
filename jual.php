<?php
$harga_per_dl = 115000;
include "header.php"; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Jual Ireng</title>

<style>
    body { margin: 0; background: #0f0f0f; font-family: Arial; color: white; }
    .container { width: 90%; max-width: 500px; margin: 30px auto; }
    .title { font-size: 26px; font-weight: bold; margin-bottom: 20px; text-align: center; }
    input { width: 100%; padding: 13px; font-size: 16px; border-radius: 8px; margin-bottom: 18px; background:#1b1b1b; border:1px solid #444; color:white; }
    .method-box { display:flex; gap:20px; justify-content:center; margin:20px 0; }
    .method-box img { width:90px; opacity:.5; cursor:pointer; border-radius:10px; border:2px solid transparent; transition:.3s; }
    .method-selected { opacity:1 !important; border:2px solid #2e81ff !important; transform:scale(1.05); }
    .btn { width:100%; padding:16px; background:#2e81ff; border:none; border-radius:10px; font-size:18px; color:white; cursor:pointer; }
</style>

<script>
function hitungHarga() {
    let jumlah = document.getElementById("jumlah_dl").value;
    let harga = <?= $harga_per_dl ?>;
    let total = jumlah * harga;
    document.getElementById("hasil").innerHTML = "Rp. " + total.toLocaleString();
}

function pilihMetode(nama, id) {
    document.getElementById("metode").value = nama;

    let imgs = document.querySelectorAll(".method-box img");
    imgs.forEach(img => img.classList.remove("method-selected"));

    document.getElementById(id).classList.add("method-selected");
}
</script>

</head>
<body>

<div class="container">

    <div class="title">Form Jual Ireng</div>

    <!-- FORM MENUJU BUKTI -->
    <form action="bukti.php" method="POST">

        <div class="subtitle">Jumlah Ireng</div>
        <input type="number" id="jumlah_dl" name="jumlah_dl" placeholder="0" onkeyup="hitungHarga()" required>

        <div class="subtitle">Nominal yang didapat</div>
        <div id="hasil">Rp. 0</div>

        <div class="subtitle">Nomor WhatsApp</div>
        <input type="number" name="whatsapp" placeholder="62xxxxxxxx" required>

        <div class="subtitle">Metode Pembayaran</div>
        <input type="hidden" id="metode" name="metode" required>

        <div class="method-box">
            <img src="logo gopay.png" id="gopay" onclick="pilihMetode('Gopay','gopay')">
            <img src="logo dana.png" id="dana" onclick="pilihMetode('Dana','dana')">
        </div>

        <div class="subtitle">Nama Anda</div>
        <input type="text" name="nama" required>

        <div class="subtitle">Nomor Rek / E-Wallet</div>
        <input type="text" name="rekening" required>

        <button type="submit" class="btn">Lanjut</button>

    </form>

</div>

</body>
</html>
