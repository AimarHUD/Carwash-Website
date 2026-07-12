<?php
require_once 'includes/company_data.php';
require_once '../config/koneksi.php';
$current_page = basename($_SERVER['PHP_SELF']);

// Mengambil pesan flash (jika ada)
$flash = get_flash_message();

// Logika Pemrosesan Form Kontak
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_telp = trim($_POST['no_telp'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');
    $created_at = trim($_POST['created_at'] ?? '');

    if ($nama === '' || $email === '' || $no_telp === '' || $pesan === '') {
        set_flash_message('Semua field wajib diisi.', 'danger');
        header('Location: kontak.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash_message('Email tidak valid. Silakan periksa kembali.', 'danger');
        header('Location: kontak.php');
        exit;
    }

    $date = DateTime::createFromFormat('Y-m-d H:i:s', $created_at);
    if ($date === false) {
        $created_at = date('Y-m-d H:i:s');
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO tb_kontak (nama, email, no_telp, pesan, created_at) VALUES (:nama, :email, :no_telp, :pesan, :created_at)');
        $stmt->execute([
            ':nama' => $nama,
            ':email' => $email,
            ':no_telp' => $no_telp,
            ':pesan' => $pesan,
            ':created_at' => $created_at,
        ]);
        set_flash_message('Pesan berhasil dikirim. Terima kasih!', 'success');
    } catch (PDOException $e) {
        set_flash_message('Gagal mengirim pesan. Silakan coba lagi.', 'danger');
    }

    header('Location: kontak.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - Carwash Woosh</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        /* Latar Belakang Biru Muda yang Friendly */
        body {
            background-color: #add8e6 !important; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* Aksen Garis Biru pada Kartu Kontak */
        .card-friendly {
            border-top: 5px solid #0d6efd !important;
        }
    </style>
</head>
<body>
    <?php include 'includes/site_header.php'; ?>

    <main class="py-5 flex-grow-1">
        <div class="container-fluid px-5">
            
            <div class="text-center mb-5">
                <h1 class="fw-bold text-primary display-5">Hubungi Kami</h1>
                <p class="lead text-dark">Punya pertanyaan, keluhan, atau ingin reservasi? Tim <strong>Carwash Woosh</strong> siap membantu Anda dengan ramah!</p>
            </div>

            <div class="row g-5">
                <div class="col-lg-5">
                    <div class="card h-100 p-4 p-md-5 border-0 shadow-sm rounded-4 bg-white card-friendly">
                        <h3 class="fw-bold mb-4 text-dark">Informasi Layanan</h3>
                        <p class="text-muted mb-4">Jangan ragu untuk datang langsung ke outlet kami atau menghubungi kami melalui kontak di bawah ini.</p>
                        
                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-map-marker-alt fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Alamat Outlet</h5>
                                <p class="text-muted mb-0"><?= htmlspecialchars($company['alamat'] ?? '-') ?></p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-phone-alt fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Telepon / WhatsApp</h5>
                                <p class="text-muted mb-0"><?= htmlspecialchars($company['no_telp'] ?? '-') ?></p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-envelope fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Email</h5>
                                <p class="text-muted mb-0"><?= htmlspecialchars($company['email'] ?? '-') ?></p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-clock fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Jam Operasional</h5>
                                <p class="text-muted mb-0"><?= htmlspecialchars($company['jam_operasional'] ?? '-') ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card h-100 p-4 p-md-5 border-0 shadow-sm rounded-4 bg-white card-friendly">
                        <h3 class="fw-bold mb-4 text-dark">Kirim Pesan Cepat</h3>
                        
                        <?php if (!empty($flash)): ?>
                            <?php 
                                // Deteksi apakah get_flash_message() me-return string HTML atau array
                                if (is_array($flash)) {
                                    $msg_type = $flash['type'] ?? 'info';
                                    $msg_text = $flash['message'] ?? '';
                                } else {
                                    $msg_type = strpos($flash, 'danger') !== false ? 'danger' : 'success'; // fallback sederhana
                                    $msg_text = $flash; 
                                }
                            ?>
                            <div class="alert alert-<?= $msg_type ?> alert-dismissible fade show shadow-sm" role="alert">
                                <?= is_array($flash) ? htmlspecialchars($msg_text) : $msg_text ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-primary">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control form-control-lg bg-light border-0" placeholder="Masukkan nama Anda" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-primary">Nomor WhatsApp</label>
                                    <input type="text" name="no_telp" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: 0812..." required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-primary">Alamat Email</label>
                                <input type="email" name="email" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: nama@email.com" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-primary">Isi Pesan</label>
                                <textarea name="pesan" class="form-control form-control-lg bg-light border-0" rows="5" placeholder="Tuliskan pesan atau pertanyaan Anda di sini..." required></textarea>
                            </div>

                            <button type="submit" name="submit_contact" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Pesan Sekarang
                            </button>
                        </form>
                        
                    </div>
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