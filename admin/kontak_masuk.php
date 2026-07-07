<?php
require_once '../config/koneksi.php';
require_once '../config/cek_session.php';

// Auto-create table jika belum ada
try {
    $pdo->query('SELECT 1 FROM tb_kontak LIMIT 1');
} catch (PDOException $e) {
    // Tabel belum ada, buat sekarang
    $pdo->exec('CREATE TABLE IF NOT EXISTS tb_kontak (
        id_kontak INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        no_telp VARCHAR(25) NOT NULL,
        pesan TEXT NOT NULL,
        status ENUM("baru","dibaca") NOT NULL DEFAULT "baru",
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
    
    // Insert sample data
    $pdo->exec('INSERT INTO tb_kontak (nama, email, no_telp, pesan, status) VALUES
    ("Hari Purnomo", "hari.purnomo@email.com", "089876543210", "Halo, saya ingin menanyakan tentang paket cuci premium untuk mobil saya. Berapa harganya dan berapa lama prosesnya?", "baru"),
    ("Dewi Lestari", "dewi.lestari@email.com", "081234567890", "Saya ingin booking layanan cuci motor untuk hari Minggu pukul 10:00. Apakah tersedia?", "dibaca"),
    ("Roni Wijaya", "roni.wijaya@email.com", "082987654321", "Apakah ada diskon untuk member setia atau paket tahunan?", "baru")');
}

// Ambil pesan masuk
$stmt = $pdo->query('SELECT k.id_kontak, k.nama, k.email, k.no_telp, k.pesan, k.created_at, k.status
    FROM tb_kontak k
    WHERE k.deleted_at IS NULL
    ORDER BY k.created_at DESC');
$kontak_list = $stmt->fetchAll();

$flash = get_flash_message();

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id_kontak = (int)$_POST['id_kontak'];
    $stmt = $pdo->prepare('UPDATE tb_kontak SET deleted_at = NOW() WHERE id_kontak = :id');
    $stmt->execute(['id' => $id_kontak]);
    set_flash_message('success', 'Kontak berhasil dihapus');
    header('Location: kontak_masuk.php');
    exit;
}

// Handle mark as read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark_read') {
    $id_kontak = (int)$_POST['id_kontak'];
    $stmt = $pdo->prepare('UPDATE tb_kontak SET status = "dibaca" WHERE id_kontak = :id');
    $stmt->execute(['id' => $id_kontak]);
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kontak Masuk - WashWoosh</title>
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
        .kontak-item {
            border-left: 4px solid #0d6efd;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .kontak-item:hover {
            background-color: #f8f9fa !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .kontak-item.baru {
            background-color: #f0f8ff;
            border-left-color: #dc3545;
        }
        .badge-status {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
        }
        .icon-action {
            font-size: 0.9rem;
            cursor: pointer;
            transition: color 0.2s;
        }
        .icon-action:hover {
            color: #dc3545;
        }
        .kontak-detail {
            display: none;
        }
        .kontak-detail.show {
            display: block;
        }
        .info-badge {
            display: inline-block;
            padding: 0.5rem 0.75rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        .info-badge-label {
            font-weight: 600;
            color: #495057;
        }
        .pesan-content {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            border-left: 4px solid #0d6efd;
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.6;
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
                    <li class="nav-item"><a class="nav-link" href="galeri.php">Artikel</a></li>
                    <li class="nav-item"><a class="nav-link active" href="kontak_masuk.php">Kontak Masuk</a></li>
                </ul>
                <a class="btn btn-outline-light" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div>
                <h2 class="h4 mb-1">Kontak Masuk</h2>
                <p class="text-muted mb-0">Kelola pesan dan pertanyaan dari pengunjung website.</p>
            </div>
            <div class="text-end">
                <span class="badge rounded-pill bg-primary">Total: <?= count($kontak_list) ?></span>
            </div>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($kontak_list)): ?>
            <div class="alert alert-info text-center" role="alert">
                <p class="mb-0">📭 Tidak ada pesan masuk saat ini.</p>
            </div>
        <?php else: ?>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($kontak_list as $kontak): ?>
                            <div class="list-group-item kontak-item <?= $kontak['status'] === 'baru' ? 'baru' : '' ?>" data-bs-toggle="collapse" data-bs-target="#kontak-<?= $kontak['id_kontak'] ?>">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex gap-2 align-items-center mb-2">
                                            <h6 class="mb-0 fw-bold"><?= htmlspecialchars($kontak['nama']) ?></h6>
                                            <?php if ($kontak['status'] === 'baru'): ?>
                                                <span class="badge bg-danger">Baru</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Dibaca</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                ✉️ <?= htmlspecialchars($kontak['email']) ?>
                                                | 📱 <?= htmlspecialchars($kontak['no_telp']) ?>
                                            </small>
                                        </div>
                                        <p class="mb-0 text-secondary">
                                            <small><?= strlen($kontak['pesan']) > 100 ? substr(htmlspecialchars($kontak['pesan']), 0, 100) . '...' : htmlspecialchars($kontak['pesan']) ?></small>
                                        </p>
                                    </div>
                                    <div class="text-end ms-3">
                                        <small class="text-muted d-block mb-2"><?= date('d/m/Y H:i', strtotime($kontak['created_at'])) ?></small>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id_kontak" value="<?= $kontak['id_kontak'] ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">🗑️</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail Kontak -->
                                <div class="collapse mt-3" id="kontak-<?= $kontak['id_kontak'] ?>">
                                    <div class="kontak-detail show">
                                        <hr class="my-3">
                                        <div class="mb-3">
                                            <h6 class="fw-bold mb-2">Informasi Pengirim:</h6>
                                            <div>
                                                <div class="info-badge">
                                                    <span class="info-badge-label">Nama:</span> <?= htmlspecialchars($kontak['nama']) ?>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="info-badge">
                                                    <span class="info-badge-label">Email:</span> <a href="mailto:<?= htmlspecialchars($kontak['email']) ?>"><?= htmlspecialchars($kontak['email']) ?></a>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="info-badge">
                                                    <span class="info-badge-label">No. Telepon:</span> <a href="tel:<?= htmlspecialchars($kontak['no_telp']) ?>"><?= htmlspecialchars($kontak['no_telp']) ?></a>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="info-badge">
                                                    <span class="info-badge-label">Tanggal:</span> <?= date('d/m/Y H:i:s', strtotime($kontak['created_at'])) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="fw-bold mb-2">Pesan:</h6>
                                            <div class="pesan-content">
                                                <?= htmlspecialchars($kontak['pesan']) ?>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="mailto:<?= htmlspecialchars($kontak['email']) ?>" class="btn btn-primary btn-sm">
                                                ✉️ Balas Email
                                            </a>
                                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $kontak['no_telp']) ?>" target="_blank" class="btn btn-success btn-sm">
                                                💬 Hubungi via WhatsApp
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Mark as read saat detail dibuka
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(element => {
        element.addEventListener('click', function() {
            const targetId = this.getAttribute('data-bs-target');
            const badge = this.querySelector('.badge.bg-danger');
            if (badge) {
                // Mark as read
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'mark_read';
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id_kontak';
                idInput.value = this.getAttribute('data-bs-target').substring(8);
                form.appendChild(actionInput);
                form.appendChild(idInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
</script>
</body>
</html>
