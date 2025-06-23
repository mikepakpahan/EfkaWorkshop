<?php
require_once 'config.php';

// Keamanan: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

if (!isset($_GET['id'])) {
    header("Location: ../Pages/admin/feedback/feedback.php");
    exit();
}

$feedback_id = intval($_GET['id']);

// Update status pesan menjadi 'read'
$stmt = $conn->prepare("UPDATE feedback SET status = 'read' WHERE id = ?");
$stmt->bind_param("i", $feedback_id);

if ($stmt->execute()) {
} else {
}

// Kembali ke halaman feedback
header("Location: ../Pages/admin/feedback/feedback.php");
exit();
?>