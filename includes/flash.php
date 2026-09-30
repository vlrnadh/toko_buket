<?php
// Flash message: disimpan di session, tampil sekali, lalu dihapus.

function set_flash($tipe, $pesan)
{
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
}

function tampil_flash()
{
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        echo '<div class="alert alert-' . e($f['tipe']) . '" role="status">' . e($f['pesan']) . '</div>';
        unset($_SESSION['flash']);
    }
}
