<?php
require_once 'config.php';

// Keamanan: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

if (!isset($_GET['id'])) {
    header("Location: ../Pages/admin/queue/queue.php");
    exit();
}

$booking_id = intval($_GET['id']);

// Langsung hapus dari tabel service_bookings
$stmt = $conn->prepare("DELETE FROM service_bookings WHERE id = ?");
$stmt->bind_param("i", $booking_id);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Booking telah berhasil dihapus dari antrian.";
} else {
    $_SESSION['error_message'] = "Gagal menghapus booking.";
}

header("Location: ../Pages/admin/queue/queue.php");
exit();
?>