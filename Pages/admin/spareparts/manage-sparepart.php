<?php
include '../../../backend/config.php';

$pageTitle = 'Management Sparepart';
$activeMenu = 'sparepart';

include '../template-header.php';
include '../template-sidebar.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak. Halaman ini hanya untuk Admin.");
}

// Ambil semua data sparepart dari database
$sql = "SELECT id, part_name, description, price, stock, image_url FROM spareparts ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Management Sparepart - EFKA Workshop</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <style>
        /* Tambahan CSS untuk menandai item yang dipilih */
        .product-card.selected {
            border: 2px solid #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }
        .product-card {
            cursor: pointer;
        }
    </style>
  </head>
<body>
    <!-- <div class="page-container">
        <header class="top-header">
            <button class="menu-toggle" id="menu-toggle-btn" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <h1 class="page-title">Management Sparepart</h1>
            <div class="header-logo">
                <img src="/EfkaWorkshop/assets/logo-efka.png" alt="EFKA Workshop Logo" class="logo">
            </div>
        </header>
        
        <div class="main-body">
            <aside class="sidebar">
        <nav class="sidebar-menu">
            <a href="../dashboard/dashboard.php" class="sidebar-link">
                <img src="/EfkaWorkshop/assets/icons/dashboard.png" alt="Dashboard icon">
                <span>Dashboard</span>
            </a>
            <a href="#" class="sidebar-link active">
                <img src="/EfkaWorkshop/assets/icons/manage-spareparts.png" alt="Management Sparepart icon">
                <span>Management Sparepart</span>
            </a>
            <a href="../service/manage-service.php" class="sidebar-link">
                <img src="/EfkaWorkshop/assets/icons/manage-services.png" alt="Management Service icon">
                <span>Management Service</span>
            </a>
            <a href="../spareparts/sparepart-order.php" class="sidebar-link">
                <img src="/EfkaWorkshop/assets/icons/sparepart-order.png" alt="Sparepart Order icon">
                <span>Sparepart Order</span>
            </a>
            <a href="#" class="sidebar-link">
                <img src="/EfkaWorkshop/assets/icons/pending-service.png" alt="Pending Service icon">
                <span>Pending Service</span>
            </a>
            <a href="#" class="sidebar-link">
                <img src="/EfkaWorkshop/assets/icons/queue.png" alt="Queue icon">
                <span>Queue</span>
            </a>
            <a href="../user/user.php" class="sidebar-link">
                <img src="/EfkaWorkshop/assets/icons/user.png" alt="User icon">
                <span>User</span>
            </a>
        </nav>
            </aside> -->

            <main class="main-content">
                <div class="content-actions">
                    <button id="btn-edit" class="btn btn-edit">Edit</button>
                    <button id="btn-hapus" class="btn btn-hapus">Hapus</button>
                    <button id="btn-tambah" class="btn btn-tambah">Tambah</button>
                    </div>

                <div class="products-grid">
                    <?php
                    // Cek jika ada data produk
                    if ($result && $result->num_rows > 0) {
                        // Looping untuk setiap produk
                        while($row = $result->fetch_assoc()) {
                            // Format harga agar mudah dibaca
                            $formatted_price = number_format($row["price"], 0, ',', '.');
                            
                            // Tampilkan HTML card dengan data dari database
                            // PENTING: tambahkan atribut data-id untuk menyimpan ID produk
                            echo '
                            <div class="product-card" data-id="' . $row['id'] . '">
                                <img src="' . htmlspecialchars($row["image_url"]) . '" alt="' . htmlspecialchars($row["part_name"]) . '" class="product-img">
                                <div class="product-info">
                                    <h3 class="product-title">' . htmlspecialchars($row["part_name"]) . '</h3>
                                    <p class="product-description">' . htmlspecialchars($row["description"]) . '</p>
                                    <div class="product-detail">
                                        <span>Harga</span>
                                        <strong>Rp ' . $formatted_price . '</strong>
                                    </div>
                                    <div class="product-detail">
                                        <span>Stok</span>
                                        <strong>' . $row['stock'] . '</strong>
                                    </div>
                                </div>
                            </div>';
                        }
                    } else {
                        echo "<p>Belum ada produk di database.</p>";
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>
    <script>
        const menuToggleBtn = document.getElementById('menu-toggle-btn');
            const sidebar = document.querySelector('.sidebar');

            menuToggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('is-open');
                menuToggleBtn.classList.toggle('is-open');
            });
        document.addEventListener('DOMContentLoaded', function() {
            const productGrid = document.querySelector('.products-grid');
            let selectedProductId = null;

            // 1. Logika untuk memilih produk
            productGrid.addEventListener('click', function(e) {
                const card = e.target.closest('.product-card');
                if (!card) return;

                // Hapus seleksi dari card lain
                document.querySelectorAll('.product-card.selected').forEach(selectedCard => {
                    selectedCard.classList.remove('selected');
                });

                // Tambah seleksi ke card yang diklik
                card.classList.add('selected');
                selectedProductId = card.dataset.id; // Simpan ID produk yang dipilih
            });

            // 2. Logika untuk tombol Tambah
            document.getElementById('btn-tambah').addEventListener('click', function() {
                // Arahkan ke halaman tambah produk yang sudah kita buat
                window.location.href = 'admin-tambah-produk.php'; // Sesuaikan path jika perlu
            });

            // 3. Logika untuk tombol Hapus
            document.getElementById('btn-hapus').addEventListener('click', function() {
                if (!selectedProductId) {
                    alert('Pilih produk yang ingin dihapus terlebih dahulu.');
                    return;
                }

                if (confirm('Apakah Anda yakin ingin menghapus produk ini? Aksi ini tidak bisa dibatalkan.')) {
                    // Arahkan ke skrip penghapusan dengan mengirim ID
                    window.location.href = '../../../backend/admin_hapus_produk.php?id=' + selectedProductId;
                }
            });

            // 4. Logika untuk tombol Edit (akan kita kembangkan nanti)
            document.getElementById('btn-edit').addEventListener('click', function() {
                window.location.href = 'admin-edit-produk.php?id=' + selectedProductId;
            });
        });
    </script>
    <script src="../script.js"></script>
</body>
</html>