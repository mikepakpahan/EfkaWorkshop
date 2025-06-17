<?php
require_once 'config.php';

// Keamanan: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

if (!isset($_GET['id'])) {
    header("Location: ../management_sparepart.php"); // Sesuaikan path jika perlu
    exit();
}

$new_hero_id = intval($_GET['id']);

// Memulai database transaction untuk menjamin keamanan data
$conn->begin_transaction();

try {
    // Langkah 1: Turunkan takhta semua produk yang saat ini jadi hero (is_featured = 1)
    $stmt_reset = $conn->prepare("UPDATE spareparts SET is_featured = 0 WHERE is_featured = 1");
    $stmt_reset->execute();
    $stmt_reset->close();

    // Langkah 2: Nobatkan produk yang dipilih sebagai hero baru
    $stmt_set = $conn->prepare("UPDATE spareparts SET is_featured = 1 WHERE id = ?");
    $stmt_set->bind_param("i", $new_hero_id);
    $stmt_set->execute();
    $stmt_set->close();

    // Jika kedua langkah berhasil, simpan perubahan permanen
    $conn->commit();
    $_SESSION['success_message'] = "Produk unggulan berhasil diperbarui!";

} catch (Exception $e) {
    // Jika salah satu langkah gagal, batalkan semua perubahan
    $conn->rollback();
    $_SESSION['error_message'] = "Gagal memperbarui produk unggulan: " . $e->getMessage();
}

// Kembali ke halaman manajemen sparepart
header("Location: /EfkaWorkshop/Pages/admin/spareparts/manage-sparepart.php");
exit();
?>