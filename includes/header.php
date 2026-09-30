<?php
// Variabel opsional sebelum include: $is_admin (true/false), $judul (judul halaman)
$is_admin = $is_admin ?? false;
$judul    = $judul ?? NAMA_TOKO;
$base     = $is_admin ? "../" : "";
$hal      = basename($_SERVER['SCRIPT_NAME']);

function nav_aktif($file, $hal)
{
    return $file === $hal ? ' class="aktif"' : '';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($judul); ?> | <?= NAMA_TOKO; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base; ?>assets/style.css">
</head>

<body class="<?= $is_admin ? 'admin' : 'publik'; ?>">

<header class="navbar">
    <div class="container nav-inner">

        <a href="index.php" class="brand"><?= NAMA_TOKO; ?></a>

        <nav class="nav-links">
        <?php if ($is_admin && !isset($_SESSION['admin_id'])): ?>
            <a href="../index.php">Kembali ke website</a>
        <?php elseif ($is_admin): ?>
            <a href="index.php"<?= nav_aktif('index.php', $hal); ?>>Dashboard</a>
            <a href="produk.php"<?= nav_aktif('produk.php', $hal); ?>>Kelola Produk</a>
            <a href="password.php"<?= nav_aktif('password.php', $hal); ?>>Ubah Password</a>
            <a href="../index.php">Lihat Website</a>
            <a href="logout.php">Keluar</a>
        <?php else: ?>
            <a href="index.php"<?= nav_aktif('index.php', $hal); ?>>Beranda</a>
            <a href="produk.php"<?= nav_aktif('produk.php', $hal); ?>>Produk</a>
            <a href="tentang.php"<?= nav_aktif('tentang.php', $hal); ?>>Tentang Kami</a>
        <?php endif; ?>
        </nav>

    </div>
</header>

<main class="container">
