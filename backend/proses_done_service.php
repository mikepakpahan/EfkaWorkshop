<?php
require_once 'config.php';

// Keamanan: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

if (!isset($_GET['id'])) {
    header("Location: ../Pages/admin/queue/queue.php"); // Sesuaikan path
    exit();
}

$booking_id = intval($_GET['id']);

// Memulai database transaction
$conn->begin_transaction();

try {
    // 1. Ambil data dari service_bookings sebelum dihapus
    $stmt_select = $conn->prepare("SELECT user_id, complaint, price FROM service_bookings WHERE id = ?");
    $stmt_select->bind_param("i", $booking_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $booking = $result->fetch_assoc();

    if (!$booking) {
        throw new Exception("Booking tidak ditemukan.");
    }

    $user_id = $booking['user_id'];
    $description = $booking['complaint'];
    $final_price = $booking['price'];
    $type = 'service';

    // 2. Masukkan data ke tabel history
    $stmt_insert = $conn->prepare("INSERT INTO history (user_id, transaction_type, description, final_price) VALUES (?, ?, ?, ?)");
    $stmt_insert->bind_param("issi", $user_id, $type, $description, $final_price);
    $stmt_insert->execute();

    // 3. Hapus data dari tabel service_bookings
    $stmt_delete = $conn->prepare("DELETE FROM service_bookings WHERE id = ?");
    $stmt_delete->bind_param("i", $booking_id);
    $stmt_delete->execute();

    // Jika semua query berhasil, 'commit' transaksinya
    $conn->commit();

    $_SESSION['success_message'] = "Servis telah ditandai selesai dan dipindahkan ke riwayat.";
    
} catch (Exception $e) {
    // Jika ada satu saja query yang gagal, batalkan semua perubahan (rollback)
    $conn->rollback();
    $_SESSION['error_message'] = "Gagal memproses: " . $e->getMessage();
}

// Kembali ke halaman antrian
header("Location: ../Pages/admin/queue/queue.php"); // Sesuaikan path
exit();
?>