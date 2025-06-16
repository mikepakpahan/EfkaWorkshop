<?php
require_once 'config.php';
header('Content-Type: application/json');

// Keamanan: Wajib login untuk mengirim feedback
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login untuk mengirim feedback.']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari session dan form
    $user_id = $_SESSION['user_id'];
    $name = $_SESSION['user_name'];
    $email = $_SESSION['user_email'];
    $message = trim($_POST['message']);

    if (empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Pesan tidak boleh kosong.']);
        exit;
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, name, email, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $name, $email, $message);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Terima kasih! Feedback Anda telah berhasil dikirim.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan. Gagal mengirim feedback.']);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>