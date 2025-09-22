<header class="site-header">
    <div class="liquidGlass-effect"></div>
    <div class="header-container">
        <div class="header-logo">
            <a href="/EfkaWorkshop/index.php">
                <img src="/EfkaWorkshop/assets/logo-efka.png" alt="EFKA Workshop Logo" />
                <span>EFKA WORKSHOP</span>
            </a>
        </div>

        <nav class="nav-desktop">
            <a href="/EfkaWorkshop/index.php" class="nav-link <?php if ($activePage == 'home') {
                echo 'active';
            } ?>">Home</a>
            <a href="/EfkaWorkshop/Pages/customer/spareparts/sparepart.php"
                class="nav-link <?php if ($activePage == 'spareparts') {
                    echo 'active';
                } ?>">Spareparts</a>
            <div class="dropdown">
                <a href="#" class="nav-link">Layanan <i class="fas fa-chevron-down dropdown-arrow"></i></a>
                <div class="dropdown-content">
                    <div class="liquidGlass-effect"></div>
                    <a href="/EfkaWorkshop/index.php#services">Semua Layanan</a>
                    <a href="/EfkaWorkshop/#booking-section">Booking Servis</a>
                </div>
            </div>
            <a href="/EfkaWorkshop/index.php#footer" class="nav-link">Kontak</a>
        </nav>

        <div class="nav-actions">
            <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true): ?>
            <a href="/EfkaWorkshop/Pages/customer/checkout/checkout.php" class="cart-icon-wrapper">
                <img class="cart-icon" src="/EfkaWorkshop/assets/icons/icon-cart.png" alt="">
                <?php if ($cart_count > 0): ?>
                <span class="cart-indicator"><?php echo $cart_count; ?></span>
                <?php endif; ?>
            </a>
            <div class="dropdown user-dropdown">
                <a href="#" class="nav-link welcome-text">
                    Hi, <?php echo htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]); ?>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </a>
                <div class="dropdown-content">
                    <div class="liquidGlass-effect"></div>
                    <a href="/EfkaWorkshop/Pages/customer/history/riwayat_saya.php">Riwayat Saya</a>
                    <a href="/EfkaWorkshop/Pages/customer/profile/profil.php">Profil</a>
                    <a href="/EfkaWorkshop/backend/logout.php">Logout</a>
                </div>
            </div>
            <?php else: ?>
            <a href="/EfkaWorkshop/Pages/login/login-page.php" class="btn-primary" style="z-index: 1">Login</a>
            <?php endif; ?>
        </div>

        <button id="hamburger-btn" class="nav-toggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<nav id="mobile-menu" class="nav-mobile">
    <a href="/EfkaWorkshop/index.php" class="nav-link">Home</a>
    <a href="/EfkaWorkshop/Pages/customer/spareparts/sparepart.php" class="nav-link">Spareparts</a>
    <a href="/EfkaWorkshop/index.php#services" class="nav-link">Layanan</a>
    <a href="/EfkaWorkshop/index.php#footer" class="nav-link">Kontak</a>
    <hr>
    <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true): ?>
    <a href="/EfkaWorkshop/Pages/customer/history/riwayat_saya.php" class="nav-link">Riwayat Saya</a>
    <a href="/EfkaWorkshop/backend/logout.php" class="nav-link" style="color: #ffc107;">Logout</a>
    <?php else: ?>
    <a href="/EfkaWorkshop/Pages/login/login-page.php" class="btn-primary">Login / Daftar</a>
    <?php endif; ?>
</nav>
<?php /**PATH C:\xampp\htdocs\EfkaWorkshop\resources\views/layout/partials/customer/header.blade.php ENDPATH**/ ?>