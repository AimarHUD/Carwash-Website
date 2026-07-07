<?php
require_once '../config/koneksi.php';
require_once '../config/cek_session.php';
$flash = get_flash_message();
?>
<!doctype html>
<html lang="id">    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Company Profile - Carwash Dapa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/company_profile.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(180deg, #fbfdff 0%, #ffffff 100%); min-height:100vh; }
        .hero { background: linear-gradient(135deg,#0d6efd 0%, #33b5ff 100%); color:#fff; padding:3rem 0; border-radius:12px; }
        .card-soft { background: rgba(255,255,255,0.96); border: 1px solid #eee; box-shadow: 0 6px 20px rgba(13,110,253,0.04); }
    </style>
</head>
<body>
<div class="container py-4">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 border-bottom border-white border-opacity-10">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Carwash Dapa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav"
                    aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="topNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="company_profile.php">Company Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="pembayaran.php">Table</a></li>
                    <li class="nav-item"><a class="nav-link" href="galeri.php">Artikel</a></li>
                </ul>
                <a class="btn btn-outline-light" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <section class="hero p-4 mb-4 rounded">
        <div class="container text-center">
            <h1 class="display-6 fw-bold">Carwash Dapa</h1>
            <p class="lead mb-0">Solusi cepat, ramah lingkungan, dan profesional untuk semua kebutuhan cuci kendaraan Anda.</p>
        </div>
    </section>

    <div class="row g-3">
        <div class="col-md-8">
            <div class="card card-soft p-4">
                <h4>Tentang Kami</h4>
                <p class="text-muted">Carwash Dapa berdiri dengan tujuan memberikan layanan perawatan kendaraan berkualitas tinggi yang cepat dan terjangkau. Kami memadukan teknik manual terbaik dengan peralatan modern yang ramah lingkungan.</p>

                <h5 class="mt-4">Visi</h5>
                <p class="text-muted">Menjadi layanan cuci kendaraan pilihan utama di kawasan kami dengan standar kebersihan dan kepuasan pelanggan terbaik.</p>

                <h5 class="mt-4">Misi</h5>
                <ul>
                    <li>Menyediakan layanan cepat dan andal untuk semua jenis kendaraan.</li>
                    <li>Menggunakan produk yang aman bagi kendaraan dan lingkungan.</li>
                    <li>Memberikan pengalaman pelanggan yang ramah dan transparan.</li>
                </ul>

                <h5 class="mt-4">Layanan Unggulan</h5>
                <div class="row">
                    <div class="col-sm-6">
                        <ul>
                            <li>Cuci Eksterior</li>
                            <li>Cuci Interior</li>
                            <li>Salon dan Pengkilap</li>
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <ul>
                            <li>Waxing & Coating</li>
                            <li>Perawatan Aksesoris</li>
                            <li>Perawatan Mesin Ringan</li>
                        </ul>
                    </div>
                </div>

                <h5 class="mt-4">Keunggulan Kami</h5>
                <ul>
                    <li>Staff terlatih dan berpengalaman</li>
                    <li>Proses cepat dan efisien</li>
                    <li>Produk ramah lingkungan</li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-soft p-3 mb-3">
                <h6>Kontak</h6>
                <p class="mb-1"><strong>Alamat:</strong><br> Jl. Contoh No.123, Kecamatan Dapa</p>
                <p class="mb-1"><strong>Telepon:</strong><br> +62 812 3456 7890</p>
                <p class="mb-0"><strong>Email:</strong><br> info@carwashdapa.local</p>
            </div>

            <div class="card card-soft p-3 mb-3">
                <h6>Jam Operasional</h6>
                <ul class="mb-0">
                    <li>Senin - Jumat: 08:00 - 18:00</li>
                    <li>Sabtu: 08:00 - 16:00</li>
                    <li>Minggu: Tutup</li>
                </ul>
            </div>

            <div class="card card-soft p-3">
                <h6>Media Sosial</h6>
                <p class="mb-0">Ikuti kami di Instagram dan Facebook: <br>@carwashdapa</p>
            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
