<?php
require_once '../config/koneksi.php';
require_once '../config/cek_session.php';
$flash = get_flash_message();

$company = [
    'id' => null,
    'nama_perusahaan' => 'WashWoosh',
    'alamat' => '',
    'no_telp' => '',
    'email' => '',
    'jam_operasional' => '',
    'deskripsi' => '',
    'visi' => '',
    'misi' => '',
];

$stmt = $pdo->query('SELECT * FROM tb_company_profile ORDER BY updated_at DESC LIMIT 1');
$companyRow = $stmt->fetch();
if ($companyRow) {
    $company = array_merge($company, $companyRow);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_company_profile'])) {
    $data = [
        'nama_perusahaan' => trim($_POST['nama_perusahaan'] ?? ''),
        'alamat' => trim($_POST['alamat'] ?? ''),
        'no_telp' => trim($_POST['no_telp'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'jam_operasional' => trim($_POST['jam_operasional'] ?? ''),
        'deskripsi' => trim($_POST['deskripsi'] ?? ''),
        'visi' => trim($_POST['visi'] ?? ''),
        'misi' => trim($_POST['misi'] ?? ''),
    ];

    if ($data['nama_perusahaan'] === '' || $data['alamat'] === '' || $data['no_telp'] === '' || $data['email'] === '' || $data['jam_operasional'] === '') {
        set_flash_message('Nama perusahaan, alamat, telepon, email, dan jam operasional wajib diisi.', 'danger');
        header('Location: company_profile.php');
        exit;
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        set_flash_message('Format email tidak valid.', 'danger');
        header('Location: company_profile.php');
        exit;
    }

    try {
        if ($companyRow) {
            $stmt = $pdo->prepare('UPDATE tb_company_profile SET nama_perusahaan=:nama_perusahaan, alamat=:alamat, no_telp=:no_telp, email=:email, jam_operasional=:jam_operasional, deskripsi=:deskripsi, visi=:visi, misi=:misi WHERE id=:id');
            $stmt->execute([ ...$data, ':id' => $company['id'] ]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO tb_company_profile (nama_perusahaan, alamat, no_telp, email, jam_operasional, deskripsi, visi, misi) VALUES (:nama_perusahaan, :alamat, :no_telp, :email, :jam_operasional, :deskripsi, :visi, :misi)');
            $stmt->execute($data);
        }

        set_flash_message('Data company profile berhasil disimpan.', 'success');
    } catch (PDOException $e) {
        set_flash_message('Gagal menyimpan data company profile.', 'danger');
    }

    header('Location: company_profile.php');
    exit;
}
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

        .form-control, .form-select, .form-textarea {
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        .form-textarea {
            min-height: 110px;
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
                <h3><?= htmlspecialchars($company['nama_perusahaan']) ?> hadir untuk memberikan pengalaman cuci kendaraan yang cepat, bersih, dan nyaman.</h3>
                <p class="mb-0">Kelola profil perusahaan Anda dengan data yang langsung tampil di website frontend WashWoosh.</p>
            </section>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="panel-card h-100">
                        <div class="card-body">
                            <h5 class="section-title">Form Company Profile</h5>
                            <form method="post" action="company_profile.php">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Perusahaan</label>
                                        <input type="text" class="form-control" name="nama_perusahaan" value="<?= htmlspecialchars($company['nama_perusahaan']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">No. Telepon</label>
                                        <input type="text" class="form-control" name="no_telp" value="<?= htmlspecialchars($company['no_telp']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($company['email']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Jam Operasional</label>
                                        <input type="text" class="form-control" name="jam_operasional" value="<?= htmlspecialchars($company['jam_operasional']) ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Alamat</label>
                                        <textarea class="form-control" name="alamat" rows="3" required><?= htmlspecialchars($company['alamat']) ?></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="deskripsi" rows="3"><?= htmlspecialchars($company['deskripsi']) ?></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Visi</label>
                                        <textarea class="form-control" name="visi" rows="2"><?= htmlspecialchars($company['visi']) ?></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Misi</label>
                                        <textarea class="form-control" name="misi" rows="2"><?= htmlspecialchars($company['misi']) ?></textarea>
                                    </div>
                                </div>
                                <button type="submit" name="save_company_profile" class="btn btn-primary mt-4">Simpan Profil</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="panel-card mb-4">
                        <div class="card-body">
                            <h5 class="section-title">Preview Website</h5>
                            <p class="muted mb-2"><strong>Nama:</strong><br><?= htmlspecialchars($company['nama_perusahaan']) ?></p>
                            <p class="muted mb-2"><strong>Alamat:</strong><br><?= htmlspecialchars($company['alamat'] ?: '-') ?></p>
                            <p class="muted mb-2"><strong>Telepon:</strong><br><?= htmlspecialchars($company['no_telp'] ?: '-') ?></p>
                            <p class="muted mb-2"><strong>Email:</strong><br><?= htmlspecialchars($company['email'] ?: '-') ?></p>
                            <p class="muted mb-0"><strong>Jam Operasional:</strong><br><?= htmlspecialchars($company['jam_operasional'] ?: '-') ?></p>
                        </div>
                    </div>

                    <div class="panel-card">
                        <div class="card-body">
                            <h5 class="section-title">Informasi Singkat</h5>
                            <p class="muted mb-2">Data yang Anda simpan akan langsung tampil di halaman depan website WashWoosh.</p>
                            <ul class="feature-list">
                                <li>Branding perusahaan</li>
                                <li>Kontak dan jam operasional</li>
                                <li>Visi serta misi bisnis</li>
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
