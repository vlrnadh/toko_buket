</main>

<footer class="footer">
    <div class="container">
        <?php if (!empty($is_admin)): ?>
            <p>Panel admin <?= NAMA_TOKO; ?></p>
        <?php else: ?>
            <p>&copy; <?= date('Y'); ?> <?= NAMA_TOKO; ?>. Bunga segar, dirangkai sesuai pesananmu.</p>
            <p><a href="admin/login.php">Masuk admin</a></p>
        <?php endif; ?>
    </div>
</footer>

</body>

</html>
