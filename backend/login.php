<?php
include 'config.php';

// Ambil data dari form
$email = $_POST['email'];
$password = $_POST['password'];

// Persiapkan statement untuk mencari user berdasarkan email
$stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah user dengan email tersebut ada (hasilnya harus 1 baris)
if ($result->num_rows === 1) {
    // Ambil data user
    $user = $result->fetch_assoc();

    // Verifikasi password yang diinput dengan hash di database
    if (password_verify($password, $user['password'])) {
        // Jika password cocok, simpan informasi user ke dalam SESSION
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['logged_in'] = true;

        // Arahkan ke halaman utama (index.html)
        header("Location: ../index.php");
        exit();
    } else {
        // Jika password tidak cocok
        echo "Login Gagal. Email atau Password salah.";
    }
} else {
    // Jika email tidak ditemukan
    echo "Login Gagal. Email atau Password salah.";
}

$stmt->close();
$conn->close();
?>