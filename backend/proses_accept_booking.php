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
    
    // 1. Ambil data dari form modal
    $booking_id = intval($_POST['booking_id']);
    $service_price = intval($_POST['service_price']);

    // Validasi sederhana
    if (empty($booking_id) || empty($service_price) || !is_numeric($service_price)) {
        die("Error: Data tidak valid.");
    }

    // 2. Buat token konfirmasi unik untuk customer
    $confirmation_token = bin2hex(random_bytes(32));

    // 3. Update database: ubah status, masukkan harga, dan simpan token
    $stmt = $conn->prepare("UPDATE service_bookings SET status = 'accepted', price = ?, confirmation_token = ? WHERE id = ?");
    $stmt->bind_param("isi", $service_price, $confirmation_token, $booking_id);
    
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

        // 4. Konfigurasi dan Kirim Email dengan PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Konfigurasi Server SMTP (Contoh menggunakan Gmail)
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

            // Konten Email
            $mail->isHTML(true);
            $mail->Subject = 'Booking Servis Anda di EFKA Workshop Telah Diterima!';
            
            // Buat link konfirmasi
            $confirmation_link = "192.168.100.110/EfkaWorkshop/confirm_booking.php?token=" . $confirmation_token;
            
            // Body email dalam bentuk HTML
            $mail->Body    = "
                <html>
                <body>
                    <h2>Halo, " . htmlspecialchars($customer_name) . "!</h2>
                    <p>Kabar baik! Permintaan booking servis Anda telah kami terima dengan rincian sebagai berikut:</p>
                    <p><strong>Harga Estimasi Servis:</strong> Rp " . number_format($service_price, 0, ',', '.') . "</p>
                    <p>Silakan klik tombol di bawah ini untuk mengonfirmasi jadwal Anda. Dengan mengonfirmasi, Anda akan masuk ke dalam antrian servis kami.</p>
                    <a href='" . $confirmation_link . "' style='background-color: #FFC72C; color: black; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Konfirmasi Jadwal Saya</a>
                    <p>Jika Anda tidak merasa melakukan booking ini, silakan abaikan email ini.</p>
                    <p>Terima kasih,<br>Tim EFKA Workshop</p>
                </body>
                </html>";

            $mail->send();
            
            // Jika email berhasil dikirim, kembali ke halaman pending dengan pesan sukses
            $_SESSION['success_message'] = "Booking berhasil di-accept dan email konfirmasi telah dikirim ke customer.";
            header("Location: ../Pages/admin/service/pending-service.php"); // Sesuaikan path
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