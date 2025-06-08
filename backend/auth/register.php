<?php
require '../config/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Cek email sudah terdaftar
$query = $conn->prepare("SELECT id FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$query->store_result();
if ($query->num_rows > 0) {
  echo json_encode(['error' => 'Email sudah terdaftar']);
  exit;
}

// Hash password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Simpan user baru
$role = 'customer';
$query = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$query->bind_param("ssss", $name, $email, $hashed, $role);
if ($query->execute()) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['error' => 'Gagal daftar']);
}
?>
