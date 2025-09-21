<?php

require_once '../../../backend/config.php';
if (!isset($pageTitle)) {
    $pageTitle = 'EFKA Workshop Admin';
}

if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
    include __DIR__ . '/../errors/access_denied.php';
    exit();
}

?>
<header class="top-header">
    <button class="menu-toggle" id="menu-toggle-btn" aria-label="Toggle Menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <h1 class="page-title"><?php echo htmlspecialchars($pageTitle); ?></h1>
    <div class="header-logo">
        <img src="/EfkaWorkshop/assets/logo-efka.png" alt="EFKA Workshop Logo" class="logo">
    </div>
</header>