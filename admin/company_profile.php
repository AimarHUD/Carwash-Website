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
    <title>Company Profile - WashWoosh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0d6efd;
            --primary-dark: #0b5ed7;
            --bg: #f4f8ff;
            --surface: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e5ecf6;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #f4f8ff 0%, #eef6ff 100%);
            color: var(--text);
            font-family: Inter, "Segoe UI", Roboto, Arial, sans-serif;
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            background: var(--bg);
        }

        .sidebar {
            width: 270px;
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            color: #fff;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.4rem 0.4rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary) 0%, #3ab7ff 100%);
            font-size: 1.3rem;
            box-shadow: 0 10px 24px rgba(13, 110, 253, 0.25);
        }

        .brand h4 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .brand small {
            color: rgba(255,255,255,0.72);
        }

        .nav-links {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .nav-links a {
            color: rgba(255,255,255,0.84);
            text-decoration: none;
            padding: 0.8rem 0.9rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            transition: all 0.2s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.12);
        }

        .main-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 1.2rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .topbar h2 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .topbar p {
            margin: 0.2rem 0 0;
            color: var(--muted);
        }

        .content {
            padding: 1.5rem;
        }

        .hero-card {
            background: linear-gradient(135deg, var(--primary) 0%, #38bdf8 100%);
            color: #fff;
            border-radius: 24px;
            padding: 1.6rem;
            box-shadow: 0 18px 40px rgba(13, 110, 253, 0.18);
            margin-bottom: 1.25rem;
        }

        .hero-card h3 {
            font-size: 1.7rem;
            font-weight: 700;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 0.8rem;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 999px;
            font-size: 0.9rem;
            margin-bottom: 0.7rem;
        }

        .panel-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        }

        .panel-card .card-body {
            padding: 1.2rem 1.25rem;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.6rem;
        }

        .muted {
            color: var(--muted);
        }

        .feature-list {
            padding-left: 1rem;
            margin: 0;
            color: var(--muted);
        }

        .feature-list li {
            margin-bottom: 0.4rem;
        }

        @media (max-width: 992px) {
            .page-wrapper {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
            }
            .nav-links {
                flex-direction: row;
                flex-wrap: wrap;
            }
            .nav-links a {
                flex: 1 1 180px;
            }
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">🚗</div>
            <div>
                <h4>WashWoosh</h4>
                <small>Admin Panel</small>
            </div>
        </div>

        <nav class="nav-links">
            <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a class="active" href="company_profile.php"><i class="bi bi-building"></i> Company Profile</a>
            <a href="galeri.php"><i class="bi bi-journal-text"></i> Artikel</a>
            <a href="kotak_masuk.php"><i class="bi bi-envelope"></i> Kotak Masuk</a>
        </nav>

        <div class="sidebar-footer">
            <a class="btn btn-outline-light btn-sm w-100" href="../logout.php">Logout</a>
        </div>
    </aside>

    <div class="main-panel">
        <header class="topbar">
            <div>
                <h2>Company Profile</h2>
                <p>Kelola dan tampilkan identitas perusahaan secara profesional.</p>
            </div>
        </header>

        <main class="content">
            <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show mb-4" role="alert">
                    <?= htmlspecialchars($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <section class="hero-card">
                <div class="hero-badge">✨ Layanan cuci kendaraan modern</div>
                <h3>WashWoosh hadir untuk memberikan pengalaman cuci kendaraan yang cepat, bersih, dan nyaman.</h3>
                <p class="mb-0">Kami menggabungkan teknologi, ketelitian, dan pelayanan ramah untuk menjaga kendaraan Anda tampil terbaik di setiap kunjungan.</p>
            </section>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="panel-card h-100">
                        <div class="card-body">
                            <h5 class="section-title">Tentang Kami</h5>
                            <p class="muted mb-3">WashWoosh berdiri dengan tujuan memberikan layanan perawatan kendaraan berkualitas tinggi yang cepat dan terjangkau. Kami memadukan teknik manual terbaik dengan peralatan modern dan produk yang aman bagi kendaraan serta lingkungan.</p>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <div class="panel-card">
                                        <div class="card-body">
                                            <h6 class="section-title">Visi</h6>
                                            <p class="muted mb-0">Menjadi layanan cuci kendaraan pilihan utama di kawasan kami dengan standar kebersihan dan kepuasan pelanggan terbaik.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="panel-card">
                                        <div class="card-body">
                                            <h6 class="section-title">Misi</h6>
                                            <ul class="feature-list">
                                                <li>Menyediakan layanan cepat dan andal</li>
                                                <li>Menggunakan produk ramah lingkungan</li>
                                                <li>Memberikan pelayanan yang transparan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="panel-card mb-4">
                        <div class="card-body">
                            <h5 class="section-title">Kontak</h5>
                            <p class="muted mb-2"><strong>Alamat:</strong><br>Jl. Contoh No.123, Kecamatan Pesanggrahan</p>
                            <p class="muted mb-2"><strong>Telepon:</strong><br>+62 875325539872</p>
                            <p class="muted mb-0"><strong>Email:</strong><br>info@washwoosh.com</p>
                        </div>
                    </div>

                    <div class="panel-card">
                        <div class="card-body">
                            <h5 class="section-title">Layanan Unggulan</h5>
                            <ul class="feature-list">
                                <li>Cuci eksterior & interior</li>
                                <li>Salon dan pengkilap</li>
                                <li>Waxing & coating</li>
                                <li>Perawatan mesin ringan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
