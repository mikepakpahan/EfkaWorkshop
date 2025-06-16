<?php
require_once 'config.php';

// Keamanan: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Ambil data teks dari form
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];

    // 2. Proses Upload Gambar
    $image_url_for_db = ''; 
    if (isset($_FILES["service_image"]) && $_FILES["service_image"]["error"] == 0) {
        $target_dir = "../assets/services/"; // Folder tujuan upload yang baru
        
        // Buat folder jika belum ada
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image_name = time() . '_' . basename($_FILES["service_image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["service_image"]["tmp_name"], $target_file)) {
            $image_url_for_db = "assets/services/" . $image_name;
        } else {
            die("Maaf, terjadi error saat mengupload file gambar.");
        }
    } else {
        die("Error: Gambar wajib di-upload.");
    }

    // 3. Simpan data ke Database
    if (!empty($image_url_for_db)) {
        $stmt = $conn->prepare("INSERT INTO services (service_name, description, image_url) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $service_name, $description, $image_url_for_db);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Layanan baru berhasil ditambahkan!";
            header("Location: ../Pages/admin/service/manage-service.php"); // Sesuaikan path
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();

} else {
    // Jika diakses langsung, redirect
    header("Location: ../Pages/admin/service/manage-service.php"); // Sesuaikan path
    exit();
}
?>