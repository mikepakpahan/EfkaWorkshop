<?php
require_once 'config.php';

// Keamanan: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Ambil semua data dari form
    $service_id = intval($_POST['service_id']);
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $old_image_path = $_POST['old_image_path'];
    $new_image_url = $old_image_path; // Secara default, kita pakai gambar lama

    // 2. Cek apakah ada gambar baru yang di-upload
    if (isset($_FILES["service_image"]) && $_FILES["service_image"]["error"] == 0) {
        // Ada file baru, proses upload
        $target_dir = "../assets/services/"; // Sesuaikan path
        $image_name = time() . '_' . basename($_FILES["service_image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["service_image"]["tmp_name"], $target_file)) {
            // Upload berhasil, siapkan path baru untuk DB
            $new_image_url = "assets/services/" . $image_name;
            
            // Hapus file gambar lama dari server
            if (file_exists("../" . $old_image_path)) {
                unlink("../" . $old_image_path);
            }
        } else {
            die("Gagal mengupload gambar baru.");
        }
    }

    // 3. Update data di database
    $stmt = $conn->prepare("UPDATE services SET service_name = ?, description = ?, image_url = ? WHERE id = ?");
    $stmt->bind_param("sssi", $service_name, $description, $new_image_url, $service_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Layanan berhasil diperbarui.";
        header("Location: ../Pages/admin/service/manage-service.php"); // Sesuaikan path
        exit();
    } else {
        echo "Error saat memperbarui data: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();

} else {
    // Jika diakses langsung, redirect
    header("Location: ../Pages/admin/service/manage-service.php"); // Sesuaikan path
    exit();
}
?>