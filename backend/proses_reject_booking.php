<?php
// Panggil file konfigurasi dan autoloader dari Composer
require_once 'config.php';
require_once '../../vendor/autoload.php';

// Gunakan class-class dari PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Keamanan: Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

// Cek jika data dikirim dengan method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Ambil data dari form modal reject
    $booking_id = intval($_POST['booking_id']);
    $rejection_reason = trim($_POST['rejection_reason']); // Ambil alasan penolakan

    // Validasi sederhana
    if (empty($booking_id) || empty($rejection_reason)) {
        die("Error: Alasan penolakan harus diisi.");
    }

    // 2. Update database: ubah status menjadi 'rejected' dan simpan alasannya
    $stmt = $conn->prepare("UPDATE service_bookings SET status = 'rejected', rejection_reason = ? WHERE id = ?");
    $stmt->bind_param("si", $rejection_reason, $booking_id);
    
    if ($stmt->execute()) {
        // Jika update DB berhasil, lanjutkan kirim email

        // Ambil data customer (nama & email) untuk tujuan pengiriman email
        $stmt_user = $conn->prepare("SELECT u.name, u.email FROM users u JOIN service_bookings sb ON u.id = sb.user_id WHERE sb.id = ?");
        $stmt_user->bind_param("i", $booking_id);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        $customer = $result_user->fetch_assoc();
        $customer_name = $customer['name'];
        $customer_email = $customer['email'];

        // 3. Konfigurasi dan Kirim Email dengan PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Konfigurasi Server SMTP (Sama seperti sebelumnya)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mike.pakpahan1407@gmail.com'; // GANTI DENGAN EMAIL GMAIL ANDA
            $mail->Password   = 'wmxp potp xqpx srah'; // GANTI DENGAN APP PASSWORD ANDA
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Penerima dan Pengirim
            $mail->setFrom('admin@efka.com', 'EFKA Workshop');
            $mail->addAddress($customer_email, $customer_name);

            // Konten Email Penolakan
            $mail->isHTML(true);
            $mail->Subject = 'Pemberitahuan Mengenai Booking Servis Anda di EFKA Workshop';
            
            // Body email dalam bentuk HTML
            $mail->Body    = "
                <html>
                <body>
                    <h2>Halo, " . htmlspecialchars($customer_name) . ".</h2>
                    <p>Dengan berat hati kami memberitahukan bahwa permintaan booking servis Anda belum dapat kami proses saat ini.</p>
                    <p><strong>Alasan:</strong></p>
                    <blockquote style='border-left: 4px solid #ccc; padding-left: 15px; margin-left: 0;'>" 
                    . nl2br(htmlspecialchars($rejection_reason)) . 
                    "</blockquote>
                    <p>Kami mohon maaf atas ketidaknyamanannya. Anda dapat mencoba melakukan booking kembali di tanggal lain atau menghubungi kami untuk informasi lebih lanjut.</p>
                    <p>Terima kasih atas pengertian Anda,<br>Tim EFKA Workshop</p>
                </body>
                </html>";

            $mail->send();
            
            // Jika email berhasil dikirim, kembali ke halaman pending dengan pesan sukses
            $_SESSION['success_message'] = "Booking berhasil di-reject dan email pemberitahuan telah dikirim ke customer.";
            header("Location: ../Pages/admin/service/pending-service.php"); // Sesuaikan path jika perlu
            exit();

        } catch (Exception $e) {
            echo "Email gagal dikirim. Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        echo "Gagal memperbarui database.";
    }

    $stmt->close();
    $conn->close();
}
?>