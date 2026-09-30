<?php
// Bagian form yang dipakai tambah.php dan edit.php
// Butuh: $aksi ('tambah'|'edit'), $data (array nilai), $errors (array)
?>
<?php if ($errors): ?>
    <div class="alert alert-error">
        <strong>Periksa kembali isian berikut:</strong>
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= e($err); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="proses_produk.php" enctype="multipart/form-data" class="card form-produk">

    <input type="hidden" name="aksi" value="<?= e($aksi); ?>">
    <?php if ($aksi === 'edit'): ?>
        <input type="hidden" name="id" value="<?= (int)$data['id']; ?>">
    <?php endif; ?>

    <label for="nama">Nama produk</label>
    <input type="text" id="nama" name="nama" maxlength="100" value="<?= e($data['nama']); ?>">

    <label for="kategori">Kategori</label>
    <select id="kategori" name="kategori">
        <option value="">Pilih kategori</option>
        <?php foreach (KATEGORI as $k): ?>
            <option value="<?= e($k); ?>" <?= $data['kategori'] === $k ? 'selected' : ''; ?>><?= e($k); ?></option>
        <?php endforeach; ?>
    </select>

    <label for="deskripsi">Deskripsi (maks. 255 karakter)</label>
    <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="255"><?= e($data['deskripsi']); ?></textarea>

    <div class="dua-kolom">
        <div>
            <label for="harga">Harga (Rp)</label>
            <input type="text" id="harga" name="harga" inputmode="numeric" value="<?= e($data['harga']); ?>">
        </div>
        <div>
            <label for="stok">Stok</label>
            <input type="text" id="stok" name="stok" inputmode="numeric" value="<?= e($data['stok']); ?>">
        </div>
    </div>

    <label for="gambar">Foto produk (JPG atau PNG, maks. 2 MB)</label>

    <?php if ($aksi === 'edit' && !empty($data['gambar'])): ?>
        <div class="foto-sekarang">
            <?= gambar_produk($data['gambar'], $data['nama'], '../'); ?>
            <small>Foto saat ini. Pilih file baru hanya jika ingin menggantinya.</small>
        </div>
    <?php endif; ?>

    <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png">

    <div class="aksi">
        <button type="submit" class="btn"><?= $aksi === 'edit' ? 'Simpan perubahan' : 'Simpan produk'; ?></button>
        <a href="produk.php" class="btn btn-garis">Batal</a>
    </div>

</form>
