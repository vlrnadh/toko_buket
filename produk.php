<?php

require_once "config/database.php";

$keyword  = trim($_GET['keyword'] ?? '');
$kategori = $_GET['kategori'] ?? '';
$page     = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if (!in_array($kategori, KATEGORI, true)) {
    $kategori = '';
}

$hasil = cari_produk($conn, $keyword, $kategori, $page, 9);

$judul = "Produk";
require_once "includes/header.php";

?>

<h1>Koleksi Buket</h1>

<form method="GET" class="card filter-form">

    <input
        type="text"
        name="keyword"
        placeholder="Cari nama buket..."
        value="<?= e($keyword); ?>"
    >

    <select name="kategori">
        <option value="">Semua kategori</option>
        <?php foreach (KATEGORI as $k): ?>
            <option value="<?= e($k); ?>" <?= $kategori === $k ? 'selected' : ''; ?>>
                <?= e($k); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="btn">Cari</button>

</form>

<p class="info"><?= $hasil['total']; ?> buket ditemukan</p>

<?php if (!$hasil['data']): ?>

    <div class="card kosong">
        <p>Belum ada buket yang cocok. Coba kata kunci lain atau pilih semua kategori.</p>
        <a href="produk.php" class="btn">Reset pencarian</a>
    </div>

<?php endif; ?>

<div class="grid">

    <?php foreach ($hasil['data'] as $row): ?>

    <div class="card produk-card">

        <?= gambar_produk($row['gambar'], $row['nama']); ?>

        <div class="card-isi">

            <span class="tag"><?= e($row['kategori']); ?></span>

            <h3><?= e($row['nama']); ?></h3>

            <p class="ringkas"><?= e(mb_strimwidth($row['deskripsi'], 0, 90, '...')); ?></p>

            <strong class="harga"><?= rupiah($row['harga']); ?></strong>

            <a href="detail.php?id=<?= $row['id']; ?>" class="btn btn-kecil">Lihat Detail</a>

        </div>

    </div>

    <?php endforeach; ?>

</div>

<?php
tampil_paginasi(
    $hasil['page'],
    $hasil['total_pages'],
    ['keyword' => $keyword, 'kategori' => $kategori]
);

require_once "includes/footer.php";
