<?php
require_once 'includes/company_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="logo" href="index.php"><?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></a>
            <nav class="site-nav">
                <a href="index.php">Home</a>
                <a href="tentang.php">Tentang Kami</a>
                <a href="service.php">Layanan Kami</a>
                <a href="artikel.php">Artikel</a>
                <a href="kontak.php">Kontak Kami</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="page-section">
            <h1>Service Kita</h1>
            <p>Di WashWoosh, kami menawarkan beberapa paket layanan cuci kendaraan yang cocok untuk mobil dan motor Anda.</p>
            <ul>
                <li><strong>Cuci Eksterior</strong> - pembersihan bodi, velg, dan ban hingga tampilan kembali bersinar.</li>
                <li><strong>Cuci Interior</strong> - vakum, pembersihan dashboard, & perawatan interior secara menyeluruh.</li>
                <li><strong>Detailing</strong> - layanan detailing mendalam untuk melindungi cat dan meningkatkan kilap.</li>
                <li><strong>Paket Premium</strong> - layanan lengkap dengan wax, polish, dan perawatan tambahan.</li>
            </ul>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?>. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>
