<?php
require_once '../config/koneksi.php';
require_once '../config/cek_session.php';

// Statistik ringkas
$stmt = $pdo->prepare('SELECT COUNT(*) AS total_today FROM tb_transaksi WHERE tanggal_transaksi = CURDATE() AND deleted_at IS NULL');
$stmt->execute();
$today = $stmt->fetchColumn();

$today = $today ?: 0;

$stmt = $pdo->prepare('SELECT COALESCE(SUM(total_bayar), 0) AS revenue_month FROM tb_transaksi WHERE MONTH(tanggal_transaksi) = MONTH(CURDATE()) AND YEAR(tanggal_transaksi) = YEAR(CURDATE()) AND deleted_at IS NULL');
$stmt->execute();
$revenue_month = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM tb_pelanggan WHERE deleted_at IS NULL');
$stmt->execute();
$total_pelanggan = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM tb_transaksi WHERE status = :status AND deleted_at IS NULL');
$stmt->execute(['status' => 'proses']);
$total_proses = $stmt->fetchColumn();

// Transaksi terbaru
$stmt = $pdo->query('SELECT t.id_transaksi, p.nama AS pelanggan, k.plat_nomor, t.tanggal_transaksi, t.status, t.total_bayar
    FROM tb_transaksi t
    INNER JOIN tb_pelanggan p ON t.id_pelanggan = p.id_pelanggan
    INNER JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
    WHERE t.deleted_at IS NULL
    ORDER BY t.created_at DESC
    LIMIT 5');
$latest_transactions = $stmt->fetchAll();

$flash = get_flash_message();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - Carwash Woosh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(180deg, #eef4ff 0%, #ffffff 100%);
        }
        .hero-banner {
            position: relative;
            background: radial-gradient(circle at top, rgba(255,255,255,0.24), transparent 30%),
                linear-gradient(135deg, #0d6efd 0%, #1f8aff 45%, #33b5ff 100%);
            color: #fff;
            overflow: hidden;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
            opacity: 0.16;
            filter: blur(2px);
        }
        .hero-banner .container {
            position: relative;
            z-index: 2;
        }
        .hero-card {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.18);
            backdrop-filter: blur(10px);
        }
        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-weight: 800;
        }
        .hero-title span {
            color: #ffd43b;
        }
        .interactive-text {
            display: inline-flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 1rem;
        }
        .interactive-text span {
            padding: 0.55rem 1rem;
            border: 1px solid rgba(255,255,255,0.35);
            border-radius: 999px;
            transition: transform 0.25s ease, background 0.25s ease, color 0.25s ease;
            cursor: default;
        }
        .interactive-text span:hover,
        .interactive-text span.active {
            transform: translateY(-4px);
            background: rgba(255,255,255,0.18);
            color: #fff;
        }
        .car-icon {
            width: 200px;
            height: 200px;
            border-radius: 40px;
            background: rgba(255,255,255,0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            margin-top: 2rem;
            border: 1px solid rgba(255,255,255,0.24);
            box-shadow: 0 20px 60px rgba(13, 110, 253, 0.18);
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.05em;
        }
        .dashboard-pill {
            background: rgba(13,110,253,0.1);
            color: #0d6efd;
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary border-bottom border-white border-opacity-10">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">WashWoosh</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav"
                aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="topNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../Frontend/index.php">Company Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="pembayaran.php">Table</a></li>
                    <li class="nav-item"><a class="nav-link" href="company_profile.php">Form</a></li>
                    <li class="nav-item"><a class="nav-link" href="galeri.php">Artikel</a></li>
                    <li class="nav-item"><a class="nav-link" href="kontak_masuk.php">Kontak Masuk</a></li>
                </ul>
                <a class="btn btn-outline-light" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <main id="dashboard-content" class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div>
                <h2 class="h4 mb-1">Dashboard</h2>
                <p class="text-muted mb-0">Selamat datang, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>. Pantau transaksi dan performa layanan carwash Anda di sini.</p>
            </div>
            <div class="text-end">
                <span class="badge rounded-pill bg-primary">Tema Mobil</span>
            </div>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="hero-icon bg-primary text-white me-3">🚗</div>
                            <div>
                                <h6 class="mb-0 text-uppercase text-muted">Transaksi Hari Ini</h6>
                                <span class="h4 mb-0"><?= number_format($today) ?></span>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Jumlah transaksi yang dicatat hari ini.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="hero-icon bg-success text-white me-3">💰</div>
                            <div>
                                <h6 class="mb-0 text-uppercase text-muted">Pendapatan Bulan Ini</h6>
                                <span class="h4 mb-0">Rp <?= number_format($revenue_month, 0, ',', '.') ?></span>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Total pembayaran selama bulan berjalan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="hero-icon bg-warning text-white me-3">👥</div>
                            <div>
                                <h6 class="mb-0 text-uppercase text-muted">Pelanggan</h6>
                                <span class="h4 mb-0"><?= number_format($total_pelanggan) ?></span>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Jumlah pelanggan terdaftar aktif.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="hero-icon bg-info text-white me-3">🧼</div>
                            <div>
                                <h6 class="mb-0 text-uppercase text-muted">Transaksi Proses</h6>
                                <span class="h4 mb-0"><?= number_format($total_proses) ?></span>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Pesanan cuci mobil/motor yang sedang diproses.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Transaksi Terbaru</h5>
                    <small class="text-muted">Lima transaksi terakhir di sistem.</small>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Pelanggan</th>
                                <th>Plat Nomor</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Total Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($latest_transactions): ?>
                            <?php foreach ($latest_transactions as $index => $trx): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($trx['pelanggan']) ?></td>
                                    <td><?= htmlspecialchars($trx['plat_nomor']) ?></td>
                                    <td><?= htmlspecialchars($trx['tanggal_transaksi']) ?></td>
                                    <td>
                                        <?php
                                            $badge = 'secondary';
                                            if ($trx['status'] === 'antri') $badge = 'warning';
                                            if ($trx['status'] === 'proses') $badge = 'primary';
                                            if ($trx['status'] === 'selesai') $badge = 'success';
                                            if ($trx['status'] === 'batal') $badge = 'danger';
                                        ?>
                                        <span class="badge bg-<?= $badge ?> text-uppercase"><?= htmlspecialchars($trx['status']) ?></span>
                                    </td>
                                    <td>Rp <?= number_format($trx['total_bayar'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">Belum ada transaksi terbaru.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
