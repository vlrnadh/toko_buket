<?php

require_once "../config/database.php";
require_once "../includes/auth.php";
require_once "../includes/flash.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $lama       = $_POST['password_lama'] ?? '';
    $baru       = $_POST['password_baru'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    // Ambil password admin yang sedang login
    $stmt = $conn->prepare("SELECT password FROM admin WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['admin_id']);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // ---------- Validasi ----------
    if (empty($lama) || empty($baru) || empty($konfirmasi)) {
        $errors[] = "Semua kolom wajib diisi.";
    } else {

        if (!$admin || !password_verify($lama, $admin['password'])) {
            $errors[] = "Password lama tidak sesuai.";
        }
        if (strlen($baru) < 8) {
            $errors[] = "Password baru minimal 8 karakter.";
        }
        if ($baru !== $konfirmasi) {
            $errors[] = "Konfirmasi password tidak sama dengan password baru.";
        }
        if ($baru === $lama) {
            $errors[] = "Password baru harus berbeda dari password lama.";
        }
    }

    // ---------- Simpan ----------
    if (empty($errors)) {

        $hash = password_hash($baru, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $_SESSION['admin_id']);

        if ($stmt->execute()) {
            session_regenerate_id(true);
            set_flash("sukses", "Password berhasil diubah.");
            header("Location: index.php");
            exit;
        }

        $errors[] = "Password gagal disimpan. Coba lagi.";
        $stmt->close();
    }
}

$is_admin = true;
$judul    = "Ubah Password";
require_once "../includes/header.php";

?>

<h1>Ubah password</h1>

<?php if ($errors): ?>
    <div class="alert alert-error">
        <strong>Password belum bisa diubah:</strong>
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= e($err); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" class="card form-produk">

    <label for="password_lama">Password lama</label>
    <input type="password" id="password_lama" name="password_lama" autocomplete="current-password">

    <label for="password_baru">Password baru (minimal 8 karakter)</label>
    <input type="password" id="password_baru" name="password_baru" autocomplete="new-password">

    <label for="konfirmasi">Ulangi password baru</label>
    <input type="password" id="konfirmasi" name="konfirmasi" autocomplete="new-password">

    <div class="aksi">
        <button type="submit" class="btn">Simpan password</button>
        <a href="index.php" class="btn btn-garis">Batal</a>
    </div>

</form>

<?php require_once "../includes/footer.php"; ?>
