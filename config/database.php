<?php
// Koneksi database + fungsi bantu yang dipakai di seluruh aplikasi

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "toko_buket";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// ---------- Pengaturan toko ----------
const NAMA_TOKO = "Nadha Florist";
const WA_NOMOR  = "6281234567890"; // ganti dengan nomor WhatsApp toko (format 62...)
const KATEGORI  = [
    "Hand Bouquet",
    "Bouquet Wisuda",
    "Money Bouquet",
    "Flower Box",
    "Bunga Meja",
    "Bouquet Pernikahan",
];

// ---------- Fungsi bantu ----------
function e($teks)
{
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}

function rupiah($angka)
{
    return "Rp " . number_format((float)$angka, 0, ',', '.');
}

// Menampilkan foto produk, atau ilustrasi bunga jika belum ada foto
function gambar_produk($gambar, $nama, $base = "")
{
    if ($gambar && file_exists(__DIR__ . "/../uploads/produk/" . $gambar)) {
        return '<img src="' . $base . 'uploads/produk/' . e($gambar) . '" alt="' . e($nama) . '" class="product-image">';
    }

    return '<div class="product-image placeholder" role="img" aria-label="Belum ada foto">
        <svg viewBox="0 0 100 100" width="64" height="64" aria-hidden="true">
            <g fill="#E8A5BA">
                <ellipse cx="50" cy="28" rx="11" ry="18"/>
                <ellipse cx="50" cy="28" rx="11" ry="18" transform="rotate(72 50 50)"/>
                <ellipse cx="50" cy="28" rx="11" ry="18" transform="rotate(144 50 50)"/>
                <ellipse cx="50" cy="28" rx="11" ry="18" transform="rotate(216 50 50)"/>
                <ellipse cx="50" cy="28" rx="11" ry="18" transform="rotate(288 50 50)"/>
            </g>
            <circle cx="50" cy="50" r="9" fill="#F3C969"/>
        </svg></div>';
}

// Pencarian + filter kategori + paginasi (prepared statement)
function cari_produk($conn, $keyword, $kategori, $page, $per_page)
{
    $where  = "WHERE 1=1";
    $types  = "";
    $params = [];

    if ($keyword !== '') {
        $where   .= " AND nama LIKE ?";
        $types   .= "s";
        $params[] = "%$keyword%";
    }
    if ($kategori !== '') {
        $where   .= " AND kategori = ?";
        $types   .= "s";
        $params[] = $kategori;
    }

    // Hitung total data
    $stmt = $conn->prepare("SELECT COUNT(*) FROM produk $where");
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();

    $total_pages = max(1, (int)ceil($total / $per_page));
    $page        = min(max(1, $page), $total_pages);
    $offset      = ($page - 1) * $per_page;

    // Ambil data halaman aktif
    $stmt  = $conn->prepare("SELECT * FROM produk $where ORDER BY id DESC LIMIT ? OFFSET ?");
    $semua = array_merge($params, [$per_page, $offset]);
    $stmt->bind_param($types . "ii", ...$semua);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return compact('data', 'total', 'total_pages', 'page');
}

// Navigasi halaman, mempertahankan keyword & kategori di URL
function tampil_paginasi($page, $total_pages, $extra = [])
{
    if ($total_pages <= 1) {
        return;
    }
    $link = function ($p) use ($extra) {
        return '?' . http_build_query(array_filter($extra, fn($v) => $v !== '') + ['page' => $p]);
    };

    echo '<nav class="pagination" aria-label="Halaman">';
    if ($page > 1) {
        echo '<a href="' . $link($page - 1) . '">Sebelumnya</a>';
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $kelas = $i === $page ? ' class="aktif" aria-current="page"' : '';
        echo '<a href="' . $link($i) . '"' . $kelas . '>' . $i . '</a>';
    }
    if ($page < $total_pages) {
        echo '<a href="' . $link($page + 1) . '">Berikutnya</a>';
    }
    echo '</nav>';
}
