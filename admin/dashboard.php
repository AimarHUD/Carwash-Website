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

        .topbar-actions {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .pill {
            padding: 0.55rem 0.8rem;
            border-radius: 999px;
            background: #eaf3ff;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.92rem;
        }

        .btn-company {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.7rem 1rem;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--primary) 0%, #2fb7ff 100%);
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            box-shadow: 0 10px 24px rgba(13, 110, 253, 0.2);
        }

        .content {
            padding: 1.5rem;
        }

        .content-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        }

        .stat-card {
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--surface);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        }

        .hero-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
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
            <a class="active" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="company_profile.php"><i class="bi bi-building"></i> Company Profile</a>
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
                <h2>Dashboard Admin</h2>
                <p>Selamat datang, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>. Pantau transaksi dan performa layanan carwash Anda di sini.</p>
            </div>
            <div class="topbar-actions">
                <a href="../Frontend/index.php" class="btn-company" target="_blank">🌐 Buka Web Company</a>
                <span class="pill">Admin</span>
                <span class="pill"><?= date('d M Y') ?></span>
            </div>
        </header>

        <main class="content">
            <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show mb-4" role="alert">
                    <?= htmlspecialchars($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row g-3 mb-4">
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
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
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
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
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
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
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
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

            <div class="content-card">
                <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center">
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
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
