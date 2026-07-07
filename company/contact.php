<?php
require_once 'includes/company_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="sub-header">
        <nav class="top-nav container">
            <a href="index.php" class="brand"><?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></a>
            <div class="nav-links">
                <a href="index.php">Beranda</a>
                <a href="about.php">Tentang</a>
                <a href="services.php">Layanan</a>
                <a href="contact.php">Kontak</a>
            </div>
        </nav>
    </header>

    <main class="container page-section">
        <h1>Hubungi Kami</h1>
        <div class="contact-box">
            <div class="card">
                <p><strong>Alamat:</strong> <?= htmlspecialchars($company['alamat'] ?? '-') ?></p>
                <p><strong>Telepon:</strong> <?= htmlspecialchars($company['no_telp'] ?? '-') ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($company['email'] ?? '-') ?></p>
                <p><strong>Jam Operasional:</strong> <?= htmlspecialchars($company['jam_operasional'] ?? '-') ?></p>
            </div>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?>. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>
