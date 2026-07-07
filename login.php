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
        :root {
            --primary: #0d6efd;
            --primary-dark: #0b5ed7;
            --ink: #0f172a;
            --muted: #64748b;
            --border: #dce7f4;
            --bg: #f4f8ff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: Inter, "Segoe UI", Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, #f4f8ff 0%, #eef6ff 100%);
            color: var(--ink);
        }

        .page-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-card {
            width: min(100%, 430px);
            background: #ffffff;
            border: 1px solid rgba(13, 110, 253, 0.12);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.12);
            padding: 2.2rem;
        }

        .brand-wrap {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo-badge {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            background: linear-gradient(135deg, var(--primary) 0%, #3ab7ff 100%);
            color: #fff;
            box-shadow: 0 12px 30px rgba(13, 110, 253, 0.22);
            margin-bottom: 1rem;
        }

        .login-title {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.35rem;
            color: var(--ink);
        }

        .login-subtitle {
            color: var(--muted);
            margin-bottom: 0;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        .form-control {
            border-radius: 14px;
            border: 1px solid var(--border);
            padding: 0.8rem 0.95rem;
            color: var(--ink);
            background: #fcfdff;
        }

        .form-control:focus {
            border-color: #6ec5ff;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.16);
            background: #fff;
        }

        .btn-primary {
            border-radius: 14px;
            padding: 0.85rem 1rem;
            font-weight: 700;
            border: none;
            background: linear-gradient(135deg, var(--primary) 0%, #3ab7ff 100%);
            box-shadow: 0 12px 24px rgba(13, 110, 253, 0.18);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #2ea8ff 100%);
        }

        .alert {
            border-radius: 14px;
        }

        .helper-text {
            color: var(--muted);
            font-size: 0.95rem;
            margin-top: 1rem;
        }

        .login-footer {
            margin-top: 1.25rem;
            font-size: 0.92rem;
            color: #6b7280;
            text-align: center;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
<div class="page-shell">
    <div class="login-card">
        <div class="brand-wrap">
            <div class="logo-badge">🚗</div>
            <h1 class="login-title">WashWoosh</h1>
            <p class="login-subtitle">Admin Login Panel</p>
        </div>

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

        <p class="helper-text">Gunakan username <strong>admin</strong> dan password <strong>admin123</strong>.</p>
        <p class="login-footer">WashWoosh &bull; Sistem Manajemen Cuci Kendaraan</p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>