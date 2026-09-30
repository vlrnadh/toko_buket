<?php

require_once "../config/database.php";
require_once "../includes/auth.php";
require_once "../includes/flash.php";

// Hapus hanya lewat POST supaya tidak terpicu oleh link biasa
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: produk.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);

$stmt = $conn->prepare("SELECT gambar FROM produk WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$produk = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$produk) {
    set_flash("error", "Produk tidak ditemukan.");
    header("Location: produk.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM produk WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    // Hapus juga file foto agar folder uploads tidak menumpuk
    if ($produk['gambar']) {
        $file = "../uploads/produk/" . basename($produk['gambar']);
        if (is_file($file)) {
            unlink($file);
        }
    }

    set_flash("sukses", "Produk berhasil dihapus.");

} else {

    set_flash("error", "Produk gagal dihapus.");
}

$stmt->close();

header("Location: produk.php");
exit;
