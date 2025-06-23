<?php
require_once 'config.php';

// Keamanan: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

// Pastikan ada ID produk yang dikirim
if (!isset($_GET['id'])) {
    header("Location: ../Pages/admin/spareparts/manage-sparepart.php");
    exit();
}

$product_id = intval($_GET['id']);

// Kita ubah statusnya kembali menjadi aktif (1)
$stmt = $conn->prepare("UPDATE spareparts SET is_active = 1 WHERE id = ?");
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Produk telah berhasil diaktifkan kembali dan akan muncul di halaman customer.";
} else {
    $_SESSION['error_message'] = "Gagal mengaktifkan produk.";
}

// Setelah selesai, kembalikan admin ke halaman manajemen sparepart
header("Location: ../Pages/admin/spareparts/manage-sparepart.php");
exit();
?>