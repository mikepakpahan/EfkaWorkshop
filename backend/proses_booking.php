<?php
include 'config.php';

// Atur header agar browser tahu jawabannya adalah JSON
header('Content-Type: application/json');

// Keamanan: Cek jika pengguna sudah login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login untuk melakukan booking.']);
    exit;
}

// Cek jika data dikirim dengan method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari form
    $user_id = $_SESSION['user_id'];
    $motor_type = $_POST['motor_type'];
    $booking_date = $_POST['booking_date'];
    $complaint = $_POST['complaint'];
    $status = 'pending';

    // Validasi sederhana
    if (empty($motor_type) || empty($booking_date) || empty($complaint)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua kolom harus diisi dengan lengkap.']);
        exit;
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO service_bookings (user_id, motor_type, booking_date, complaint, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $motor_type, $booking_date, $complaint, $status);

    if ($stmt->execute()) {
        // Jika berhasil, kirim jawaban SUKSES
        echo json_encode(['status' => 'success', 'message' => 'Booking Anda telah berhasil terkirim! Silakan tunggu konfirmasi dari admin via email.']);
    } else {
        // Jika gagal, kirim jawaban GAGAL
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan pada server. Silakan coba lagi.']);
    }

    $stmt->close();
    $conn->close();
    exit;

}
?>