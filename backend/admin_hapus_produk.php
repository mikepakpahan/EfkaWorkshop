<?php
include 'config.php';

// KEAMANAN: Cek admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak.");
}

// Cek apakah ID produk dikirim melalui URL
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']); // Ambil ID dan pastikan itu adalah angka

    // TODO: Hapus juga file gambar dari server untuk menghemat ruang
    // (Ini bisa ditambahkan nanti untuk fungsionalitas lebih lanjut)

    // Siapkan query DELETE
    $stmt = $conn->prepare("DELETE FROM spareparts WHERE id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        // Jika berhasil, kembalikan ke halaman manajemen
        header("Location: ../Pages/admin/spareparts/manage-sparepart.php"); // Sesuaikan path jika perlu
        exit();
    } else {
        echo "Gagal menghapus produk: " . $stmt->error;
    }

} else {
    // Jika tidak ada ID, kembalikan saja
    header("Location: ../management_sparepart.php"); // Sesuaikan path jika perlu
    exit();
}
?>