<?php

require_once "../config/database.php";
require_once "../includes/auth.php";
require_once "../includes/flash.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$produk = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$produk) {
    set_flash("error", "Produk tidak ditemukan.");
    header("Location: produk.php");
    exit;
}

$errors = $_SESSION['errors'] ?? [];
$data   = $produk;

// Isian lama (jika validasi gagal) menimpa data dari database, foto tetap dari database
if (isset($_SESSION['old'])) {
    $data = array_merge($produk, $_SESSION['old']);
}
unset($_SESSION['errors'], $_SESSION['old']);

$aksi     = "edit";
$is_admin = true;
$judul    = "Edit Produk";
require_once "../includes/header.php";

?>

<h1>Edit produk</h1>

<?php
require "_form_produk.php";
require_once "../includes/footer.php";
