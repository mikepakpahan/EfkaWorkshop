<?php
include 'config.php';

// Keamanan: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

// Cek jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Ambil semua data dari form
    $product_id = intval($_POST['product_id']);
    $part_name = $_POST['part_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $old_image_path = $_POST['old_image_path'];
    $new_image_url = $old_image_path;

    // 2. Cek apakah ada gambar baru yang di-upload
    if (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == 0) {
        // Ada file baru, proses upload seperti biasa
        $target_dir = "../assets/spareparts/";
        $image_name = time() . '_' . basename($_FILES["product_image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            $new_image_url = "../../../assets/spareparts/" . $image_name;
            
            // Hapus file gambar lama dari server untuk menghemat ruang
            if (file_exists("../" . $old_image_path)) {
                unlink("../" . $old_image_path);
            }
        } else {
            die("Gagal mengupload gambar baru.");
        }
    }

    // 3. Update data di database
    $stmt = $conn->prepare("UPDATE spareparts SET part_name = ?, description = ?, price = ?, stock = ?, image_url = ? WHERE id = ?");
    $stmt->bind_param("ssiisi", $part_name, $description, $price, $stock, $new_image_url, $product_id);

    if ($stmt->execute()) {
        // Jika berhasil, redirect kembali ke halaman manajemen
        header("Location: ../Pages/admin/spareparts/manage-sparepart.php");
        exit();
    } else {
        echo "Error saat memperbarui data: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();

} else {
    // Jika halaman diakses tanpa submit form, redirect
    header("Location: ../Pages/admin/spareparts/manage-sparepart.php");
    exit();
}
?>