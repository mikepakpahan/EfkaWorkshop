<?php

include 'config.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");

$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {
    // Jika eksekusi berhasil:
    // 1. SIMPAN PESAN SUKSES KE DALAM SESSION
    $_SESSION['success_message'] = "Pendaftaran berhasil! Silakan login dengan akun Anda.";

    // 2. Arahkan pengguna ke halaman login.
    // Pastikan mengarah ke login.php, bukan login.html
    header("Location: ../Pages/login/login-page.php");
    exit(); 
} else {
    // ... (kode error tetap sama)
}

$stmt->close();
$conn->close();

?>