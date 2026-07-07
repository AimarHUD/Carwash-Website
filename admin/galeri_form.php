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

$id_artikel = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$mode = $id_artikel ? 'edit' : 'create';
$error = '';
$judul = '';
$isi = '';
$penulis = '';
$tanggal = date('Y-m-d');
$status = 'Draft';
$gambar = null;

if ($mode === 'edit') {
    $stmt = $pdo->prepare('SELECT * FROM tb_artikel WHERE id_artikel = :id LIMIT 1');
    $stmt->execute(['id' => $id_artikel]);
    $artikel = $stmt->fetch();

    if ($artikel) {
        $judul = $artikel['judul'];
        $isi = $artikel['isi'];
        $penulis = $artikel['penulis'];
        $tanggal = $artikel['tanggal'];
        $status = $artikel['status'];
        $gambar = $artikel['gambar'];
    } else {
        header('Location: galeri.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $isi = trim($_POST['isi'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? date('Y-m-d'));
    $status = $_POST['status'] === 'Publish' ? 'Publish' : 'Draft';
    $existingGambar = trim($_POST['existing_gambar'] ?? '');

    if ($judul === '' || $isi === '' || $penulis === '') {
        $error = 'Judul, isi, dan penulis wajib diisi.';
    } else {
        $uploadPath = '../assets/img/artikel/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $imagePath = $existingGambar;
        if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            $fileType = $_FILES['gambar']['type'];
            $fileTmp = $_FILES['gambar']['tmp_name'];
            $fileName = basename($_FILES['gambar']['name']);
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $targetFile = uniqid('artikel_', true) . '.' . $extension;
            $destination = $uploadPath . $targetFile;

            if (!in_array($fileType, $allowedTypes, true)) {
                $error = 'Format gambar tidak valid. Gunakan JPG, PNG, atau WEBP.';
            } elseif (!move_uploaded_file($fileTmp, $destination)) {
                $error = 'Gagal mengunggah gambar. Silakan coba lagi.';
            } else {
                $imagePath = '../assets/img/artikel/' . $targetFile;
            }
        }

        if ($error === '') {
            if ($mode === 'edit') {
                $stmt = $pdo->prepare('UPDATE tb_artikel SET judul = :judul, isi = :isi, gambar = :gambar, penulis = :penulis, tanggal = :tanggal, status = :status WHERE id_artikel = :id');
                $stmt->execute([
                    ':judul' => $judul,
                    ':isi' => $isi,
                    ':gambar' => $imagePath,
                    ':penulis' => $penulis,
                    ':tanggal' => $tanggal,
                    ':status' => $status,
                    ':id' => $id_artikel,
                ]);
                set_flash_message('Artikel berhasil diperbarui.', 'success');
            } else {
                $stmt = $pdo->prepare('INSERT INTO tb_artikel (judul, isi, gambar, penulis, tanggal, status) VALUES (:judul, :isi, :gambar, :penulis, :tanggal, :status)');
                $stmt->execute([
                    ':judul' => $judul,
                    ':isi' => $isi,
                    ':gambar' => $imagePath,
                    ':penulis' => $penulis,
                    ':tanggal' => $tanggal,
                    ':status' => $status,
                ]);
                set_flash_message('Artikel berhasil ditambahkan.', 'success');
            }
            header('Location: galeri.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $mode === 'edit' ? 'Edit Artikel' : 'Tambah Artikel' ?> - WashWoosh</title>
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
        .form-label {
            font-weight: 600;
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
                <h2 class="h4 mb-1"><?= $mode === 'edit' ? 'Edit Artikel' : 'Tambah Artikel' ?></h2>
                <p class="text-muted mb-0">Isi data artikel dan simpan agar tampil di frontend.</p>
            </div>
            <div class="text-end">
                <a href="galeri.php" class="btn btn-secondary">Kembali ke Daftar</a>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="judul">Judul Artikel</label>
                        <input type="text" id="judul" name="judul" class="form-control" value="<?= htmlspecialchars($judul) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="isi">Isi Artikel</label>
                        <textarea id="isi" name="isi" rows="10" class="form-control" required><?= htmlspecialchars($isi) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="penulis">Penulis</label>
                        <input type="text" id="penulis" name="penulis" class="form-control" value="<?= htmlspecialchars($penulis) ?>" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label" for="tanggal">Tanggal Publikasi</label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= htmlspecialchars($tanggal) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="Draft" <?= $status === 'Draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="Publish" <?= $status === 'Publish' ? 'selected' : '' ?>>Publish</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="gambar">Gambar Artikel</label>
                            <input type="file" id="gambar" name="gambar" class="form-control">
                        </div>
                    </div>

                    <?php if ($gambar): ?>
                        <div class="mb-3">
                            <label class="form-label">Gambar Saat Ini</label>
                            <div>
                                <img src="<?= htmlspecialchars($gambar) ?>" alt="Preview Gambar" class="img-fluid rounded" style="max-width: 320px;">
                            </div>
                        </div>
                    <?php endif; ?>

                    <input type="hidden" name="existing_gambar" value="<?= htmlspecialchars($gambar) ?>">
                    <button type="submit" class="btn btn-primary"><?= $mode === 'edit' ? 'Perbarui Artikel' : 'Simpan Artikel' ?></button>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
