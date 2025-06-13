<?php
include 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login terlebih dahulu.']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    // ... (Logika INSERT atau UPDATE tetap sama seperti sebelumnya) ...
    $stmt_check = $conn->prepare("SELECT id FROM carts WHERE user_id = ? AND sparepart_id = ?");
    $stmt_check->bind_param("ii", $user_id, $product_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $stmt_update = $conn->prepare("UPDATE carts SET quantity = quantity + 1 WHERE user_id = ? AND sparepart_id = ?");
        $stmt_update->bind_param("ii", $user_id, $product_id);
        $stmt_update->execute();
    } else {
        $stmt_insert = $conn->prepare("INSERT INTO carts (user_id, sparepart_id, quantity) VALUES (?, ?, 1)");
        $stmt_insert->bind_param("ii", $user_id, $product_id);
        $stmt_insert->execute();
    }

    // --- PERUBAHAN DIMULAI DI SINI ---
    // Setelah berhasil menambah/update, hitung ulang total item di keranjang
    $sql_count = "SELECT COUNT(id) AS total_items FROM carts WHERE user_id = ?";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->bind_param("i", $user_id);
    $stmt_count->execute();
    $result_count = $stmt_count->get_result();
    $row_count = $result_count->fetch_assoc();
    $new_cart_count = $row_count['total_items'];

    // Kirim response sukses DENGAN JUMLAH ITEM TERBARU
    echo json_encode([
        'status' => 'success', 
        'message' => 'Produk berhasil ditambahkan ke keranjang!',
        'cart_count' => $new_cart_count // Info tambahan
    ]);

    $conn->close();
    exit;

} else {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan.']);
    exit;
}
?>