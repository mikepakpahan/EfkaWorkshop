<?php
require_once 'config.php';

// Keamanan: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

if (!isset($_GET['id'])) {
    header("Location: ../Pages/admin/order/sparepart-order.php");
    exit();
}

$order_id = intval($_GET['id']);

// Memulai transaction
$conn->begin_transaction();

try {
    // Ambil semua data yang diperlukan dari order yang akan dibatalkan
    $sql_get_order = "SELECT o.user_id, o.total_amount, oi.quantity, s.part_name 
                      FROM orders o
                      JOIN order_items oi ON o.id = oi.order_id
                      JOIN spareparts s ON oi.sparepart_id = s.id
                      WHERE o.id = ?";
    $stmt_get = $conn->prepare($sql_get_order);
    $stmt_get->bind_param("i", $order_id);
    $stmt_get->execute();
    $items = $stmt_get->get_result()->fetch_all(MYSQLI_ASSOC);

    if (empty($items)) {
        throw new Exception("Pesanan tidak ditemukan atau kosong.");
    }
    
    // Buat deskripsi pesanan DENGAN TANDA DIBATALKAN
    $description_parts = [];
    foreach ($items as $item) {
        $description_parts[] = $item['part_name'] . ' (x' . $item['quantity'] . ')';
    }
    $description = "[DIBATALKAN] " . implode(', ', $description_parts);
    $user_id = $items[0]['user_id'];
    $final_price = $items[0]['total_amount'];

    // Masukkan ke tabel history
    $stmt_history = $conn->prepare("INSERT INTO history (user_id, transaction_type, description, final_price) VALUES (?, 'sparepart', ?, ?)");
    $stmt_history->bind_param("isi", $user_id, $description, $final_price);
    $stmt_history->execute();

    // Hapus dari tabel orders (dan order_items akan ikut terhapus karena CASCADE)
    $stmt_delete = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt_delete->bind_param("i", $order_id);
    $stmt_delete->execute();
    
    $conn->commit();
    $_SESSION['success_message'] = "Pesanan telah berhasil dibatalkan dan dicatat dalam riwayat.";
    
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error_message'] = "Gagal membatalkan pesanan: " . $e->getMessage();
}

header("Location: ../Pages/admin/spareparts/sparepart-order.php");
exit();
?>