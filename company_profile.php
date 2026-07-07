<?php
// Public Company Profile - tidak memerlukan autentikasi
?>
<!doctype html>
<html lang="id">    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Company Profile - Carwash Dapa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/company_profile.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 shadow-sm rounded">
        <div class="container-fluid">
            <a class="navbar-brand brand" href="index.php">Carwash Dapa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"
                    aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link active" href="company_profile.php">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Masuk</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <a class="btn btn-outline-primary me-2" href="login.php">Booking</a>
                    <a class="btn btn-primary" href="contact.php">Kontak</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero text-center mb-4">
        <div class="container">
            <h1 class="display-5 fw-bold">Carwash Dapa</h1>
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

    <footer class="text-center mt-5 text-muted">
        &copy; <?= date('Y') ?> Carwash Dapa. Semua hak dilindungi.
    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
