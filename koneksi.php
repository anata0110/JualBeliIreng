<?php
// Contoh data (nanti bisa kamu ganti dari database)
$take = 320;
$price = 370;
$item_name = "Diamond Lock";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topup Store</title>

    <style>
        body {
            margin: 0;
            background: #111;
            font-family: Arial, sans-serif;
        }

        /* Navbar */
        .navbar {
            width: 100%;
            background: #0f0f0f;
            padding: 18px 40px;
            display: flex;
            justify-content: flex-end;
            gap: 35px;
            font-size: 19px;
            border-bottom: 1px solid #333;
        }

        .navbar a {
            color: #ddd;
            text-decoration: none;
        }

        .navbar .cek {
            padding: 7px 15px;
            border: 2px solid #ff4444;
            border-radius: 10px;
            color: #fff;
        }

        /* Card Item */
        .container {
            width: 80%;
            margin: 60px auto;
            background: #222;
            border-radius: 20px;
            padding: 35px;
            text-align: center;
            color: white;
            background-image: url('bg-game.png');
            background-size: cover;
            background-position: center;
        }

        .title {
            font-size: 38px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .item-image {
            width: 230px;
            margin: 20px auto;
            display: block;
        }

        .section {
            width: 50%;
            float: left;
            padding: 20px 0;
        }

        .section h2 {
            margin: 5px;
            font-size: 28px;
        }

        .price {
            font-size: 40px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .btn {
            padding: 12px 40px;
            border: none;
            background: #2e81ff;
            color: white;
            border-radius: 12px;
            cursor: pointer;
            font-size: 20px;
        }

        .btn-jual {
            background: #444;
        }

        footer {
            clear: both;
            padding: 20px;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <a href="index.php">Beranda</a>
        <a href="Jual.php">Jual</a>
        <a href="Beli.php">Beli</a>
        <a href="cek.php" class="cek">Cek Pesanan</a>
    </div>

    <!-- CONTENT -->
    <div class="container">
        <div class="title"><?= $item_name ?></div>

        <img src="dl.png" class="item-image">

        <div class="section">
            <h2>Take</h2>
            <div class="price">Rp. <?= number_format($take,0,",",".") ?></div>
            <button class="btn btn-jual">Jual</button>
        </div>

        <div class="section">
            <h2>Price</h2>
            <div class="price">Rp. <?= number_format($price,0,",",".") ?></div>
            <button class="btn">Beli</button>
        </div>

        <footer></footer>
    </div>

</body>
</html>
