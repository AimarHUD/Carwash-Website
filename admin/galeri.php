<?php
require_once '../config/koneksi.php';
require_once '../config/cek_session.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id_artikel = (int)$_POST['id_artikel'];
    $stmt = $pdo->prepare('DELETE FROM tb_artikel WHERE id_artikel = :id');
    $stmt->execute(['id' => $id_artikel]);

    set_flash_message('Artikel berhasil dihapus.', 'success');
    header('Location: galeri.php');
    exit;
}

$stmt = $pdo->query('SELECT * FROM tb_artikel ORDER BY tanggal DESC, created_at DESC');
$articles = $stmt->fetchAll();
$flash = get_flash_message();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Artikel - WashWoosh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(180deg, #eef4ff 0%, #ffffff 100%);
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.05em;
        }
        .hero-title {
            letter-spacing: 0.08em;
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
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../Frontend/index.php">Company Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="pembayaran.php">Table</a></li>
                    <li class="nav-item"><a class="nav-link" href="company_profile.php">Form</a></li>
                    <li class="nav-item"><a class="nav-link active" href="galeri.php">Artikel</a></li>
                    <li class="nav-item"><a class="nav-link" href="kontak_masuk.php">Kontak Masuk</a></li>
                </ul>
                <a class="btn btn-outline-light" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div>
                <h2 class="h4 mb-1">Daftar Artikel</h2>
                <p class="text-muted mb-0">Kelola artikel yang ditampilkan untuk halaman frontend.</p>
            </div>
            <div class="text-end">
                <a href="galeri_form.php" class="btn btn-primary">Tambah Artikel</a>
            </div>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($articles)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada artikel. Silakan tambahkan artikel baru.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($articles as $artikel): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($artikel['id_artikel']) ?></td>
                                        <td><?= htmlspecialchars($artikel['judul']) ?></td>
                                        <td><?= htmlspecialchars($artikel['penulis']) ?></td>
                                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($artikel['tanggal']))) ?></td>
                                        <td><?= htmlspecialchars($artikel['status']) ?></td>
                                        <td class="text-end">
                                            <a href="galeri_form.php?id=<?= htmlspecialchars($artikel['id_artikel']) ?>" class="btn btn-sm btn-outline-primary me-2">Edit</a>
                                            <form method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id_artikel" value="<?= htmlspecialchars($artikel['id_artikel']) ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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
