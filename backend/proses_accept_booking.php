<?php
require_once 'config.php';
require_once '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Keamanan: Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $booking_id = intval($_POST['booking_id']);
    $service_price = intval($_POST['service_price']);
    if (empty($booking_id) || empty($service_price) || !is_numeric($service_price)) { die("Error: Data tidak valid."); }
    $confirmation_token = bin2hex(random_bytes(32));
    $stmt = $conn->prepare("UPDATE service_bookings SET status = 'accepted', price = ?, confirmation_token = ? WHERE id = ?");
    $stmt->bind_param("isi", $service_price, $confirmation_token, $booking_id);
    
    if ($stmt->execute()) {
        $stmt_user = $conn->prepare("SELECT u.name, u.email FROM users u JOIN service_bookings sb ON u.id = sb.user_id WHERE sb.id = ?");
        $stmt_user->bind_param("i", $booking_id);
        $stmt_user->execute();
        $customer = $stmt_user->get_result()->fetch_assoc();
        $customer_name = $customer['name'];
        $customer_email = $customer['email'];

        $mail = new PHPMailer(true);
        try {
            // Konfigurasi Server SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Ambil kredensial dari variabel .env, bukan ditulis langsung
            $mail->Username   = $_ENV['GMAIL_USER'];
            $mail->Password   = $_ENV['GMAIL_APP_PASSWORD'];
            // Penerima dan Pengirim
            $mail->setFrom($_ENV['GMAIL_USER'], 'EFKA Workshop'); // Pengirim juga pakai dari .env
            $mail->addAddress($customer_email, $customer_name);

            // Konten Email
            $mail->isHTML(true);
            $mail->Subject = 'Booking Servis Anda di EFKA Workshop Telah Diterima!';
            
            // Buat link konfirmasi
            $confirmation_link = "http://192.168.100.110/EfkaWorkshop/confirm_booking.php?token=" . $confirmation_token;
            
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
            
            $_SESSION['success_message'] = "Booking berhasil di-accept dan email konfirmasi telah dikirim.";
            header("Location: ../Pages/admin/service/pending-service.php");
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