<?php

require_once "../config/database.php";
require_once "../includes/flash.php";

// Sudah login? langsung ke dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {

        $error = "Username dan password wajib diisi.";

    } else {

        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($admin && password_verify($password, $admin['password'])) {

            session_regenerate_id(true);
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_nama'] = $admin['nama'];

            set_flash("sukses", "Selamat datang, " . $admin['nama'] . ".");
            header("Location: index.php");
            exit;
        }

        $error = "Username atau password salah.";
    }
}

$is_admin = true;
$judul    = "Masuk Admin";
require_once "../includes/header.php";

?>

<div class="card login-card">

    <h1>Masuk admin</h1>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= e($error); ?></div>
    <?php endif; ?>

    <form method="POST">

        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?= e($_POST['username'] ?? ''); ?>" autofocus>

        <label for="password">Password</label>
        <input type="password" id="password" name="password">

        <button type="submit" class="btn">Masuk</button>

    </form>

</div>

<?php require_once "../includes/footer.php"; ?>
