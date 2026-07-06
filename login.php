<?php
require_once 'config/koneksi.php';

if (!empty($_SESSION['id_admin'])) {
    header('Location: admin/dashboard.php');
    exit;
}

$error = '';
if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password harus diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM tb_admin WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['id_admin'] = $admin['id_admin'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['nama_lengkap'] = $admin['nama_lengkap'];
            $_SESSION['level'] = $admin['level'];

            header('Location: admin/dashboard.php');
            exit;
        }

        $error = 'Username atau password salah.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - Carwash Dapa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
            background: #000;
            color: #fff;
        }
        .video-background {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            background: #000;
        }
        .video-background img {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            transform: translate(-50%, -50%);
            object-fit: cover;
            opacity: 0.27;
            pointer-events: none;
        }
        .video-overlay {
            position: fixed;
            inset: 0;
            z-index: 1;
            background: linear-gradient(180deg, rgba(13,110,253,0.45), rgba(0,0,0,0.7));
        }
        .page-wrap {
            position: relative;
            z-index: 2;
            min-height: 100vh;
        }
        .login-shell {
            max-width: 400px;
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 28px;
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.18);
        }
        .login-shell .card-body {
            padding: 3rem;
        }
        .login-title {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 0.08em;
        }
        .login-subtitle {
            color: rgba(255,255,255,0.78);
        }
        .form-control {
            border-radius: 14px;
            background: rgba(255,255,255,0.12);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.18);
        }
        .form-control:focus {
            background: rgba(255,255,255,0.18);
            color: #fff;
            border-color: rgba(255,255,255,0.4);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        .form-label {
            color: rgba(255,255,255,0.85);
        }
        .btn-primary {
            border-radius: 14px;
            padding: 0.9rem 1.4rem;
            font-weight: 600;
            box-shadow: 0 14px 30px rgba(0,0,0,0.12);
        }
        .helper-text {
            color: rgba(255,255,255,0.75);
        }
        .login-footer {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.7);
        }
        .logo-badge {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.18);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="video-background">
    <video autoplay muted loop playsinline>
        <source src="downloads/carwash.mp4" type="video/mp4">
        Your browser does not support the video tag.
</div>
<div class="video-overlay"></div>
<div class="page-wrap d-flex align-items-center justify-content-end min-vh-100 px-5">
    <div class="login-shell">
        <div class="card-body text-center">
            <div class="logo-badge mx-auto mb-4">🚗</div>
            <h1 class="login-title mb-2">Admin Login</h1>
            <p class="login-subtitle mb-4">Masuk untuk mengelola pelanggan, layanan, dan transaksi carwash.</p>

            <?php if ($error): ?>
                <div class="alert alert-danger text-start" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" novalidate>
                <div class="mb-3 text-start">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>
                <div class="mb-4 text-start">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Masuk Sekarang</button>
            </form>

            <p class="helper-text mt-4">Gunakan username <strong>admin</strong> dan password <strong>admin123</strong>.</p>
            <p class="login-footer mt-3">Carwash Dapa &bull; Sistem Manajemen Cuci Kendaraan</p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>