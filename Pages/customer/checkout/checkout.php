<?php
// Pastikan path ke config.php sudah benar dari lokasi file ini
include '../../../backend/config.php';

// KEAMANAN: Wajib login untuk akses halaman ini
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Arahkan ke halaman login jika belum login
    header("Location: ../../login/login-page.php");
    exit;
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Query untuk mengambil semua item di keranjang milik user yang sedang login.
// Kita menggunakan JOIN untuk mendapatkan detail produk (nama, harga, gambar) dari tabel spareparts.
$sql = "SELECT 
            s.id AS product_id, 
            s.part_name, 
            s.price, 
            s.image_url,
            c.quantity
        FROM carts c
        JOIN spareparts s ON c.sparepart_id = s.id
        WHERE c.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$grand_total = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sub_total = $row['price'] * $row['quantity'];
        $grand_total += $sub_total;
        $row['sub_total'] = $sub_total;
        $cart_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Efka Workshop</title>
    <style>
        /* Contoh CSS sederhana, bisa Anda pindah ke file .css */
        body { font-family: 'Open Sans Condensed', sans-serif; background-color: #f9f9f9; }
        .container { max-width: 960px; margin: 40px auto; padding: 20px; background: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; margin-bottom: 30px; }
        .cart-table { width: 100%; border-collapse: collapse; }
        .cart-table th, .cart-table td { padding: 15px; border-bottom: 1px solid #ddd; text-align: left; }
        .cart-table th { background-color: #f2f2f2; }
        .product-info { display: flex; align-items: center; }
        .product-info img { width: 80px; height: 80px; object-fit: cover; margin-right: 15px; }
        .remove-btn { color: #e74c3c; text-decoration: none; font-weight: bold; }
        .cart-total { text-align: right; margin-top: 30px; }
        .cart-total h3 { font-size: 24px; }
        .btn-checkout { display: block; width: 250px; float: right; margin-top: 20px; padding: 15px; background-color: #2ecc71; color: white; text-align: center; text-decoration: none; font-weight: bold; border-radius: 5px; }
        .empty-cart { text-align: center; padding: 50px; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Keranjang Belanja Anda</h1>

        <?php if (!empty($cart_items)): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Kuantitas</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['part_name']); ?>">
                                    <span><?php echo htmlspecialchars($item['part_name']); ?></span>
                                </div>
                            </td>
                            <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>Rp <?php echo number_format($item['sub_total'], 0, ',', '.'); ?></td>
                            <td>
                                <form action="../../../backend/remove_from_cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                    <button type="submit" class="remove-btn" onclick="return confirm('Yakin ingin menghapus item ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-total">
                <h3>Total Belanja: Rp <?php echo number_format($grand_total, 0, ',', '.'); ?></h3>
                <a href="#" class="btn-checkout">Lanjutkan ke Pembayaran</a>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <h2>Keranjang belanja Anda masih kosong.</h2>
                <a href="../spareparts/sparepart.php">Mulai Belanja Sekarang</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>