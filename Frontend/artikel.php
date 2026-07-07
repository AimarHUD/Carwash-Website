<?php
require_once '../config/koneksi.php';

$pdo->exec('CREATE TABLE IF NOT EXISTS tb_artikel (
    id_artikel INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isi TEXT NOT NULL,
    gambar VARCHAR(255) DEFAULT NULL,
    penulis VARCHAR(100) NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM("Draft","Publish") NOT NULL DEFAULT "Draft",
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$stmt = $pdo->prepare('SELECT * FROM tb_artikel WHERE status = "Publish" ORDER BY tanggal DESC, created_at DESC');
$stmt->execute();
$articles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel WashWoosh</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="logo" href="index.php">WashWoosh</a>
            <nav class="site-nav">
                <a href="index.php">Home</a>
                <a href="tentang.php">Tentang Kita</a>
                <a href="service.php">Service Kita</a>
                <a href="artikel.php">Artikel</a>
                <a href="kontak.php">Kontak Kita</a>
            </nav>
        </div>
    </header>

    <main class="container content-wrapper">
        <section class="page-section">
            <h1>Artikel</h1>
            <?php if (empty($articles)): ?>
                <article>
                    <h2>Belum ada artikel yang dipublikasikan.</h2>
                    <p>Silakan kembali nanti setelah admin menambahkan artikel baru.</p>
                </article>
            <?php else: ?>
                <?php foreach ($articles as $artikel): ?>
                    <article>
                        <h2><?= htmlspecialchars($artikel['judul']) ?></h2>
                        <p><?= nl2br(htmlspecialchars(substr($artikel['isi'], 0, 280))) ?><?= strlen($artikel['isi']) > 280 ? '...' : '' ?></p>
                        <p><small>Oleh <?= htmlspecialchars($artikel['penulis']) ?> | <?= date('d M Y', strtotime($artikel['tanggal'])) ?></small></p>
                        <p><a href="artikel_detail.php?id=<?= htmlspecialchars($artikel['id_artikel']) ?>">Baca Selengkapnya</a></p>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; 2026 WashWoosh. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>
