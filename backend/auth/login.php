<?php
session_start();
require '../config/db.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Cek user berdasarkan username
$query = $conn->prepare("SELECT * FROM users WHERE username = ?");
$query->bind_param("s", $username);
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
  echo json_encode(['error' => 'Username atau password salah']);
}
?>
