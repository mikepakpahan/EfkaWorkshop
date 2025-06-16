<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);
    $user_id = $_SESSION['user_id'];

    if ($quantity < 1) {
        $quantity = 1;
    }

    // Pastikan user hanya bisa update keranjangnya sendiri
    $stmt = $conn->prepare("UPDATE carts SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $quantity, $cart_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
    }
    $stmt->close();
    $conn->close();
}
?>