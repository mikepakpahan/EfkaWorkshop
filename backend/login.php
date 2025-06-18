<?php
// Memanggil file konfigurasi untuk memulai session dan koneksi database
include 'config.php';

// Mengambil data dari form login dengan method POST
$email = $_POST['email'];
$password = $_POST['password'];

// Menyiapkan statement untuk mencari user berdasarkan email
$stmt = $conn->prepare("SELECT id, name, email, password, role, account_status FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        
        if ($user['account_status'] === 'active') {
            // --- KASUS SUKSES ---
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['logged_in'] = true;

            if ($_SESSION['user_role'] === 'admin') {
                header("Location: ../Pages/admin/dashboard/dashboard.php");
            } else {
                header("Location: ../index.php");
            }
            exit();

        } else {
            // --- KASUS GAGAL: AKUN TIDAK AKTIF ---
            $_SESSION['error_message'] = "Login Gagal. Akun Anda saat ini tidak aktif. Silakan hubungi admin.";
            header("Location: ../Pages/login/login-page.php");
            exit();
        }

    } else {
        // --- KASUS GAGAL: PASSWORD SALAH ---
        $_SESSION['error_message'] = "Login Gagal. Email atau Password salah.";
        header("Location: ../Pages/login/login-page.php");
        exit();
    }
} else {
    // --- KASUS GAGAL: EMAIL TIDAK DITEMUKAN ---
    $_SESSION['error_message'] = "Login Gagal. Email atau Password salah.";
    header("Location: ../Pages/login/login-page.php");
    exit();
}

// Tutup statement dan koneksi
$stmt->close();
$conn->close();
?>