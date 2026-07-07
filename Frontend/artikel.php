<?php
require_once 'includes/company_data.php';
require_once '../config/koneksi.php';

// --- LOGIKA BACKEND ANDA ---
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
// --- AKHIR LOGIKA BACKEND ---

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel & Tips - Carwash Woosh</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        /* Desain Konsisten: Latar Biru Muda & Footer Lengket ke bawah */
        body {
            background-color: #add8e6 !important; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .site-header {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Aksen Garis Biru & Efek Hover pada Card Artikel */
        .card-friendly {
            border-top: 5px solid #0d6efd !important;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-friendly:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }

        .img-artikel-cover {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }
    </style>
</head>
<body>

    <header class="site-header py-3">
        <div class="container-fluid px-5 d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold fs-3 text-primary text-decoration-none" href="index.php">
             <img src="assets/images/logo washwoosh.png" alt="Logo Carwash Woosh" width="70" class="me-2 d-none d-md-inline">
                Carwash Woosh
            </a>
            
            <nav class="site-nav d-flex gap-4 fw-semibold">
                <a class="text-decoration-none text-dark" href="index.php">Home</a>
                <a class="text-decoration-none text-dark" href="tentang.php">Tentang Kami</a>
                <a class="text-decoration-none text-dark" href="service.php">Service Kami</a>
                <a class="text-decoration-none text-primary" href="artikel.php">Artikel</a>
                <a class="text-decoration-none text-dark" href="kontak.php">Kontak Kami</a>

            </nav>
        </div>
    </header>

    <main class="py-5 flex-grow-1">
        <div class="container-fluid px-5">
            
            <div class="text-center mb-5">
                <span class="badge bg-primary mb-3 px-3 py-2 fs-6">Kabar & Informasi</span>
                <h1 class="fw-bold text-dark display-5">Artikel & Tips Perawatan</h1>
                <p class="lead text-muted">Temukan berbagai informasi menarik, berita promo, dan tips jitu merawat kendaraan Anda agar selalu tampil prima.</p>
            </div>

            <div class="row g-4">
                
                <?php if (count($articles) > 0): ?>
                    <?php foreach ($articles as $article): ?>
                        
                        <?php 
                            // Mendapatkan URL Gambar, jika tidak ada gambar maka gunakan gambar ilustrasi bawaan
                            $imgUrl = article_image_url($article['image']);
                            if (!$imgUrl) {
                                $imgUrl = 'assets/images/ilustrasi-cucimobil.png';
                            }
                            
                            // Format Tanggal yang lebih enak dibaca (Contoh: 12 Ags 2023)
                            $tanggalTampil = date('d M Y', strtotime($article['tanggal']));
                        ?>

                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-friendly bg-white">
                                <img src="<?= htmlspecialchars($imgUrl) ?>" class="card-img-top img-artikel-cover" alt="<?= htmlspecialchars($article['judul']) ?>">
                                
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center text-muted mb-3" style="font-size: 13px;">
                                        <span><i class="fas fa-calendar-alt text-primary me-1"></i> <?= $tanggalTampil ?></span>
                                        <span class="mx-2">|</span>
                                        <span><i class="fas fa-user text-primary me-1"></i> <?= htmlspecialchars($article['penulis']) ?></span>
                                    </div>
                                    
                                    <h4 class="card-title fw-bold mb-3"><?= htmlspecialchars($article['judul']) ?></h4>
                                    <p class="card-text text-muted" style="line-height: 1.6;">
                                        <?= htmlspecialchars(article_summary($article['isi'], 120)) ?>
                                    </p>
                                </div>
                                
                                <div class="card-footer bg-white border-0 p-4 pt-0 mt-auto">
                                    <a href="detail_artikel.php?id=<?= $article['id_artikel'] ?>" class="btn btn-outline-primary rounded-pill px-4 fw-semibold w-100">
                                        Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="bg-white rounded-4 shadow-sm p-5 d-inline-block mx-auto">
                            <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                            <h3 class="fw-bold text-dark">Belum ada artikel</h3>
                            <p class="text-muted">Nantikan berita dan tips menarik dari kami dalam waktu dekat!</p>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
            
        </div>
    </main>

    <footer class="site-footer bg-dark text-white py-4 mt-auto">
        <div class="container-fluid px-5 text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> Carwash Woosh. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>