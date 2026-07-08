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


     <!-- Informasi Tambahan -->
        <section class="container-fluid px-5 py-4 mb-5">
            <div class="row g-4">
                
                <!-- Bagian "Kenapa Carwash Woosh?" yang diperbarui menjadi lebih menarik -->
                <div class="col-md-6">
                    <!-- Menambahkan aksen garis biru di sebelah kiri card -->
                    <div class="card h-100 p-4 border-0 shadow-sm rounded-4" style="border-left: 6px solid #0d6efd !important;">
                        
                        <div class="d-flex align-items-center mb-3">
                            <!-- Menambahkan Ikon Mobil Mengkilap -->
                            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-car fs-4"></i>
                            </div>
                            <h3 class="fw-bold mb-0">Kenapa Carwash Woosh?</h3>
                        </div>
                        
                        <p class="text-muted mb-4" style="font-size: 20px; line-height: 1.7;">
                            Kami menghadirkan lebih dari sekadar layanan cuci biasa. Dengan standar kualitas tinggi, kami memastikan setiap kendaraan yang keluar dari tempat kami tampil memukau dan dalam kondisi prima.
                        </p>
                        
                        <!-- Poin-poin tambahan agar lebih visual -->
                        <div class="mt-auto">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle text-success me-2 fs-5"></i> 
                                <span class="fw-semibold text-dark">Pengerjaan Cepat & Detail</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle text-success me-2 fs-5"></i> 
                                <span class="fw-semibold text-dark">Bahan Premium yang Aman</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2 fs-5"></i> 
                                <span class="fw-semibold text-dark">Ditangani Tenaga Profesional</span>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <!-- Bagian Informasi Kontak (Tetap di sebelah kanan) -->
                <div class="col-md-6">
                    <div class="card h-100 p-4 border-0 shadow-sm rounded-4">
                        <h3 class="fw-bold mb-4">Informasi Kontak</h3>
                        <p class="mb-3"><i class="fas fa-map-marker-alt text-primary me-2"></i> <strong>Alamat:</strong> <br><span class="ms-4 text-muted"><?= htmlspecialchars($company['alamat'] ?? '-') ?></span></p>
                        <p class="mb-3"><i class="fas fa-phone-alt text-primary me-2"></i> <strong>Telepon:</strong> <br><span class="ms-4 text-muted"><?= htmlspecialchars($company['no_telp'] ?? '-') ?></span></p>
                        <p class="mb-3"><i class="fas fa-envelope text-primary me-2"></i> <strong>Email:</strong> <br><span class="ms-4 text-muted"><?= htmlspecialchars($company['email'] ?? '-') ?></span></p>
                        <p class="mb-0"><i class="fas fa-clock text-primary me-2"></i> <strong>Jam Operasional:</strong> <br><span class="ms-4 text-muted"><?= htmlspecialchars($company['jam_operasional'] ?? '-') ?></span></p>
                    </div>
                </div>

            </div>
        </section>

    <footer class="site-footer bg-dark text-white py-4 mt-auto">
        <div class="container-fluid px-5 text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> Carwash Woosh. Semua hak dilindungi.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>