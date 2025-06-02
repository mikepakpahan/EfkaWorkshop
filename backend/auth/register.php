<?php
require '../config/db.php';

$username = $_POST['username'];
$password = $_POST['password'];
$role = 'customer';

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$query->bind_param("sss", $username, $hashed_password, $role);

if ($query->execute()) {
  echo json_encode(['success' => true, 'message' => 'Registrasi berhasil']);
} else {
  http_response_code(500);
  echo json_encode(['error' => 'Username sudah terdaftar']);
}
?>
