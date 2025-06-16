<?php
require_once 'config.php';

// Keamanan: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

if (!isset($_GET['id'])) {
    header("Location: ../Pages/admin/user/user.php"); // Sesuaikan path
    exit();
}

$user_id = intval($_GET['id']);

// Kita tidak DELETE, tapi UPDATE statusnya menjadi 'inactive'
$stmt = $conn->prepare("UPDATE users SET account_status = 'inactive' WHERE id = ? AND role = 'customer'");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Akun user telah berhasil dinonaktifkan.";
} else {
    $_SESSION['error_message'] = "Gagal menonaktifkan akun.";
}

header("Location: ../Pages/admin/user/user.php"); // Sesuaikan path
exit();
?>