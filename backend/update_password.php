<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in'])) { exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Ambil password lama dari DB
    $stmt_check = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt_check->bind_param("i", $user_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    $user = $result->fetch_assoc();

    // Verifikasi password lama
    if (!password_verify($old_password, $user['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Password lama Anda salah.']);
        exit;
    }
    
    // Hash password baru
    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password baru ke DB
    $stmt_update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt_update->bind_param("si", $new_hashed_password, $user_id);
    if ($stmt_update->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Password berhasil diubah.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengubah password.']);
    }
    exit;
}
?>