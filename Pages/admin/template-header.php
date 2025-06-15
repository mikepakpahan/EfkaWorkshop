<?php
// Set judul default jika tidak ada judul yang di-set di halaman utama
if (!isset($pageTitle)) {
    $pageTitle = 'EFKA Workshop Admin';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Management Sparepart - EFKA Workshop</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <div class="page-container">
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