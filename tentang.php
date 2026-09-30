<?php

require_once "config/database.php";

$judul = "Tentang Kami";
require_once "includes/header.php";

?>

<div class="card teks-panjang">

    <h1>Tentang <?= NAMA_TOKO; ?></h1>

    <p>
        <?= NAMA_TOKO; ?> adalah UMKM florist yang merangkai buket
        secara handmade dari bunga segar pilihan. Setiap rangkaian
        dibuat setelah pesanan masuk, jadi bunga sampai ke tanganmu
        dalam keadaan terbaik.
    </p>

    <p>
        Kami melayani buket untuk ulang tahun, wisuda, pernikahan,
        anniversary, hingga hadiah kecil tanpa alasan khusus.
        Warna, ukuran, dan kartu ucapan bisa disesuaikan.
    </p>

    <h2>Cara memesan</h2>

    <p>
        Pilih buket di halaman Produk, buka detailnya, lalu tekan
        tombol WhatsApp. Sebutkan tanggal dan alamat pengiriman,
        kami konfirmasi ketersediaannya.
    </p>

    <h2>Kontak</h2>

    <p>WhatsApp: <a href="https://wa.me/<?= WA_NOMOR; ?>">+<?= WA_NOMOR; ?></a></p>

    <p>Instagram: @nadhaflorist</p>

    <p>Jam buka: setiap hari, 08.00 - 20.00 WIB</p>

</div>

<?php require_once "includes/footer.php"; ?>
