<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = intval($_POST['cart_id']);
    $user_id = $_SESSION['user_id'];

    // Pastikan user hanya bisa hapus keranjangnya sendiri
    $stmt = $conn->prepare("DELETE FROM carts WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database delete failed']);
    }
    $stmt->close();
    $conn->close();
}
?>