<?php

require_once "../config/database.php";
require_once "../includes/auth.php";
require_once "../includes/flash.php";

$total_produk = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM produk"))[0];
$total_stok   = mysqli_fetch_row(mysqli_query($conn, "SELECT COALESCE(SUM(stok),0) FROM produk"))[0];
$total_kat    = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(DISTINCT kategori) FROM produk"))[0];
$stok_tipis   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM produk WHERE stok <= 5"))[0];

$terbaru = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC LIMIT 5");

$is_admin = true;
$judul    = "Dashboard";
require_once "../includes/header.php";

?>

<div class="baris-judul">
    <h1>Dashboard</h1>
    <a href="password.php" class="btn btn-kecil btn-garis">Ubah password</a>
</div>

<?php tampil_flash(); ?>

<div class="stat-grid">

    <div class="card stat"><span>Total produk</span><strong><?= $total_produk; ?></strong></div>
    <div class="card stat"><span>Total stok bunga</span><strong><?= $total_stok; ?></strong></div>
    <div class="card stat"><span>Kategori aktif</span><strong><?= $total_kat; ?></strong></div>
    <div class="card stat <?= $stok_tipis ? 'stat-warn' : ''; ?>"><span>Stok menipis (5 atau kurang)</span><strong><?= $stok_tipis; ?></strong></div>

</div>

<div class="card">

    <div class="baris-judul">
        <h2>Produk terbaru</h2>
        <a href="tambah.php" class="btn btn-kecil">Tambah produk</a>
    </div>

    <div class="tabel-wrap">
        <table>
            <thead>
                <tr><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th></tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($terbaru)): ?>
                <tr>
                    <td><?= e($row['nama']); ?></td>
                    <td><?= e($row['kategori']); ?></td>
                    <td><?= rupiah($row['harga']); ?></td>
                    <td><?= (int)$row['stok']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once "../includes/footer.php"; ?>
