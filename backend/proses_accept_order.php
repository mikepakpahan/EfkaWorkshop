<?php
require_once 'config.php';

// Keamanan: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

if (!isset($_GET['id'])) {
    header("Location: ../Pages/admin/order/sparepart-order.php"); // Sesuaikan path
    exit();
}

$order_id = intval($_GET['id']);

// Memulai database transaction untuk keamanan data
$conn->begin_transaction();

try {
    // 1. Ambil semua data yang diperlukan dari order yang akan diproses
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

    // Buat deskripsi pesanan untuk tabel history
    $description_parts = [];
    foreach ($items as $item) {
        $description_parts[] = $item['part_name'] . ' (x' . $item['quantity'] . ')';
    }
    $description = implode(', ', $description_parts);
    $user_id = $items[0]['user_id'];
    $final_price = $items[0]['total_amount'];

    // 2. Masukkan data ke tabel history
    $stmt_history = $conn->prepare("INSERT INTO history (user_id, transaction_type, description, final_price) VALUES (?, 'sparepart', ?, ?)");
    $stmt_history->bind_param("isi", $user_id, $description, $final_price);
    $stmt_history->execute();

    // 3. Hapus data dari order_items (ON DELETE CASCADE akan bekerja jika order dihapus)
    // 4. Hapus data dari tabel orders
    $stmt_delete = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt_delete->bind_param("i", $order_id);
    $stmt_delete->execute();
    
    // Jika semua berhasil, 'commit' transaksinya
    $conn->commit();
    $_SESSION['success_message'] = "Pesanan telah diselesaikan dan dipindahkan ke riwayat.";
    
} catch (Exception $e) {
    // Jika ada yang gagal, batalkan semua perubahan
    $conn->rollback();
    $_SESSION['error_message'] = "Gagal memproses pesanan: " . $e->getMessage();
}

// Kembali ke halaman order
header("Location: ../Pages/admin/spareparts/sparepart-order.php"); // Sesuaikan path
exit();
?>