<?php

require_once "../config/database.php";
require_once "../includes/auth.php";
require_once "../includes/flash.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: produk.php");
    exit;
}

$aksi      = $_POST['aksi'] ?? '';
$id        = (int)($_POST['id'] ?? 0);
$nama      = trim($_POST['nama'] ?? '');
$kategori  = $_POST['kategori'] ?? '';
$deskripsi = trim($_POST['deskripsi'] ?? '');
$harga     = trim($_POST['harga'] ?? '');
$stok      = trim($_POST['stok'] ?? '');

$halaman_form = $aksi === 'edit' ? "edit.php?id=$id" : "tambah.php";

// ---------- 1. Validasi input (sisi server) ----------
$errors = [];

if (empty($nama)) {
    $errors[] = "Nama produk wajib diisi.";
} elseif (strlen($nama) > 100) {
    $errors[] = "Nama produk maksimal 100 karakter.";
}

if (!in_array($kategori, KATEGORI, true)) {
    $errors[] = "Kategori wajib dipilih.";
}

if (empty($deskripsi)) {
    $errors[] = "Deskripsi wajib diisi.";
} elseif (strlen($deskripsi) > 255) {
    $errors[] = "Deskripsi maksimal 255 karakter.";
}

if ($harga === '' || !is_numeric($harga) || $harga < 0) {
    $errors[] = "Harga harus berupa angka dan tidak boleh negatif.";
}

if ($stok === '' || !ctype_digit($stok)) {
    $errors[] = "Stok harus berupa bilangan bulat 0 atau lebih.";
}

// Data lama produk (untuk edit)
$gambar_lama = null;
if ($aksi === 'edit') {
    $stmt = $conn->prepare("SELECT gambar FROM produk WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $lama = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$lama) {
        set_flash("error", "Produk tidak ditemukan.");
        header("Location: produk.php");
        exit;
    }
    $gambar_lama = $lama['gambar'];
} elseif ($aksi !== 'tambah') {
    header("Location: produk.php");
    exit;
}

// ---------- 2. Upload gambar ----------
$nama_baru = null;

if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {

    $file = $_FILES['gambar'];

    if ($file['error'] !== UPLOAD_ERR_OK) {

        $errors[] = "Foto gagal diunggah. Coba pilih file lain.";

    } else {

        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed  = ['jpg', 'jpeg', 'png'];
        $maxSize  = 2 * 1024 * 1024; // 2MB
        $isGambar = @getimagesize($file['tmp_name']) !== false; // pastikan isinya benar-benar gambar

        if (!in_array($ext, $allowed, true) || !$isGambar) {
            $errors[] = "Foto harus berupa file JPG atau PNG.";
        } elseif ($file['size'] > $maxSize) {
            $errors[] = "Ukuran foto maksimal 2 MB.";
        } elseif (!$errors) {

            $nama_baru = uniqid() . '.' . $ext;

            if (!move_uploaded_file($file['tmp_name'], "../uploads/produk/" . $nama_baru)) {
                $errors[] = "Foto gagal disimpan di server.";
                $nama_baru = null;
            }
        }
    }
}

// ---------- 3. Ada error? kembalikan ke form ----------
if ($errors) {

    // Foto yang sudah terlanjur tersimpan tidak dipakai lagi
    if ($nama_baru && is_file("../uploads/produk/" . $nama_baru)) {
        unlink("../uploads/produk/" . $nama_baru);
    }

    $_SESSION['errors'] = $errors;
    $_SESSION['old']    = compact('nama', 'kategori', 'deskripsi', 'harga', 'stok');

    header("Location: " . $halaman_form);
    exit;
}

// ---------- 4. Simpan ke database ----------
if ($aksi === 'tambah') {

    $stmt = $conn->prepare(
        "INSERT INTO produk (nama, kategori, deskripsi, harga, stok, gambar)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("sssdis", $nama, $kategori, $deskripsi, $harga, $stok, $nama_baru);

    if ($stmt->execute()) {
        set_flash("sukses", "Produk berhasil ditambahkan.");
    } else {
        set_flash("error", "Produk gagal disimpan.");
    }

} else {

    // Jika ada foto baru pakai foto baru, kalau tidak pertahankan foto lama
    $gambar_simpan = $nama_baru ?? $gambar_lama;

    $stmt = $conn->prepare(
        "UPDATE produk
         SET nama = ?, kategori = ?, deskripsi = ?, harga = ?, stok = ?, gambar = ?
         WHERE id = ?"
    );
    $stmt->bind_param("sssdisi", $nama, $kategori, $deskripsi, $harga, $stok, $gambar_simpan, $id);

    if ($stmt->execute()) {

        // Hapus foto lama bila sudah diganti
        if ($nama_baru && $gambar_lama && is_file("../uploads/produk/" . basename($gambar_lama))) {
            unlink("../uploads/produk/" . basename($gambar_lama));
        }

        set_flash("sukses", "Produk berhasil diperbarui.");

    } else {

        set_flash("error", "Produk gagal diperbarui.");
    }
}

$stmt->close();

header("Location: produk.php");
exit;
