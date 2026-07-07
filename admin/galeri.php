<?php
require_once '../config/koneksi.php';
require_once '../config/cek_session.php';

try {
    $pdo->query('SELECT 1 FROM tb_artikel LIMIT 1');
} catch (PDOException $e) {
    $pdo->exec('CREATE TABLE IF NOT EXISTS tb_artikel (
        id_artikel INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        judul VARCHAR(255) NOT NULL,
        isi TEXT NOT NULL,
        gambar VARCHAR(255) DEFAULT NULL,
        penulis VARCHAR(100) NOT NULL,
        tanggal DATE NOT NULL,
        status ENUM("Draft","Publish") NOT NULL DEFAULT "Draft",
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

    $pdo->exec("INSERT INTO tb_artikel (judul, isi, gambar, penulis, tanggal, status) VALUES
        ('Tips Merawat Mobil Setelah Cuci', 'Jaga kilap mobil tetap maksimal dengan langkah sederhana setelah proses pencucian.', 'uploads/artikel-1.jpg', 'Admin WashWoosh', CURDATE(), 'Publish'),
        ('Kenapa Cuci Mobil Rutin Itu Penting', 'Cuci kendaraan secara rutin membantu menjaga warna cat tetap terjaga dan mencegah penumpukan kotoran.', 'uploads/artikel-2.jpg', 'Admin WashWoosh', CURDATE(), 'Draft')");
}

$flash = get_flash_message();

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$article = null;
if ($editId > 0) {
    $stmt = $pdo->prepare('SELECT * FROM tb_artikel WHERE id_artikel = :id LIMIT 1');
    $stmt->execute(['id' => $editId]);
    $article = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && !empty($_POST['id_artikel'])) {
        $id = (int)$_POST['id_artikel'];
        $stmt = $pdo->prepare('DELETE FROM tb_artikel WHERE id_artikel = :id');
        $stmt->execute(['id' => $id]);
        set_flash_message('Artikel berhasil dihapus', 'success');
        header('Location: galeri.php');
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'save') {
        $id = isset($_POST['id_artikel']) ? (int)$_POST['id_artikel'] : 0;
        $judul = trim($_POST['judul'] ?? '');
        $isi = trim($_POST['isi'] ?? '');
        $gambar = trim($_POST['gambar'] ?? '');
        $penulis = trim($_POST['penulis'] ?? '');
        $tanggal = trim($_POST['tanggal'] ?? date('Y-m-d'));
        $status = trim($_POST['status'] ?? 'Draft');

        if ($judul === '' || $isi === '' || $penulis === '') {
            set_flash_message('Judul, isi, dan penulis wajib diisi.', 'danger');
            header('Location: galeri.php' . ($id > 0 ? '?edit=' . $id : ''));
            exit;
        }

        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE tb_artikel SET judul = :judul, isi = :isi, gambar = :gambar, penulis = :penulis, tanggal = :tanggal, status = :status WHERE id_artikel = :id');
            $stmt->execute(['judul' => $judul, 'isi' => $isi, 'gambar' => $gambar, 'penulis' => $penulis, 'tanggal' => $tanggal, 'status' => $status, 'id' => $id]);
            set_flash_message('Artikel berhasil diperbarui', 'success');
        } else {
            $stmt = $pdo->prepare('INSERT INTO tb_artikel (judul, isi, gambar, penulis, tanggal, status) VALUES (:judul, :isi, :gambar, :penulis, :tanggal, :status)');
            $stmt->execute(['judul' => $judul, 'isi' => $isi, 'gambar' => $gambar, 'penulis' => $penulis, 'tanggal' => $tanggal, 'status' => $status]);
            set_flash_message('Artikel berhasil ditambahkan', 'success');
        }

        header('Location: galeri.php');
        exit;
    }
}

$stmt = $pdo->query('SELECT COUNT(*) FROM tb_artikel');
$total_artikel = $stmt->fetchColumn();

$stmt = $pdo->query('SELECT COUNT(*) FROM tb_artikel WHERE status = "Publish"');
$published = $stmt->fetchColumn();

$stmt = $pdo->query('SELECT COUNT(*) FROM tb_artikel WHERE status = "Draft"');
$draft_count = $stmt->fetchColumn();

$stmt = $pdo->query('SELECT * FROM tb_artikel ORDER BY created_at DESC');
$articles = $stmt->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Artikel - WashWoosh</title>
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

        .page-wrapper { min-height: 100vh; display: flex; background: var(--bg); }
        .sidebar {
            width: 270px;
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            color: #fff;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        .brand { display: flex; align-items: center; gap: 0.85rem; padding: 0.4rem 0.4rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.12); }
        .brand-icon { width: 48px; height: 48px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary) 0%, #3ab7ff 100%); font-size: 1.3rem; box-shadow: 0 10px 24px rgba(13, 110, 253, 0.25); }
        .brand h4 { margin: 0; font-size: 1.1rem; font-weight: 700; }
        .brand small { color: rgba(255,255,255,0.72); }
        .nav-links { display: flex; flex-direction: column; gap: 0.35rem; }
        .nav-links a { color: rgba(255,255,255,0.84); text-decoration: none; padding: 0.8rem 0.9rem; border-radius: 12px; display: flex; align-items: center; gap: 0.7rem; transition: all 0.2s ease; }
        .nav-links a:hover, .nav-links a.active { background: rgba(255,255,255,0.12); color: #fff; }
        .sidebar-footer { margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.12); }
        .main-panel { flex: 1; display: flex; flex-direction: column; }
        .topbar { background: var(--surface); border-bottom: 1px solid var(--border); padding: 1.2rem 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
        .topbar h2 { margin: 0; font-size: 1.25rem; font-weight: 700; }
        .topbar p { margin: 0.2rem 0 0; color: var(--muted); }
        .content { padding: 1.5rem; }
        .panel-card { background: var(--surface); border: 1px solid var(--border); border-radius: 18px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05); }
        .stat-card { border-radius: 16px; border: 1px solid var(--border); background: var(--surface); }
        .form-control, .form-select, .form-textarea { border-radius: 12px; }
        .article-preview { white-space: pre-wrap; }
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
            <a class="active" href="galeri.php"><i class="bi bi-journal-text"></i> Artikel</a>
            <a href="kotak_masuk.php"><i class="bi bi-envelope"></i> Kotak Masuk</a>
        </nav>
        <div class="sidebar-footer">
            <a class="btn btn-outline-light btn-sm w-100" href="../logout.php">Logout</a>
        </div>
    </aside>

    <div class="main-panel">
        <header class="topbar">
            <div>
                <h2>Manajemen Artikel</h2>
                <p>Tambah, edit, dan kelola artikel publikasi perusahaan Anda.</p>
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
                <div class="col-md-4">
                    <div class="stat-card p-3 h-100">
                        <h6 class="text-muted mb-1">Total Artikel</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($total_artikel) ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card p-3 h-100">
                        <h6 class="text-muted mb-1">Publish</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($published) ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card p-3 h-100">
                        <h6 class="text-muted mb-1">Draft</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($draft_count) ?></h3>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="panel-card p-3">
                        <h5 class="mb-3"><?= $article ? 'Edit Artikel' : 'Tambah Artikel Baru' ?></h5>
                        <form method="post">
                            <input type="hidden" name="action" value="save">
                            <?php if ($article): ?><input type="hidden" name="id_artikel" value="<?= (int)$article['id_artikel'] ?>">
                            <?php endif; ?>
                            <div class="mb-3">
                                <label class="form-label">Judul</label>
                                <input type="text" class="form-control" name="judul" value="<?= htmlspecialchars($article['judul'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Isi Artikel</label>
                                <textarea class="form-control" name="isi" rows="7" required><?= htmlspecialchars($article['isi'] ?? '') ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gambar (opsional)</label>
                                <input type="text" class="form-control" name="gambar" value="<?= htmlspecialchars($article['gambar'] ?? '') ?>" placeholder="uploads/artikel.jpg">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Penulis</label>
                                <input type="text" class="form-control" name="penulis" value="<?= htmlspecialchars($article['penulis'] ?? '') ?>" required>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal" value="<?= htmlspecialchars($article['tanggal'] ?? date('Y-m-d')) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="Draft" <?= (($article['status'] ?? 'Draft') === 'Draft') ? 'selected' : '' ?>>Draft</option>
                                        <option value="Publish" <?= (($article['status'] ?? 'Draft') === 'Publish') ? 'selected' : '' ?>>Publish</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Simpan Artikel</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="panel-card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Judul</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($articles): ?>
                                            <?php foreach ($articles as $index => $item): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td>
                                                        <strong><?= htmlspecialchars($item['judul']) ?></strong><br>
                                                        <small class="text-muted">Oleh <?= htmlspecialchars($item['penulis']) ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?= $item['status'] === 'Publish' ? 'bg-success' : 'bg-secondary' ?>"><?= htmlspecialchars($item['status']) ?></span>
                                                    </td>
                                                    <td><?= htmlspecialchars($item['tanggal']) ?></td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="galeri.php?edit=<?= (int)$item['id_artikel'] ?>" class="btn btn-outline-primary">Edit</a>
                                                            <form method="post" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');" style="display:inline;">
                                                                <input type="hidden" name="action" value="delete">
                                                                <input type="hidden" name="id_artikel" value="<?= (int)$item['id_artikel'] ?>">
                                                                <button type="submit" class="btn btn-outline-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">Belum ada artikel.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
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
