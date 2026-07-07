<?php
require_once 'includes/company_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan - <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></title>
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
        <h1>Layanan Kami</h1>
        <div class="service-grid">
            <div class="card">
                <h3>Cuci Eksterior</h3>
                <p>Pembersihan bodi, velg, jendela, dan detail eksterior kendaraan.</p>
            </div>
            <div class="card">
                <h3>Cuci Interior</h3>
                <p>Vakum, pembersihan jok, dashboard, dan area interior lainnya.</p>
            </div>
            <div class="card">
                <h3>Detailing Premium</h3>
                <p>Perawatan menyeluruh untuk menjaga kilau dan kualitas cat kendaraan.</p>
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
