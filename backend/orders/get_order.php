<?php
require '../config/db.php';

$order_id = $_GET['id'];

$query = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$query->bind_param("i", $order_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
  $order = $result->fetch_assoc();
  echo json_encode($order);
} else {
  http_response_code(404);
  echo json_encode(['error' => 'Order tidak ditemukan']);
}
?>
