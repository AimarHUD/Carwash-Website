<?php
require_once 'includes/company_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?> - Company Profile</title>
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

    <main>
        <section class="hero">
            <div class="container hero-content">
                <span class="hero-badge">✨ Layanan cuci kendaraan modern</span>
                <h1><?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></h1>
                <p><?= htmlspecialchars($company['deskripsi'] ?? 'Solusi lengkap untuk mencuci kendaraan Anda dengan cepat, bersih, dan ramah lingkungan.') ?></p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="service.php">Lihat Layanan</a>
                    <a class="btn btn-secondary" href="kontak.php">Hubungi Kami</a>
                </div>
            </div>
        </section>

        <section class="overview container">
            <div class="card">
                <h2>Kenapa <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?>?</h2>
                <p>Kami menghadirkan layanan cuci kendaraan yang cepat, bersih, dan profesional dengan standar kualitas tinggi untuk setiap kendaraan pelanggan.</p>
            </div>
            <div class="card">
                <h2>Visi Kami</h2>
                <p><?= htmlspecialchars($company['visi'] ?? 'Menjadi layanan cuci kendaraan pilihan utama di kawasan kami.') ?></p>
            </div>
        </section>

        <section class="features container">
            <article class="feature-item">
                <h3>Pelayanan Cepat</h3>
                <p>Proses pencucian yang efisien tanpa mengurangi kualitas hasil akhir.</p>
            </article>
            <article class="feature-item">
                <h3>Ramah Lingkungan</h3>
                <p>Produk dan metode yang aman untuk kendaraan serta lingkungan sekitar.</p>
            </article>
            <article class="feature-item">
                <h3>Kualitas Terjamin</h3>
                <p>Tim berpengalaman menjaga detail kendaraan Anda dengan standar layanan terbaik.</p>
            </article>
        </section>

        <section class="container contact-grid">
            <div class="contact-card">
                <h2>Tentang Kami</h2>
                <p><?= htmlspecialchars($company['deskripsi'] ?? 'Layanan cuci kendaraan modern.') ?></p>
                <p><strong>Misi:</strong> <?= htmlspecialchars($company['misi'] ?? 'Memberikan layanan berkualitas dengan harga terjangkau.') ?></p>
            </div>
            <div class="contact-card">
                <h2>Informasi Kontak</h2>
                <p><strong>Alamat:</strong> <?= htmlspecialchars($company['alamat'] ?? '-') ?></p>
                <p><strong>Telepon:</strong> <?= htmlspecialchars($company['no_telp'] ?? '-') ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($company['email'] ?? '-') ?></p>
                <p><strong>Jam Operasional:</strong> <?= htmlspecialchars($company['jam_operasional'] ?? '-') ?></p>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?>. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>