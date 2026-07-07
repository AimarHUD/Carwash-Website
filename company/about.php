<?php
require_once 'includes/company_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></title>
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
        <h1>Tentang <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></h1>
        <p><?= htmlspecialchars($company['deskripsi'] ?? 'Kami adalah perusahaan cuci kendaraan yang fokus pada kualitas layanan.') ?></p>
        <p><strong>Visi:</strong> <?= htmlspecialchars($company['visi'] ?? '-') ?></p>
        <p><strong>Misi:</strong> <?= htmlspecialchars($company['misi'] ?? '-') ?></p>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?>. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>
