<?php
include 'config.php';

// Keamanan: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

// Cek jika form disubmit dengan method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Ambil data teks dari form
    $part_name = $_POST['part_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // 2. Proses Upload Gambar
    $image_url_for_db = '';
    if (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == 0) {
        
        // --- PERUBAHAN DI SINI ---
        $target_dir = "../assets/spareparts/"; // Folder tujuan upload, relatif dari file ini
        
        $image_name = time() . '_' . basename($_FILES["product_image"]["name"]);
        $target_file = $target_dir . $image_name;
        
        // --- DAN PERUBAHAN DI SINI ---
        $image_url_for_db = "/EfkaWorkshop/assets/spareparts/" . $image_name;

        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            // File berhasil diupload
        } else {
            die("Maaf, terjadi error saat mengupload file gambar.");
        }
    } else {
        die("Error: Tidak ada gambar yang di-upload atau terjadi kesalahan.");
    }

    // 3. Simpan data ke Database
    if (!empty($image_url_for_db)) {
        $stmt = $conn->prepare("INSERT INTO spareparts (part_name, description, price, stock, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiis", $part_name, $description, $price, $stock, $image_url_for_db);

        if ($stmt->execute()) {
            header("Location: ../Pages/admin/spareparts/manage-sparepart.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();

} else {
    header("Location: ../Pages/admin/spareparts/manage-sparepart.php");
    exit();
}
?>