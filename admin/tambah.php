<?php

require_once "../config/database.php";
require_once "../includes/auth.php";
require_once "../includes/flash.php";

// Jika validasi gagal, proses_produk.php mengembalikan error & isian lama lewat session
$errors = $_SESSION['errors'] ?? [];
$data   = $_SESSION['old'] ?? ['nama' => '', 'kategori' => '', 'deskripsi' => '', 'harga' => '', 'stok' => ''];
unset($_SESSION['errors'], $_SESSION['old']);

$aksi     = "tambah";
$is_admin = true;
$judul    = "Tambah Produk";
require_once "../includes/header.php";

?>

<h1>Tambah produk</h1>

<?php
require "_form_produk.php";
require_once "../includes/footer.php";
