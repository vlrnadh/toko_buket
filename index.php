<?php

require_once "config/database.php";

$query = mysqli_query(
    $conn,
    "SELECT * FROM produk
     ORDER BY id DESC
     LIMIT 6"
);

$judul = "Beranda";
require_once "includes/header.php";

?>

<section class="hero">

    <div class="hero-teks">

        <h1>Bunga yang bicara,<br>saat kata-kata tak cukup.</h1>

        <p>
            Buket handmade dari bunga segar untuk ulang tahun, wisuda,
            pernikahan, dan hari-hari kecil yang ingin kamu rayakan.
        </p>

        <a href="produk.php" class="btn">Lihat Koleksi</a>

    </div>

</section>

<section class="bagian">

    <h2>Cari sesuai momen</h2>

    <div class="chips">
        <?php foreach (KATEGORI as $k): ?>
            <a href="produk.php?kategori=<?= urlencode($k); ?>"><?= e($k); ?></a>
        <?php endforeach; ?>
    </div>

</section>

<section class="bagian">

    <h2>Buket terbaru</h2>

    <div class="grid">

        <?php while ($row = mysqli_fetch_assoc($query)): ?>

        <div class="card produk-card">

            <?= gambar_produk($row['gambar'], $row['nama']); ?>

            <div class="card-isi">

                <span class="tag"><?= e($row['kategori']); ?></span>

                <h3><?= e($row['nama']); ?></h3>

                <strong class="harga"><?= rupiah($row['harga']); ?></strong>

                <a href="detail.php?id=<?= $row['id']; ?>" class="btn btn-kecil">Detail</a>

            </div>

        </div>

        <?php endwhile; ?>

    </div>

</section>

<?php require_once "includes/footer.php"; ?>
