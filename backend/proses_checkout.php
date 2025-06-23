<?php
require_once 'config.php';
require_once '../../vendor/autoload.php'; 

if (!isset($_SESSION['logged_in'])) { die("Anda harus login."); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $cart_ids_to_checkout = $_POST['cart_ids'] ?? [];

    if (empty($cart_ids_to_checkout)) {
        header("Location: ../Pages/Checkout/checkout.php");
        exit;
    }

    $placeholders = implode(',', array_fill(0, count($cart_ids_to_checkout), '?'));
    $types = str_repeat('i', count($cart_ids_to_checkout));

    // Ambil semua item yang di-checkout dari keranjang
    $sql = "SELECT s.id as sparepart_id, s.price, c.quantity 
            FROM carts c JOIN spareparts s ON c.sparepart_id = s.id 
            WHERE c.id IN ($placeholders) AND c.user_id = ?";
    
    // Buat array baru untuk menampung semua parameter
    $params = $cart_ids_to_checkout;
    $params[] = $user_id; // Masukkan user_id ke dalam array

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types . "i", ...$params);

    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Mulai transaction
    $conn->begin_transaction();
    try {
        $total_amount = 0;
        foreach ($items as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

        $completion_token = bin2hex(random_bytes(32));

        // 1. Insert ke tabel orders
        $stmt_order = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, completion_token) VALUES (?, ?, 'processing', ?)");
        $stmt_order->bind_param("iis", $user_id, $total_amount, $completion_token);
        $stmt_order->execute();
        $new_order_id = $conn->insert_id;

        // 2. Insert setiap item ke tabel order_items
        $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, sparepart_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
        foreach ($items as $item) {
            $stmt_items->bind_param("iiii", $new_order_id, $item['sparepart_id'], $item['quantity'], $item['price']);
            $stmt_items->execute();
        }

        // 3. Hapus item yang sudah di-checkout dari tabel carts
        $stmt_delete = $conn->prepare("DELETE FROM carts WHERE id IN ($placeholders) AND user_id = ?");
        // Gunakan array $params yang sama yang sudah kita buat di atas
        $stmt_delete->bind_param($types . "i", ...$params);
        
        $stmt_delete->execute();
        
        $conn->commit();
        
        header("Location: ../order_success.php?order_id=" . $new_order_id);
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        die("Checkout gagal. Silakan coba lagi. Error: " . $e->getMessage());
    }
}
?>