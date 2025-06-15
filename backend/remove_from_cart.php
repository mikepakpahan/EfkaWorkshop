<?php
include 'config.php';

// Wajib login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    exit('Akses ditolak.');
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    // Query untuk menghapus item spesifik dari keranjang user
    $stmt = $conn->prepare("DELETE FROM carts WHERE user_id = ? AND sparepart_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    
    if ($stmt->execute()) {
        // Jika berhasil, kembalikan ke halaman checkout
        header("Location: ../Pages/customer/checkout/checkout.php"); // Sesuaikan path ini!
        exit;
    } else {
        echo "Gagal menghapus item.";
    }

} else {
    // Jika tidak ada data post, kembalikan saja
    header("Location: ../Pages/customer/checkout/checkout.php"); // Sesuaikan path ini!
    exit;
}
?>