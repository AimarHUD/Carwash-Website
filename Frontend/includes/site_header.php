<?php
if (!isset($current_page)) {
    $current_page = basename($_SERVER['PHP_SELF']);
}

if (!function_exists('washwoosh_nav_class')) {
    function washwoosh_nav_class(string $target_page, string $current_page): string
    {
        return 'text-decoration-none ' . ($current_page === $target_page ? 'text-primary' : 'text-dark');
    }
}
?>
<header class="site-header py-3">
    <div class="container-fluid px-5 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <a class="navbar-brand fw-bold fs-3 text-primary text-decoration-none d-flex align-items-center" href="index.php">
            <img src="assets/images/logo washwoosh.png" alt="Logo Carwash Woosh" width="70" class="me-2 d-none d-md-inline">
            Carwash Woosh
        </a>

        <nav class="site-nav d-flex flex-wrap gap-4 fw-semibold" aria-label="Navigasi utama">
            <a class="<?= washwoosh_nav_class('index.php', $current_page) ?>" href="index.php">Home</a>
            <a class="<?= washwoosh_nav_class('tentang.php', $current_page) ?>" href="tentang.php">Tentang Kami</a>
            <a class="<?= washwoosh_nav_class('service.php', $current_page) ?>" href="service.php">Service Kami</a>
            <a class="<?= washwoosh_nav_class('artikel.php', $current_page) ?>" href="artikel.php">Artikel</a>
            <a class="<?= washwoosh_nav_class('kontak.php', $current_page) ?>" href="kontak.php">Kontak Kami</a>
        </nav>
    </div>
</header>