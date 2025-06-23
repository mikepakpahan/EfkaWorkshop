<?php
require_once 'config.php';

// Keamanan: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

if (!isset($_GET['id'])) {
    header("Location: ../Pages/admin/service/manage-service.php");
    exit();
}

$service_id = intval($_GET['id']);

// TRANSACTION: Ambil path gambar, hapus dari DB, lalu hapus file gambar.
$conn->begin_transaction();

try {
    // 1. Ambil path gambar sebelum data dihapus dari DB
    $stmt_select = $conn->prepare("SELECT image_url FROM services WHERE id = ?");
    $stmt_select->bind_param("i", $service_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = $row['image_url'];
    }
    $stmt_select->close();

    // 2. Hapus data dari tabel 'services'
    $stmt_delete = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt_delete->bind_param("i", $service_id);
    $stmt_delete->execute();
    $stmt_delete->close();
    
    // 3. Jika path gambar ada, hapus file gambarnya dari server
    if (!empty($image_path)) {
        $file_to_delete = '../' . $image_path; 
        if (file_exists($file_to_delete)) {
            unlink($file_to_delete);
        }
    }
    
    // Jika semua berhasil
    $conn->commit();
    $_SESSION['success_message'] = "Layanan telah berhasil dihapus.";

} catch (Exception $e) {
    // Jika ada yang gagal, batalkan semua
    $conn->rollback();
    $_SESSION['error_message'] = "Gagal menghapus layanan: " . $e->getMessage();
}

header("Location: ../Pages/admin/service/manage-service.php");
exit();
?>