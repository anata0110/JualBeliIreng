<!-- header.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topup Growtopia</title>

    <style>
        body {
            background: #111;
            margin: 0;
            font-family: Arial, sans-serif;
            color: white;
        }

        /* NAVBAR */
        .navbar {
            width: 100%;
            background: #0f0f0f;
            padding: 15px 25px;
            border-bottom: 1px solid #333;
        }

        .nav-container {
            display: flex;
            justify-content: center;
        }

        .nav-menu {
            display: flex;
            gap: 25px;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
        }

        .nav-menu a {
            color: #ddd;
            text-decoration: none;
            font-size: 17px;
        }

        .btn-cek {
            padding: 6px 12px;
            border-radius: 8px;
            color: #fff !important;
        }

        /* RESPONSIVE NAVBAR */
        @media (max-width: 600px) {
            .nav-menu {
                gap: 15px;
            }
            .nav-menu a {
                font-size: 15px;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">
    <div class="nav-container">
        <ul class="nav-menu">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="jual.php">Jual</a></li>
            <li><a href="beli.php">Beli</a></li>
        </ul>
    </div>
</nav>
