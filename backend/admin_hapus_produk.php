<?php
require_once 'config.php';

// Keamanan: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

if (!isset($_GET['id'])) {
    header("Location: ../Pages/admin/spareparts/manage-sparepart.php");
    exit();
}

$product_id = intval($_GET['id']);

// BUKAN DELETE, TAPI UPDATE STATUSNYA MENJADI TIDAK AKTIF (0)
$stmt = $conn->prepare("UPDATE spareparts SET is_active = 0 WHERE id = ?");
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Produk telah berhasil diarsipkan (tidak aktif).";
} else {
    $_SESSION['error_message'] = "Gagal mengarsipkan produk.";
}

header("Location: ../Pages/admin/spareparts/manage-sparepart.php"); // Sesuaikan path jika perlu
exit();
?>