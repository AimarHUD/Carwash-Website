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
    <title>Kotak Masuk - WashWoosh</title>
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
        .message-card { border-left: 4px solid var(--primary); border-radius: 14px; padding: 1rem; background: #fcfdff; transition: transform 0.2s ease, box-shadow 0.2s ease; cursor: pointer; }
        .message-card:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08); }
        .message-card.unread { border-left-color: #dc3545; background: #fef7f7; }
        .info-badge { display: inline-block; padding: 0.45rem 0.65rem; background: #f4f8ff; border-radius: 999px; margin-right: 0.35rem; margin-bottom: 0.35rem; font-size: 0.85rem; color: var(--muted); }
        .pesan-content { background: #f8fbff; padding: 1rem; border-radius: 12px; border-left: 4px solid var(--primary); white-space: pre-wrap; word-wrap: break-word; line-height: 1.6; }
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
            <a href="galeri.php"><i class="bi bi-journal-text"></i> Artikel</a>
            <a class="active" href="kotak_masuk.php"><i class="bi bi-envelope"></i> Kotak Masuk</a>
        </nav>
        <div class="sidebar-footer">
            <a class="btn btn-outline-light btn-sm w-100" href="../logout.php">Logout</a>
        </div>
    </aside>

    <div class="main-panel">
        <header class="topbar">
            <div>
                <h2>Kotak Masuk</h2>
                <p>Kelola pesan pelanggan dengan tampilan yang lebih modern dan informatif.</p>
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
                <div class="col-md-6">
                    <div class="stat-card p-3 h-100">
                        <h6 class="text-muted mb-1">Total Pesan</h6>
                        <h3 class="fw-bold mb-0"><?= count($kontak_list) ?></h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card p-3 h-100">
                        <h6 class="text-muted mb-1">Pesan Baru</h6>
                        <h3 class="fw-bold mb-0"><?= count(array_filter($kontak_list, fn($item) => $item['status'] === 'baru')) ?></h3>
                    </div>
                </div>
            </div>

            <?php if (empty($kontak_list)): ?>
                <div class="panel-card p-4 text-center">
                    <h5 class="mb-2">Belum ada pesan masuk</h5>
                    <p class="text-muted mb-0">Pesan dari pelanggan akan muncul di sini.</p>
                </div>
            <?php else: ?>
                <div class="panel-card p-3">
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($kontak_list as $kontak): ?>
                            <div class="message-card <?= $kontak['status'] === 'baru' ? 'unread' : '' ?>" data-bs-toggle="collapse" data-bs-target="#kontak-<?= $kontak['id_kontak'] ?>">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <h6 class="mb-0 fw-bold"><?= htmlspecialchars($kontak['nama']) ?></h6>
                                            <?php if ($kontak['status'] === 'baru'): ?>
                                                <span class="badge bg-danger">Baru</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Dibaca</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mb-2">
                                            <span class="info-badge">✉️ <?= htmlspecialchars($kontak['email']) ?></span>
                                            <span class="info-badge">📱 <?= htmlspecialchars($kontak['no_telp']) ?></span>
                                        </div>
                                        <p class="mb-0 text-muted"><?= htmlspecialchars(substr($kontak['pesan'], 0, 120)) ?><?= strlen($kontak['pesan']) > 120 ? '...' : '' ?></p>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block mb-2"><?= date('d/m/Y H:i', strtotime($kontak['created_at'])) ?></small>
                                        <form method="post" onsubmit="return confirm('Yakin ingin menghapus pesan ini?');" style="display:inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_kontak" value="<?= (int)$kontak['id_kontak'] ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="collapse mt-3" id="kontak-<?= $kontak['id_kontak'] ?>">
                                    <hr>
                                    <div class="mb-3">
                                        <h6 class="fw-bold mb-2">Informasi Pengirim</h6>
                                        <div class="info-badge">Nama: <?= htmlspecialchars($kontak['nama']) ?></div>
                                        <div class="info-badge">Email: <a href="mailto:<?= htmlspecialchars($kontak['email']) ?>"><?= htmlspecialchars($kontak['email']) ?></a></div>
                                        <div class="info-badge">Telepon: <a href="tel:<?= htmlspecialchars($kontak['no_telp']) ?>"><?= htmlspecialchars($kontak['no_telp']) ?></a></div>
                                        <div class="info-badge">Tanggal: <?= date('d/m/Y H:i:s', strtotime($kontak['created_at'])) ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <h6 class="fw-bold mb-2">Pesan</h6>
                                        <div class="pesan-content"><?= htmlspecialchars($kontak['pesan']) ?></div>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="mailto:<?= htmlspecialchars($kontak['email']) ?>" class="btn btn-primary btn-sm">Balas Email</a>
                                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $kontak['no_telp']) ?>" target="_blank" class="btn btn-success btn-sm">Hubungi WhatsApp</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(element => {
        element.addEventListener('click', function() {
            const badge = this.querySelector('.badge.bg-danger');
            if (badge) {
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
