<?php
$Beli = 117000;
$Jual = 115000;
$store_name = "Clausz Store";

include "header.php"; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $item_name ?></title>

    <style>

        /* CARD UTAMA */
        .container {
            width: 90%;
            max-width: 900px;
            margin: 40px auto;
            background: #222;
            border-radius: 18px;
            padding: 25px;
            text-align: center;
            color: white;
            background-image: url('bg-game.png');
            background-size: cover;
            background-position: center;
        }

        .title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .item-image {
            width: 180px;
            margin: 20px auto;
            display: block;
        }

        /* BAGIAN JUAL BELI */
        .box-wrapper {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 20px;
        }

        .section {
            flex: 1;
            background: rgba(0,0,0,0.4);
            padding: 15px;
            border-radius: 12px;
        }

        .section h2 {
            font-size: 22px;
        }

        .price {
            font-size: 30px;
            font-weight: bold;
            margin: 10px 0 20px;
        }

        .btn {
            padding: 12px 35px;
            border: none;
            background: #2e81ff;
            color: white;
            border-radius: 10px;
            font-size: 18px;
            text-decoration: none;
        }

        /* RESPONSIVE HP */
        @media (max-width: 700px) {
            .title {
                font-size: 26px;
            }
            .item-image {
                width: 140px;
            }
            .box-wrapper {
                flex-direction: column;
            }
            .section {
                width: 100%;
            }
            .price {
                font-size: 26px;
            }
            .btn {
                width: 100%;
                display: block;
                padding: 12px 0;
                font-size: 17px;
            }
        }
    </style>

</head>
<body>

    <div class="container">
        <div class="title"><?= $store_name ?></div>
        <div class="box-wrapper">

            <div class="section">
                <h2>Take IRENG</h2>
                <div class="price">Rp. <?= number_format($Jual, 0, ",", ".") ?></div>
                <a href="jual.php" class="btn">Jual</a>
            </div>

            <div class="section">
                <h2>Price IRENG</h2>
                <div class="price">Rp. <?= number_format($Beli, 0, ",", ".") ?></div>
                <a href="beli.php" class="btn">Beli</a>
            </div>

        </div>

    </div>

</body>
</html>
