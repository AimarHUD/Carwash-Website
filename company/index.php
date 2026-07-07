<?php
require_once 'includes/company_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?> - Company Website</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="hero-header">
        <nav class="top-nav container">
            <a href="index.php" class="brand"><?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></a>
            <div class="nav-links">
                <a href="index.php">Beranda</a>
                <a href="about.php">Tentang</a>
                <a href="services.php">Layanan</a>
                <a href="contact.php">Kontak</a>
            </div>
        </nav>

        <div class="hero container">
            <div>
                <p class="eyebrow">✨ Layanan Cuci Kendaraan Modern</p>
                <h1><?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></h1>
                <p class="hero-text"><?= htmlspecialchars($company['deskripsi'] ?? 'Layanan cuci kendaraan modern yang cepat, bersih, dan ramah lingkungan.') ?></p>
                <div class="hero-actions">
                    <a href="services.php" class="btn btn-primary">Lihat Layanan</a>
                    <a href="contact.php" class="btn btn-secondary">Hubungi Kami</a>
                </div>
            </div>
            <div class="hero-visual">
                <img src="assets/images/carwash-hero.svg" alt="Mobil cuci WashWoosh">
            </div>
        </div>
    </header>

    <main class="container sections">
        <section class="card">
            <h2>Tentang Kami</h2>
            <p><?= htmlspecialchars($company['deskripsi'] ?? 'Kami hadir untuk memberikan pengalaman cuci kendaraan yang nyaman dan berkualitas.') ?></p>
        </section>

        <section class="card">
            <h2>Visi & Misi</h2>
            <p><strong>Visi:</strong> <?= htmlspecialchars($company['visi'] ?? '-') ?></p>
            <p><strong>Misi:</strong> <?= htmlspecialchars($company['misi'] ?? '-') ?></p>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?>. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>
