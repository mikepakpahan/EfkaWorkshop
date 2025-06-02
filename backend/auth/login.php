<?php
session_start();
require '../config/db.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Cek user berdasarkan email
$query = $conn->prepare("SELECT * FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
  // Simpan ke session
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['role'] = $user['role'];

  echo json_encode([
    'success' => true,
    'role' => $user['role']
  ]);
} else {
  http_response_code(401);
  echo json_encode(['error' => 'email atau password salah']);
}
?>
