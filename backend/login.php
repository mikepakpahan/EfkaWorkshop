<?php
// Memanggil file konfigurasi untuk memulai session dan koneksi database
include 'config.php';

// Mengambil data dari form login dengan method POST
$email = $_POST['email'];
$password = $_POST['password'];

// Menyiapkan statement untuk mencari user berdasarkan email untuk mencegah SQL Injection
$stmt = $conn->prepare("SELECT id, name, email, password, role, account_status FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah user dengan email tersebut ditemukan (hasil query harus 1 baris)
if ($result->num_rows === 1) {
    
    // Ambil data user dari hasil query
    $user = $result->fetch_assoc();

    // Verifikasi password yang diinput oleh user dengan hash yang ada di database
    if (password_verify($password, $user['password'])) {
        
        // --- PENGECEKAN STATUS AKUN DIMULAI DI SINI ---
        // Setelah password dipastikan benar, kita cek status akunnya.
        if ($user['account_status'] === 'active') {

            // JIKA AKUN AKTIF, lanjutkan proses login seperti biasa
            
            // Simpan semua informasi penting user ke dalam SESSION
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['logged_in'] = true;

            // LOGIKA PENGALIHAN BERDASARKAN ROLE
            if ($_SESSION['user_role'] === 'admin') {
                // Jika role adalah 'admin', arahkan ke dashboard admin
                header("Location: ../Pages/admin/dashboard/dashboard.php");
            } else {
                // Jika bukan admin (customer), arahkan ke halaman utama
                header("Location: ../index.php");
            }
            exit();

        } else {
            // JIKA AKUN TIDAK AKTIF (misal: 'inactive' atau 'suspended')
            echo "Login Gagal. Akun Anda saat ini tidak aktif. Silakan hubungi admin.";
            exit();
        }
        // --- AKHIR DARI PENGECEKAN STATUS AKUN ---

    } else {
        // Jika password tidak cocok dari awal
        echo "Login Gagal. Email atau Password salah.";
    }
} else {
    // Jika email tidak ditemukan
    echo "Login Gagal. Email atau Password salah.";
}

// Tutup statement dan koneksi untuk membereskan resource
$stmt->close();
$conn->close();
?>