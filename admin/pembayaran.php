<?php
require_once '../config/koneksi.php';
require_once '../config/cek_session.php';

$search = trim($_GET['search'] ?? '');
$status = trim($_GET['status'] ?? '');

$stmt = $pdo->prepare('SELECT COUNT(*) AS total_transaksi, COALESCE(SUM(total_bayar), 0) AS total_revenue FROM tb_transaksi WHERE deleted_at IS NULL');
$stmt->execute();
$summary = $stmt->fetch();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM tb_transaksi WHERE status = :status AND deleted_at IS NULL');
$stmt->execute(['status' => 'proses']);
$pending = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM tb_transaksi WHERE status = :status AND deleted_at IS NULL');
$stmt->execute(['status' => 'selesai']);
$completed = $stmt->fetchColumn();

$query = 'SELECT t.id_transaksi, p.nama AS pelanggan, k.plat_nomor, t.tanggal_transaksi, t.status, t.total_bayar, t.metode_pembayaran
    FROM tb_transaksi t
    INNER JOIN tb_pelanggan p ON t.id_pelanggan = p.id_pelanggan
    INNER JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
    WHERE t.deleted_at IS NULL';
$params = [];

if ($search !== '') {
    $query .= ' AND (p.nama LIKE :search OR k.plat_nomor LIKE :search OR t.id_transaksi LIKE :search)';
    $params['search'] = '%' . $search . '%';
}

if ($status !== '') {
    $query .= ' AND t.status = :status';
    $params['status'] = $status;
}

$query .= ' ORDER BY t.created_at DESC';
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$transactions = $stmt->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Transaksi - WashWoosh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0d6efd;
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

        .brand h4 { margin: 0; font-size: 1.1rem; font-weight: 700; }
        .brand small { color: rgba(255,255,255,0.72); }

        .nav-links { display: flex; flex-direction: column; gap: 0.35rem; }
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
        .nav-links a:hover, .nav-links a.active {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }

        .sidebar-footer { margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.12); }
        .main-panel { flex: 1; display: flex; flex-direction: column; }
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 1.2rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        .topbar h2 { margin: 0; font-size: 1.25rem; font-weight: 700; }
        .topbar p { margin: 0.2rem 0 0; color: var(--muted); }
        .content { padding: 1.5rem; }
        .panel-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        }
        .stat-card { border-radius: 16px; border: 1px solid var(--border); background: var(--surface); }
        .table-shell { overflow: hidden; border-radius: 16px; }
        .table thead th { background: #f8fbff; }

        @media (max-width: 992px) {
            .page-wrapper { flex-direction: column; }
            .sidebar { width: 100%; }
            .nav-links { flex-direction: row; flex-wrap: wrap; }
            .nav-links a { flex: 1 1 180px; }
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
            <a href="company_profile.php"><i class="bi bi-building"></i> Company Profile</a>
            <a class="active" href="pembayaran.php"><i class="bi bi-table"></i> Table</a>
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
                <h2>Data Transaksi</h2>
                <p>Kelola seluruh transaksi pelanggan dengan tampilan yang lebih rapi dan modern.</p>
            </div>
        </header>

        <main class="content">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Transaksi</h6>
                                <h3 class="fw-bold mb-0"><?= number_format($summary['total_transaksi']) ?></h3>
                            </div>
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;">📋</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Pendapatan</h6>
                                <h3 class="fw-bold mb-0">Rp <?= number_format($summary['total_revenue'], 0, ',', '.') ?></h3>
                            </div>
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;">💰</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Sedang Diproses</h6>
                                <h3 class="fw-bold mb-0"><?= number_format($pending) ?></h3>
                            </div>
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;">🧼</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-card p-3 mb-4">
                <form method="get" class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">Cari pelanggan / plat / ID</label>
                        <input type="text" class="form-control" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Masukkan pencarian">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">Semua status</option>
                            <option value="antri" <?= $status === 'antri' ? 'selected' : '' ?>>Antri</option>
                            <option value="proses" <?= $status === 'proses' ? 'selected' : '' ?>>Proses</option>
                            <option value="selesai" <?= $status === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            <option value="batal" <?= $status === 'batal' ? 'selected' : '' ?>>Batal</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>

            <div class="panel-card">
                <div class="card-body p-0">
                    <div class="table-responsive table-shell">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Pelanggan</th>
                                    <th>Plat Nomor</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Metode</th>
                                    <th>Total Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($transactions): ?>
                                    <?php foreach ($transactions as $index => $trx): ?>
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
                                            <td><?= htmlspecialchars($trx['metode_pembayaran'] ?? '-') ?></td>
                                            <td>Rp <?= number_format($trx['total_bayar'], 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Tidak ada data transaksi yang sesuai.</td>
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
