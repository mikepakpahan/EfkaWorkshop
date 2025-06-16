<?php
// Karena file ini ada di dalam folder 'backend', dan config.php juga di sana,
// path-nya cukup nama filenya saja.
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_GET['order_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Order ID tidak ada.']);
    exit;
}

$order_id = intval($_GET['order_id']);

$stmt = $conn->prepare("SELECT status FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $order = $result->fetch_assoc();
    echo json_encode(['status' => $order['status']]);
} else {
    // Jika order tidak ditemukan di tabel 'orders' (kemungkinan sudah dipindah ke history)
    // kita anggap saja sudah 'completed' agar polling berhenti.
    echo json_encode(['status' => 'completed']);
}

$stmt->close();
$conn->close();
?>