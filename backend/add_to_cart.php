<?php
include 'config.php';

// Atur header agar outputnya adalah JSON
header('Content-Type: application/json');

// Cek jika pengguna belum login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Kirim response error dalam format JSON dan hentikan skrip
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login terlebih dahulu untuk menambahkan item.']);
    exit;
}

// Cek jika data dikirim dengan method POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity_to_add = 1;

    // Cek apakah item sudah ada di keranjang
    $stmt_check = $conn->prepare("SELECT id, quantity FROM carts WHERE user_id = ? AND sparepart_id = ?");
    $stmt_check->bind_param("ii", $user_id, $product_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // JIKA SUDAH ADA: UPDATE quantity
        $cart_item = $result_check->fetch_assoc();
        $stmt_update = $conn->prepare("UPDATE carts SET quantity = quantity + ? WHERE id = ?");
        $stmt_update->bind_param("ii", $quantity_to_add, $cart_item['id']);
        $stmt_update->execute();
    } else {
        // JIKA BELUM ADA: INSERT baris baru
        $stmt_insert = $conn->prepare("INSERT INTO carts (user_id, sparepart_id, quantity) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("iii", $user_id, $product_id, $quantity_to_add);
        $stmt_insert->execute();
    }

    // Kirim response sukses dalam format JSON
    echo json_encode(['status' => 'success', 'message' => 'Produk berhasil ditambahkan ke keranjang!']);

    $conn->close();
    exit;

} else {
    // Jika ada data yang tidak valid
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan.']);
    exit;
}
?>