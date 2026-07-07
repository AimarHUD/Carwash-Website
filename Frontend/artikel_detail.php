<?php
require_once 'includes/company_data.php';
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

$artikelId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($artikelId <= 0) {
    header('Location: artikel.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM tb_artikel WHERE id_artikel = :id AND status = "Publish" LIMIT 1');
$stmt->execute(['id' => $artikelId]);
$artikel = $stmt->fetch();

if (!$artikel) {
    header('Location: artikel.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($artikel['judul']) ?> - <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></title>
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

    <main class="container content-wrapper">
        <section class="page-section">
            <h1><?= htmlspecialchars($artikel['judul']) ?></h1>
            <p><small>Oleh <?= htmlspecialchars($artikel['penulis']) ?> | <?= date('d M Y', strtotime($artikel['tanggal'])) ?></small></p>
            <?php if (!empty($artikel['gambar'])): ?>
                <div style="margin-bottom: 24px;">
                    <img src="<?= htmlspecialchars($artikel['gambar']) ?>" alt="<?= htmlspecialchars($artikel['judul']) ?>" style="width:100%;border-radius:16px;">
                </div>
            <?php endif; ?>
            <article>
                <?= nl2br(htmlspecialchars($artikel['isi'])) ?>
            </article>
            <div style="margin-top: 32px;">
                <a href="artikel.php" class="btn btn-secondary">Kembali ke Artikel</a>
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
