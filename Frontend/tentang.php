<?php
require_once 'includes/company_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="logo" href="index.php"><?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></a>
            <nav class="site-nav">
                <a href="index.php">Home</a>
                <a href="tentang.php">Tentang Kita</a>
                <a href="service.php">Service Kita</a>
                <a href="artikel.php">Artikel</a>
                <a href="kontak.php">Kontak Kita</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="page-section">
            <h1>Tentang <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></h1>
            <p><?= htmlspecialchars($company['deskripsi'] ?? 'Perusahaan cuci kendaraan yang fokus pada kualitas, efisiensi, dan pengalaman pelanggan terbaik.') ?></p>
            <h2>Visi Kami</h2>
            <p><?= htmlspecialchars($company['visi'] ?? 'Menjadi pusat perawatan kendaraan populer dan tepercaya di komunitas lokal.') ?></p>
            <h2>Misi Kami</h2>
            <p><?= htmlspecialchars($company['misi'] ?? 'Menyediakan layanan cuci kendaraan yang nyaman, cepat, dan ramah lingkungan untuk semua pelanggan kami.') ?></p>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?>. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>
