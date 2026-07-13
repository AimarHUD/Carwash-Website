<?php
require_once 'includes/company_data.php';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Mengubah Title menjadi Carwash Woosh -->
    <title>Carwash Woosh - Company Profile</title>
    
    <!-- Memuat Bootstrap CSS untuk layout yang lebih lebar dan responsif -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Memuat FontAwesome untuk Ikon Keunggulan Kami -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        /* Mengubah Latar Belakang menjadi Biru Muda */
        body {
            background-color: #add8e6 !important; 
        }
        
        /* Menyesuaikan Header */
        .site-header {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php include 'includes/site_header.php'; ?>

    <main>
        <!-- Hero Section dengan Slogan, Deskripsi, dan Gambar -->
        <section class="hero py-5">
            <div class="container-fluid px-5">
                <div class="row align-items-center">
                    <div class="col-md-6 pe-lg-5">
                        <span class="badge bg-primary mb-3 px-3 py-2 fs-6">Layanan cuci kendaraan modern</span>
                        <h1 class="display-4 fw-bold mb-3">Bersih, Cepat, dan Mengkilap!</h1>
                        <!-- Mengubah sebutan di dalam teks menjadi Carwash Woosh -->
                        <p class="lead mb-4">Selamat datang di <strong>Carwash Woosh</strong>. Solusi perawatan mobil terbaik Anda dengan layanan profesional, cepat, dan menggunakan bahan berkualitas untuk menjaga penampilan kendaraan Anda tetap prima.</p>
                        <div class="hero-actions">
                            <a class="btn btn-primary btn-lg me-2 px-4" href="service.php">Service Kami</a>
                            <a class="btn btn-outline-dark btn-lg px-4 bg-white" href="kontak.php">Hubungi Kami</a>
                        </div>
                    </div>
                    <div class="col-md-6 text-center mt-5 mt-md-0">
                        <!-- Gambar .png terkait pencucian mobil -->
                        <img src="assets/images/99cdfa217cf61968e07f8d5c1d499692.jpg" class="img-fluid" alt="Ilustrasi Cuci Mobil Carwash Woosh">
                    </div>
                </div>
            </div>
        </section>

       <!-- Section Keunggulan Kami -->
        <section class="features d-block container-fluid px-5 py-5 text-center bg-white my-4 shadow-sm rounded-4 w-100 mx-auto">
            
            <!-- Judul di atas dan di tengah -->
            <h2 class="fw-bold mb-5 w-100 text-center">Keunggulan Kami</h2>

            <!-- Poin-poin di bawahnya dan di tengah -->
            <div class="row justify-content-center w-100 mx-auto">
                
                <div class="col-md col-sm-6 px-3 mb-4">
                    <i class="fas fa-users-cog fa-3x mb-3 text-primary"></i>
                    <h5 class="text-uppercase fw-bold" style="font-size: 20px;">Professional Carwash and Autodetailing</h5>
                    <p class="text-muted" style="font-size: 14px;">Carwash Woosh memiliki pengalaman lebih dari 10 tahun di Bidang Cuci mobil dan detailing mobil, dengan memiliki sistem manajemen yang terstruktur.</p>
                </div>
                
                <div class="col-md col-sm-6 px-3 mb-4">
                    <i class="fas fa-award fa-3x mb-3 text-primary"></i>
                    <h5 class="text-uppercase fw-bold" style="font-size: 20px;">Top Quality Products</h5>
                    <p class="text-muted" style="font-size: 14px;">Carwash Woosh menggunakan bahan - bahan yang teruji kualitasnya dan aman bagi lingkungan.</p>
                </div>
                
                <div class="col-md col-sm-6 px-3 mb-4">
                    <i class="fas fa-chalkboard-teacher fa-3x mb-3 text-primary"></i>
                    <h5 class="text-uppercase fw-bold" style="font-size: 20px;">Training Method</h5>
                    <p class="text-muted" style="font-size: 14px;">Carwash Woosh memiliki metode pelatihan tenaga kerja untuk memastikan bahwa pengalaman yang diterima oleh konsumen memuaskan.</p>
                </div>
                
                <div class="col-md col-sm-6 px-3 mb-4">
                    <i class="fas fa-video fa-3x mb-3 text-primary"></i>
                    <h5 class="text-uppercase fw-bold" style="font-size: 20px;">Security System</h5>
                    <p class="text-muted" style="font-size: 14px;">Di setiap outlet Carwash Woosh memiliki CCTV dan Pos yang dikelola secara mandiri dan professional.</p>
                </div>
                
                <div class="col-md col-sm-6 px-3 mb-4">
                    <i class="fas fa-bullhorn fa-3x mb-3 text-primary"></i>
                    <h5 class="text-uppercase fw-bold" style="font-size: 20px;">Promotion</h5>
                    <p class="text-muted" style="font-size: 14px;">Carwash Woosh memberikan promo menarik kepada para pelanggan di setiap bulannya.</p>
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