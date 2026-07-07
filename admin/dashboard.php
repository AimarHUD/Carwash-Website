<?php
require_once '../config/koneksi.php';
require_once '../config/cek_session.php';

function table_exists(PDO $pdo, string $tableName): bool
{
    try {
        $stmt = $pdo->prepare('SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = :table_name LIMIT 1');
        $stmt->execute(['table_name' => $tableName]);
        return (bool) $stmt->fetchColumn();
    } catch (PDOException $e) {
        return false;
    }
}

function safe_fetch_column(PDO $pdo, string $sql, array $params = []): int
{
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}

function safe_fetch_all(PDO $pdo, string $sql, array $params = []): array
{
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) {
        return [];
    }
}

$total_artikel = table_exists($pdo, 'tb_artikel')
    ? safe_fetch_column($pdo, 'SELECT COUNT(*) FROM tb_artikel')
    : 0;

$total_admin = table_exists($pdo, 'tb_admin')
    ? safe_fetch_column($pdo, "SELECT COUNT(*) FROM tb_admin WHERE level IN ('super', 'admin')")
    : 0;

$total_pesan_masuk = table_exists($pdo, 'tb_kontak')
    ? safe_fetch_column($pdo, 'SELECT COUNT(*) FROM tb_kontak WHERE deleted_at IS NULL')
    : 0;

$total_galeri = table_exists($pdo, 'tb_galeri')
    ? safe_fetch_column($pdo, 'SELECT COUNT(*) FROM tb_galeri WHERE deleted_at IS NULL')
    : 0;

$latest_articles = table_exists($pdo, 'tb_artikel')
    ? safe_fetch_all(
        $pdo,
        'SELECT id_artikel, judul, penulis, tanggal, status
         FROM tb_artikel
         ORDER BY tanggal DESC, created_at DESC
         LIMIT 5'
    )
    : [];

$latest_messages = table_exists($pdo, 'tb_kontak')
    ? safe_fetch_all(
        $pdo,
        'SELECT id_kontak, nama, email, pesan, created_at, status
         FROM tb_kontak
         WHERE deleted_at IS NULL
         ORDER BY created_at DESC
         LIMIT 5'
    )
    : [];

$current_admin_name = $_SESSION['nama_lengkap'] ?? 'Administrator';
$current_admin_role = $_SESSION['level'] ?? 'admin';
$current_admin_role_label = in_array($current_admin_role, ['super', 'admin'], true) ? 'Admin' : ucfirst($current_admin_role);
$login_at = $_SESSION['login_at'] ?? null;
$login_at_display = $login_at ? date('d M Y H:i', strtotime($login_at)) : 'Belum tersedia';
$current_datetime = date('d M Y H:i:s');

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
                <p>Selamat datang, <?= htmlspecialchars($current_admin_name) ?>. Pantau konten, pesan, dan informasi perusahaan Anda di sini.</p>
            </div>
            <div class="topbar-actions">
                <a href="../Frontend/index.php" class="btn-company" target="_blank">🌐 Buka Web Company</a>
                <span class="pill"><?= htmlspecialchars($current_admin_role_label) ?></span>
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
                            <div class="hero-icon bg-primary text-white me-3">📝</div>
                            <div>
                                <h6 class="mb-0 text-uppercase text-muted">Total Artikel</h6>
                                <span class="h4 mb-0"><?= number_format($total_artikel) ?></span>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Jumlah artikel yang tersimpan di database.</p>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="hero-icon bg-success text-white me-3">👤</div>
                            <div>
                                <h6 class="mb-0 text-uppercase text-muted">Total Admin</h6>
                                <span class="h4 mb-0"><?= number_format($total_admin) ?></span>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Jumlah akun admin yang aktif di sistem.</p>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="hero-icon bg-warning text-white me-3">✉️</div>
                            <div>
                                <h6 class="mb-0 text-uppercase text-muted">Total Pesan Masuk</h6>
                                <span class="h4 mb-0"><?= number_format($total_pesan_masuk) ?></span>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Pesan kontak yang tersimpan di kotak masuk.</p>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="hero-icon bg-info text-white me-3">🖼️</div>
                            <div>
                                <h6 class="mb-0 text-uppercase text-muted">Total Galeri</h6>
                                <span class="h4 mb-0"><?= number_format($total_galeri) ?></span>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Jumlah item galeri yang tersedia saat ini.</p>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Artikel Terbaru</h5>
                        <small class="text-muted">Lima artikel terbaru dari database.</small>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Judul Artikel</th>
                                    <th>Penulis</th>
                                    <th>Tanggal Publish</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if ($latest_articles): ?>
                                <?php foreach ($latest_articles as $index => $article): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($article['judul']) ?></td>
                                        <td><?= htmlspecialchars($article['penulis']) ?></td>
                                        <td><?= !empty($article['tanggal']) ? htmlspecialchars(date('d M Y', strtotime($article['tanggal']))) : '-' ?></td>
                                        <td>
                                            <?php
                                                $statusValue = $article['status'] ?? 'Draft';
                                                $badge = strtolower((string) $statusValue) === 'publish' ? 'success' : 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $badge ?> text-uppercase"><?= htmlspecialchars($statusValue) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">Belum ada artikel terbaru.</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-lg-6">
                    <div class="content-card h-100">
                        <div class="card-header bg-white border-0 px-4 py-3">
                            <h5 class="mb-0">Quick Action</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="galeri.php" class="btn btn-primary">Tambah Artikel</a>
                                <a href="company_profile.php" class="btn btn-outline-primary">Edit Company Profile</a>
                                <a href="galeri.php" class="btn btn-outline-secondary">Kelola Artikel</a>
                                <a href="../Frontend/index.php" target="_blank" class="btn btn-outline-success">Lihat Website</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="content-card h-100">
                        <div class="card-header bg-white border-0 px-4 py-3">
                            <h5 class="mb-0">Informasi Admin</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="text-muted" style="width: 40%;">Nama Admin</th>
                                            <td><?= htmlspecialchars($current_admin_name) ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Role</th>
                                            <td><?= htmlspecialchars($current_admin_role_label) ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Tanggal Login</th>
                                            <td><?= htmlspecialchars($login_at_display) ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Tanggal &amp; Jam Saat Ini</th>
                                            <td><?= htmlspecialchars($current_datetime) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-card mt-3">
                <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Pesan Masuk Terbaru</h5>
                        <small class="text-muted">Lima pesan terbaru dari form kontak.</small>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Tanggal</th>
                                    <th>Pesan</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if ($latest_messages): ?>
                                <?php foreach ($latest_messages as $index => $message): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($message['nama']) ?></td>
                                        <td><?= htmlspecialchars($message['email']) ?></td>
                                        <td><?= !empty($message['created_at']) ? htmlspecialchars(date('d M Y H:i', strtotime($message['created_at']))) : '-' ?></td>
                                        <td><?= htmlspecialchars(mb_strimwidth((string) $message['pesan'], 0, 80, '...')) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">Belum ada pesan masuk.</td>
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
