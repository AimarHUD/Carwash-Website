<?php
require_once '../config/koneksi.php';

$flash = get_flash_message();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_telp = trim($_POST['no_telp'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');
    $created_at = trim($_POST['created_at'] ?? '');

    if ($nama === '' || $email === '' || $no_telp === '' || $pesan === '') {
        set_flash_message('Semua field wajib diisi.', 'danger');
        header('Location: kontak.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash_message('Email tidak valid. Silakan periksa kembali.', 'danger');
        header('Location: kontak.php');
        exit;
    }

    $date = DateTime::createFromFormat('Y-m-d H:i:s', $created_at);
    if ($date === false) {
        $created_at = date('Y-m-d H:i:s');
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO tb_kontak (nama, email, no_telp, pesan, created_at) VALUES (:nama, :email, :no_telp, :pesan, :created_at)');
        $stmt->execute([
            ':nama' => $nama,
            ':email' => $email,
            ':no_telp' => $no_telp,
            ':pesan' => $pesan,
            ':created_at' => $created_at,
        ]);
        set_flash_message('Pesan berhasil dikirim. Terima kasih!', 'success');
    } catch (PDOException $e) {
        set_flash_message('Gagal mengirim pesan. Silakan coba lagi.', 'danger');
    }

    header('Location: kontak.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak WashWoosh</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="logo" href="index.php">WashWoosh</a>
            <nav class="site-nav">
                <a href="index.php">Home</a>
                <a href="tentang.php">Tentang Kita</a>
                <a href="service.php">Service Kita</a>
                <a href="artikel.php">Artikel</a>
                <a href="kontak.php">Kontak Kita</a>
            </nav>
        </div>
    </header>

    <main class="container content-wrapper">
        <section class="page-section page-section--wide">
            <h1>Kontak Kita</h1>
            <p>Silakan isi form di bawah untuk mengirim pesan ke tim WashWoosh. Pesan akan masuk ke dashboard admin dan tercatat sesuai waktu perangkat Anda.</p>
        </section>

        <?php if ($flash): ?>
            <div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : 'alert-danger' ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <div class="contact-grid">
            <div class="contact-card">
                <h2>Form Kontak</h2>
                <form method="POST" action="kontak.php" class="contact-form">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="no_telp">Nomor Telepon</label>
                    <input type="tel" id="no_telp" name="no_telp" required>

                    <label for="pesan">Pesan</label>
                    <textarea id="pesan" name="pesan" rows="6" required></textarea>

                    <input type="hidden" name="created_at" id="created_at">
                    <button type="submit" name="submit_contact" class="btn btn-primary">Kirim Pesan</button>
                </form>
            </div>

            <div class="contact-card contact-info">
                <h2>Informasi Kontak</h2>
                <div class="info-block">
                    <p><strong>Email:</strong> info@washwoosh.com</p>
                    <p><strong>Telepon:</strong> +62 812-3456-7890</p>
                    <p><strong>Alamat:</strong> Jl. Melati No. 12, Bandung, Jawa Barat</p>
                </div>

                <div class="info-block">
                    <h3>Lokasi Kami</h3>
                    <p>WashWoosh berada di pusat kota dengan akses mudah dari berbagai ruas jalan utama. Kunjungi kami untuk layanan cuci kendaraan profesional dan nyaman.</p>
                </div>

                <div class="map-card">
                    <p>Lokasi fisik:</p>
                    <p>Jl. Melati No. 12, Bandung</p>
                    <p>Koordinat: -6.9175, 107.6191</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; 2026 WashWoosh. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script>
        const createdAtInput = document.getElementById('created_at');
        if (createdAtInput) {
            const now = new Date();
            const pad = (value) => String(value).padStart(2, '0');
            const formatted = `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
            createdAtInput.value = formatted;
        }
    </script>
</body>
</html>
