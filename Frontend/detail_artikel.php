<?php
require_once 'includes/company_data.php';
require_once '../config/koneksi.php';
$current_page = 'artikel.php';

// 1. Ambil ID artikel dari URL (misal: detail_artikel.php?id=5)
$id_artikel = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Jika tidak ada ID di URL, kembalikan pengunjung ke halaman artikel
if ($id_artikel === 0) {
    header('Location: artikel.php');
    exit;
}

// 2. Query untuk mengambil data artikel yang spesifik dan berstatus Publish
$stmt = $pdo->prepare('SELECT * FROM tb_artikel WHERE id_artikel = :id AND status = "Publish"');
$stmt->execute([':id' => $id_artikel]);
$article = $stmt->fetch();

// 3. Fungsi untuk menangani gambar (sama seperti di halaman utama artikel)
function article_image_url(?string $path): ?string
{
    $path = trim((string) $path);
    if ($path === '') {
        return null;
    }
    return '../' . ltrim(str_replace('\\', '/', $path), '/');
}

$imgUrl = '';
$tanggalTampil = '';

if ($article) {
    $imgUrl = article_image_url($article['image']);
    if (!$imgUrl) {
        $imgUrl = 'assets/images/ilustrasi-cucimobil.png'; // Gambar fallback jika tidak ada
    }
    $tanggalTampil = date('d F Y', strtotime($article['tanggal']));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $article ? htmlspecialchars($article['judul']) : 'Artikel Tidak Ditemukan' ?> - Carwash Woosh</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        /* Desain Konsisten: Latar Biru Muda */
        body {
            background-color: #add8e6 !important; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* Styling spesifik untuk gambar artikel penuh */
        .img-artikel-detail {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 1rem;
        }

        .article-content {
            font-size: 16px;
            line-height: 1.8;
            color: #495057;
        }
    </style>
</head>
<body>
    <?php include 'includes/site_header.php'; ?>

    <main class="py-5 flex-grow-1">
        <div class="container-fluid px-5">
            
            <div class="row justify-content-center">
                <div class="col-lg-9 col-xl-8">
                    
                    <?php if ($article): ?>
                        
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5" style="border-top: 5px solid #0d6efd !important;">
                            
                            <a href="artikel.php" class="btn btn-outline-secondary btn-sm mb-4 d-inline-block rounded-pill fw-semibold" style="width: max-content;">
                                <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Artikel
                            </a>

                            <div class="mb-4">
                                <div class="d-flex align-items-center text-primary fw-semibold mb-2" style="font-size: 14px;">
                                    <span><i class="fas fa-calendar-alt me-1"></i> <?= $tanggalTampil ?></span>
                                    <span class="mx-3 text-muted">|</span>
                                    <span><i class="fas fa-user-edit me-1"></i> Ditulis oleh <?= htmlspecialchars($article['penulis']) ?></span>
                                </div>
                                <h1 class="fw-bold text-dark display-6"><?= htmlspecialchars($article['judul']) ?></h1>
                            </div>

                            <div class="mb-5 text-center">
                                <img src="<?= htmlspecialchars($imgUrl) ?>" class="img-fluid img-artikel-detail shadow-sm" alt="<?= htmlspecialchars($article['judul']) ?>">
                            </div>

                            <div class="article-content">
                                <?= nl2br(htmlspecialchars($article['isi'])) ?>
                            </div>

                        </div>

                    <?php else: ?>
                        
                        <div class="text-center py-5">
                            <div class="card border-0 shadow-sm rounded-4 bg-white p-5 d-inline-block mx-auto">
                                <i class="fas fa-search fa-4x text-muted mb-4"></i>
                                <h2 class="fw-bold text-dark">Artikel Tidak Ditemukan</h2>
                                <p class="text-muted mb-4">Maaf, artikel yang Anda cari mungkin telah dihapus atau belum dipublikasikan.</p>
                                <a href="artikel.php" class="btn btn-primary rounded-pill px-4">Kembali ke Halaman Artikel</a>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
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