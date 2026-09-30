<?php

require_once "config/database.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$produk = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$produk) {
    http_response_code(404);
    die("Produk tidak ditemukan.");
}

$pesan_wa = "Halo " . NAMA_TOKO . ", saya ingin memesan " . $produk['nama'] . ".";

$judul = $produk['nama'];
require_once "includes/header.php";

?>

<div class="card detail">

    <?= gambar_produk($produk['gambar'], $produk['nama']); ?>

    <div class="detail-isi">

        <span class="tag"><?= e($produk['kategori']); ?></span>

        <h1><?= e($produk['nama']); ?></h1>

        <p><?= nl2br(e($produk['deskripsi'])); ?></p>

        <h2 class="harga"><?= rupiah($produk['harga']); ?></h2>

        <p>
            Stok:
            <?php if ($produk['stok'] > 0): ?>
                <?= (int)$produk['stok']; ?> tersedia
            <?php else: ?>
                <span class="habis">Habis</span>
            <?php endif; ?>
        </p>

        <div class="aksi">

            <?php if ($produk['stok'] > 0): ?>
                <a
                    href="https://wa.me/<?= WA_NOMOR; ?>?text=<?= urlencode($pesan_wa); ?>"
                    class="btn"
                    target="_blank"
                    rel="noopener"
                >
                    Pesan lewat WhatsApp
                </a>
            <?php endif; ?>

            <a href="produk.php" class="btn btn-garis">Kembali</a>

        </div>

    </div>

</div>

<?php require_once "includes/footer.php"; ?>
