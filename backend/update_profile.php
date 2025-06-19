<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in'])) { exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name']);

    if (empty($name)) {
        echo json_encode(['status' => 'error', 'message' => 'Nama tidak boleh kosong.']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $user_id);
    if ($stmt->execute()) {
        $_SESSION['user_name'] = $name; // Perbarui session juga
        echo json_encode(['status' => 'success', 'message' => 'Nama berhasil diperbarui.', 'new_name' => $name]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui nama.']);
    }
    exit;
}
?>