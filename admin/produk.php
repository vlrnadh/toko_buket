<?php

require_once "../config/database.php";
require_once "../includes/auth.php";
require_once "../includes/flash.php";

$keyword  = trim($_GET['keyword'] ?? '');
$kategori = $_GET['kategori'] ?? '';
$page     = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if (!in_array($kategori, KATEGORI, true)) {
    $kategori = '';
}

$hasil = cari_produk($conn, $keyword, $kategori, $page, 5);

$is_admin = true;
$judul    = "Kelola Produk";
require_once "../includes/header.php";

?>

<div class="baris-judul">
    <h1>Kelola produk</h1>
    <a href="tambah.php" class="btn">Tambah produk</a>
</div>

<?php tampil_flash(); ?>

<form method="GET" class="card filter-form">

    <input type="text" name="keyword" placeholder="Cari nama produk..." value="<?= e($keyword); ?>">

    <select name="kategori">
        <option value="">Semua kategori</option>
        <?php foreach (KATEGORI as $k): ?>
            <option value="<?= e($k); ?>" <?= $kategori === $k ? 'selected' : ''; ?>><?= e($k); ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="btn">Cari</button>

</form>

<p class="info"><?= $hasil['total']; ?> produk ditemukan</p>

<div class="card">
    <div class="tabel-wrap">
        <table>
            <thead>
                <tr><th>Foto</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if (!$hasil['data']): ?>
                    <tr><td colspan="6">Belum ada produk yang cocok.</td></tr>
                <?php endif; ?>

                <?php foreach ($hasil['data'] as $row): ?>
                <tr>
                    <td class="kolom-foto"><?= gambar_produk($row['gambar'], $row['nama'], '../'); ?></td>
                    <td><?= e($row['nama']); ?></td>
                    <td><?= e($row['kategori']); ?></td>
                    <td><?= rupiah($row['harga']); ?></td>
                    <td><?= (int)$row['stok']; ?></td>
                    <td class="aksi-tabel">
                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-kecil btn-garis">Edit</a>
                        <form method="POST" action="hapus.php" onsubmit="return confirm('Hapus produk ini? Tindakan ini tidak bisa dibatalkan.');">
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            <button type="submit" class="btn btn-kecil btn-bahaya">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
tampil_paginasi($hasil['page'], $hasil['total_pages'], ['keyword' => $keyword, 'kategori' => $kategori]);

require_once "../includes/footer.php";
