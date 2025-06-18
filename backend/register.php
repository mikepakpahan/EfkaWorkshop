<?php
include 'config.php';

// Ambil data dari form
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

$errors = [];
if (strlen($password) < 8) {
    $errors[] = "Password harus minimal 8 karakter.";
}
if (!preg_match('/[A-Z]/', $password)) {
    $errors[] = "Password harus mengandung setidaknya satu huruf besar.";
}
if (!preg_match('/[0-9]/', $password)) {
    $errors[] = "Password harus mengandung setidaknya satu angka.";
}
if (!preg_match('/[^A-Za-z0-9]/', $password)) {
    $errors[] = "Password harus mengandung setidaknya satu simbol.";
}

// Jika ditemukan ada error dari validasi di atas, hentikan proses.
if (!empty($errors)) {
    die("Pendaftaran Gagal: " . implode(" ", $errors));
}
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Pendaftaran berhasil! Silakan login dengan akun Anda.";
    header("Location: ../Pages/login/login-page.php"); // Sesuaikan path jika perlu
    exit(); 
} else {
    if ($conn->errno === 1062) {
        die("Pendaftaran Gagal: Alamat email ini sudah terdaftar. Silakan gunakan email lain atau login.");
    } else {
        die("Terjadi kesalahan pada server saat pendaftaran. Silakan coba lagi nanti.");
    }
}

$stmt->close();
$conn->close();
?>