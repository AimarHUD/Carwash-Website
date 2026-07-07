<?php
require_once 'includes/company_data.php';
require_once '../config/koneksi.php';

$pdo->exec('CREATE TABLE IF NOT EXISTS tb_artikel (
    id_artikel INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isi TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    penulis VARCHAR(100) NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM("Draft","Publish") NOT NULL DEFAULT "Draft",
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$stmt = $pdo->prepare('SELECT * FROM tb_artikel WHERE status = "Publish" ORDER BY tanggal DESC, created_at DESC');
$stmt->execute();
$articles = $stmt->fetchAll();

function article_image_url(?string $path): ?string
{
    $path = trim((string) $path);
    if ($path === '') {
        return null;
    }

    return '../' . ltrim(str_replace('\\', '/', $path), '/');
}

function article_summary(string $content, int $limit = 120): string
{
    $cleanContent = trim(preg_replace('/\s+/u', ' ', strip_tags($content)) ?? '');

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($cleanContent) <= $limit) {
            return $cleanContent;
        }

        return mb_substr($cleanContent, 0, $limit - 3) . '...';
    }

    if (strlen($cleanContent) <= $limit) {
        return $cleanContent;
    }

    return substr($cleanContent, 0, $limit - 3) . '...';
}

$featuredArticle = $articles[0] ?? null;
$otherArticles = array_slice($articles, 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <section class="page-section articles-page news-page">
            <div class="articles-intro news-intro">
                <p class="articles-kicker news-kicker">Portal Berita WashWoosh</p>
                <h1 class="mb-3">Artikel</h1>
                <p class="articles-lead news-lead">Berita, tips, dan informasi terbaru seputar layanan WashWoosh yang disusun dalam format kartu yang lebih rapi dan mudah dibaca.</p>
            </div>
            <?php if (empty($articles)): ?>
                <div class="alert alert-light border shadow-sm rounded-4 p-4 article-empty-state mb-0">
                    <h2 class="h4 mb-2">Belum ada artikel yang dipublikasikan.</h2>
                    <p class="mb-0 text-secondary">Silakan kembali nanti setelah admin menambahkan artikel baru.</p>
                </div>
            <?php else: ?>
                <div class="row g-4 news-grid">
                    <?php if ($featuredArticle): ?>
                        <?php $featuredImage = $featuredArticle['image'] ?? $featuredArticle['gambar'] ?? ''; ?>
                        <div class="col-12 col-md-6 col-lg-7">
                            <article class="card border-0 shadow-lg rounded-4 overflow-hidden h-100 news-card news-card-featured">
                                <a class="news-media news-media-featured" href="artikel_detail.php?id=<?= htmlspecialchars($featuredArticle['id_artikel']) ?>">
                                <?php if (!empty($featuredImage)): ?>
                                    <img src="<?= htmlspecialchars(article_image_url($featuredImage) ?? '') ?>" alt="<?= htmlspecialchars($featuredArticle['judul']) ?>">
                                <?php else: ?>
                                    <div class="news-placeholder">
                                        <span>Gambar belum tersedia</span>
                                    </div>
                                <?php endif; ?>
                                </a>
                                <div class="card-body p-4 p-xl-5 d-flex flex-column gap-3">
                                    <span class="badge text-bg-primary-subtle text-primary-emphasis rounded-pill px-3 py-2 align-self-start">Artikel Utama</span>
                                    <h2 class="card-title fw-bold mb-0 news-title news-title-featured"><?= htmlspecialchars($featuredArticle['judul']) ?></h2>
                                    <p class="card-text text-secondary mb-0 news-summary"><?= htmlspecialchars(article_summary($featuredArticle['isi'], 120)) ?></p>
                                    <div class="d-flex flex-wrap align-items-center gap-2 text-secondary small news-meta">
                                        <span><?= htmlspecialchars($featuredArticle['penulis']) ?></span>
                                        <span>&middot;</span>
                                        <span><?= date('d M Y', strtotime($featuredArticle['tanggal'])) ?></span>
                                    </div>
                                    <a class="btn btn-primary rounded-pill px-4 py-2 align-self-start news-button" href="artikel_detail.php?id=<?= htmlspecialchars($featuredArticle['id_artikel']) ?>">Baca Selengkapnya</a>
                                </div>
                            </article>
                        </div>
                    <?php endif; ?>

                    <div class="col-12 col-md-6 col-lg-5">
                        <div class="d-flex flex-column gap-3 news-list h-100">
                        <?php if (empty($otherArticles)): ?>
                            <div class="card border-0 shadow-sm rounded-4 news-card news-card-compact">
                                <div class="card-body p-4">
                                    <h2 class="h5 fw-bold mb-2">Artikel lain belum tersedia.</h2>
                                    <p class="text-secondary mb-0">Silakan cek kembali nanti saat admin menambahkan artikel baru.</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($otherArticles as $artikel): ?>
                                <?php $articleImage = $artikel['image'] ?? $artikel['gambar'] ?? ''; ?>
                                <article class="card border-0 shadow-sm rounded-4 overflow-hidden news-card news-card-compact">
                                    <a class="news-media news-media-compact" href="artikel_detail.php?id=<?= htmlspecialchars($artikel['id_artikel']) ?>">
                                        <?php if (!empty($articleImage)): ?>
                                            <img src="<?= htmlspecialchars(article_image_url($articleImage) ?? '') ?>" alt="<?= htmlspecialchars($artikel['judul']) ?>">
                                        <?php else: ?>
                                            <div class="news-placeholder">
                                                <span>Gambar belum tersedia</span>
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                    <div class="card-body p-4 d-flex flex-column gap-3">
                                        <h2 class="h5 fw-bold mb-0 news-title news-title-small"><?= htmlspecialchars($artikel['judul']) ?></h2>
                                        <p class="card-text text-secondary mb-0 news-summary"><?= htmlspecialchars(article_summary($artikel['isi'], 120)) ?></p>
                                        <div class="d-flex flex-wrap align-items-center gap-2 text-secondary small news-meta">
                                            <span><?= htmlspecialchars($artikel['penulis']) ?></span>
                                            <span>&middot;</span>
                                            <span><?= date('d M Y', strtotime($artikel['tanggal'])) ?></span>
                                        </div>
                                        <a class="btn btn-outline-primary rounded-pill px-4 py-2 align-self-start news-button news-button-small" href="artikel_detail.php?id=<?= htmlspecialchars($artikel['id_artikel']) ?>">Baca Selengkapnya</a>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?>. Semua hak dilindungi.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
