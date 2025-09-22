<?php
// File: resources/backend/handlers/landing_page_handler.php

function getLandingPageData($conn)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // --- 1. AMBIL DATA SERVICES DARI DATABASE ---
    $services = [];
    $sql_services = 'SELECT service_name, description, image_url FROM services ORDER BY id';
    $result_services = $conn->query($sql_services);
    if ($result_services && $result_services->num_rows > 0) {
        while ($row = $result_services->fetch_assoc()) {
            $services[] = $row;
        }
    }

    // --- 2. SIAPKAN DATA AUTENTIKASI (LOGIN STATUS) ---
    $isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    $userName = $_SESSION['user_name'] ?? ''; // Pake null coalescing operator, lebih rapi
    $userEmail = $_SESSION['user_email'] ?? '';

    // Data dari layout helper juga bisa kita panggil di sini jika perlu
    // Tapi karena rating sudah ada di layout helper, kita bisa lewati

    // --- 3. KEMBALIKAN SEMUA DATA DALAM SATU PAKET ---
    return [
        'services' => $services,
        'isLoggedIn' => $isLoggedIn,
        'userName' => $userName,
        'userEmail' => $userEmail,
    ];
}
