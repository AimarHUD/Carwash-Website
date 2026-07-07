<?php
require_once 'includes/company_data.php';
require_once dirname(__DIR__) . '/config/koneksi.php';

$stmt = $pdo->prepare('SELECT * FROM tb_artikel WHERE status = "Publish" ORDER BY tanggal DESC, created_at DESC');
$stmt->execute();
$articles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel - <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></title>
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
                <a href="articles.php">Artikel</a>
                <a href="contact.php">Kontak</a>
            </div>
        </nav>
    </header>

    <main class="container page-section">
        <h1>Artikel & Tips</h1>
        <p>Temukan tips perawatan kendaraan, insight layanan, dan informasi terbaru dari WashWoosh.</p>

        <div class="service-grid">
            <?php if (empty($articles)): ?>
                <div class="card">
                    <h3>Belum ada artikel</h3>
                    <p>Artikel akan segera ditampilkan setelah admin mempublikasikannya.</p>
                </div>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <article class="card">
                        <h3><?= htmlspecialchars($article['judul']) ?></h3>
                        <p><?= nl2br(htmlspecialchars(substr($article['isi'], 0, 180))) ?><?= strlen($article['isi']) > 180 ? '...' : '' ?></p>
                        <p><small>Oleh <?= htmlspecialchars($article['penulis']) ?> • <?= date('d M Y', strtotime($article['tanggal'])) ?></small></p>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?>. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>
