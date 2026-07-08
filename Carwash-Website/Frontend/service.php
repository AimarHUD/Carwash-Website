<?php
require_once 'includes/company_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .service-gallery{display:flex;gap:16px;flex-wrap:wrap;margin-top:18px}
        .service-gallery .card{flex:1 1 calc(33% - 16px);min-width:220px;background:#fff;border-radius:6px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,.08)}
        .service-gallery img{display:block;width:100%;height:auto;object-fit:cover}
        .service-gallery figcaption{padding:8px 10px;font-size:14px;color:#333}
        @media(max-width:720px){.service-gallery{flex-direction:column}.service-gallery .card{flex:1 1 100%}}
    </style>
</head>
<body>
    <header class="site-header py-3">
        <div class="container-fluid px-5 d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold fs-4 text-primary text-decoration-none" href="index.php"><?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></a>
            <nav class="site-nav d-flex gap-4 fw-semibold">
                <a class="text-decoration-none text-dark" href="index.php">Home</a>
                <a class="text-decoration-none text-dark" href="tentang.php">Tentang Kami</a>
                <a class="text-decoration-none text-dark" href="service.php">Service Kami</a>
                <a class="text-decoration-none text-dark" href="artikel.php">Artikel</a>
                <a class="text-decoration-none text-dark" href="kontak.php">Kontak Kami</a>
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
            <section aria-labelledby="gallery-heading" class="service-gallery-section">
                <h2 id="gallery-heading">Contoh Pekerjaan Kami</h2>
                <div class="service-gallery" role="list">
                    <figure class="card" role="listitem">
                        <img src="assets/images/service1.jpg" alt="Detailing bodi mobil biru" loading="lazy">
                        <figcaption>Detailing eksterior — kilap maksimal</figcaption>
                    </figure>
                    <figure class="card" role="listitem">
                        <img src="assets/images/service2.jpg" alt="Interior mobil bersih" loading="lazy">
                        <figcaption>Pembersihan interior — rapi dan higienis</figcaption>
                    </figure>
                    <figure class="card" role="listitem">
                        <img src="assets/images/service3.jpg" alt="Proses polishing pada kap mesin" loading="lazy">
                        <figcaption>Polishing & finishing — proteksi cat</figcaption>
                    </figure>
                </div>
                <p class="muted">Catatan: Taruh tiga foto di <strong>Frontend/assets/images/</strong> dengan nama <em>service1.jpg</em>, <em>service2.jpg</em>, dan <em>service3.jpg</em>.</p>
            </section>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?>. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>
