<?php
session_start();
require '../config/db.php';
require '../utils/is_logged_in.php';
// Ambil data dari JSON body
$input = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'];
$schedule = isset($input['schedule']) ? $input['schedule'] : date('Y-m-d H:i:s');
$services = $input['services'];       // array of service_id
$spareparts = $input['spareparts'];   // array of [id, qty]

$total_price = 0;

// Hitung total harga
foreach ($services as $sid) {
  $res = $conn->query("SELECT price FROM services WHERE id = $sid");
  $row = $res->fetch_assoc();
  $total_price += $row['price'];
}

foreach ($spareparts as $item) {
  $id = $item['id'];
  $qty = $item['qty'];
  $res = $conn->query("SELECT price, stock FROM spareparts WHERE id = $id");
  $row = $res->fetch_assoc();
  
  if ($row['stock'] < $qty) {
    http_response_code(400);
    echo json_encode(['error' => 'Stok tidak cukup untuk item ID ' . $id]);
    exit;
  }

  $subtotal = $row['price'] * $qty;
  $total_price += $subtotal;
}

// Simpan order
$query = $conn->prepare("INSERT INTO orders (user_id, schedule, total_price) VALUES (?, ?, ?)");
$query->bind_param("isi", $user_id, $schedule, $total_price);
$query->execute();
$order_id = $conn->insert_id;

// Simpan layanan
foreach ($services as $sid) {
  $conn->query("INSERT INTO order_services (order_id, service_id) VALUES ($order_id, $sid)");
}

// Simpan spareparts dan kurangi stok
foreach ($spareparts as $item) {
  $id = $item['id'];
  $qty = $item['qty'];
  $res = $conn->query("SELECT price FROM spareparts WHERE id = $id");
  $row = $res->fetch_assoc();
  $subtotal = $row['price'] * $qty;

  $conn->query("INSERT INTO order_spareparts (order_id, sparepart_id, quantity, subtotal)
                VALUES ($order_id, $id, $qty, $subtotal)");
  $conn->query("UPDATE spareparts SET stock = stock - $qty WHERE id = $id");
}

echo json_encode(['success' => true, 'order_id' => $order_id]);
exit;
?>