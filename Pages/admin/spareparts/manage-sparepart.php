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
$sql = "SELECT id, part_name, description, price, stock, image_url, is_featured FROM spareparts ORDER BY id DESC";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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

            <main class="main-content">
                <div class="content-actions">
                    <button id="btn-edit" class="btn btn-edit">Edit</button>
                    <button id="btn-hapus" class="btn btn-hapus">Hapus</button>
                    <button id="btn-tambah" class="btn btn-tambah">Tambah</button>
                    <button id="btn-hero" class="btn" style="background-color: #17a2b8; color: white;">Jadikan Hero</button>
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
                            // PENTING: tambahkan atribut data-id dan data-is-featured
                            echo '
                            <div class="product-card" data-id="' . $row['id'] . '" data-is-featured="' . $row['is_featured'] . '">
                                
                                ' . ($row['is_featured'] ? '<span class="hero-badge">★ Unggulan</span>' : '') . '
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
    // Bungkus SEMUA kode di dalam event listener ini
    document.addEventListener('DOMContentLoaded', function() {

        // =================================================
        // DEKLARASI ELEMEN-ELEMEN
        // =================================================
        const menuToggleBtn = document.getElementById('menu-toggle-btn');
        const sidebar = document.querySelector('.sidebar');
        const itemGrid = document.querySelector('.products-grid, .service-grid'); // Menargetkan kedua jenis grid
        const tambahBtn = document.getElementById('btn-tambah');
        const hapusBtn = document.getElementById('btn-hapus');
        const editBtn = document.getElementById('btn-edit');
        const heroBtn = document.getElementById('btn-hero'); // Untuk sparepart
        
        let selectedItemId = null; // Satu variabel untuk menyimpan ID yang dipilih

        // =================================================
        // EVENT LISTENERS
        // =================================================

        // 1. Logika untuk Menu Toggle (Sidebar)
        if (menuToggleBtn && sidebar) {
            menuToggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('is-open');
                menuToggleBtn.classList.toggle('is-open');
            });
        }

        // 2. Logika untuk Memilih Item (Card)
        if (itemGrid) {
            itemGrid.addEventListener('click', function(e) {
                const card = e.target.closest('.product-card, .service-card');
                if (!card) return;

                // Hapus seleksi dari card lain yang mungkin terpilih
                itemGrid.querySelectorAll('.selected').forEach(selectedCard => {
                    selectedCard.classList.remove('selected');
                });

                // Tambahkan seleksi ke card yang baru diklik
                card.classList.add('selected');
                selectedItemId = card.dataset.id; // Simpan ID item yang dipilih
            });
        }

        // 3. Logika untuk Tombol Tambah
        if (tambahBtn) {
            tambahBtn.addEventListener('click', function() {
                // Cek apakah ini halaman sparepart atau service
                if (document.querySelector('.products-grid')) {
                    window.location.href = 'admin-tambah-produk.php';
                } else if (document.querySelector('.service-grid')) {
                    window.location.href = 'admin-tambah-service.php';
                }
            });
        }

        // 4. Logika untuk Tombol Hapus (dengan SweetAlert2)
        if (hapusBtn) {
            hapusBtn.addEventListener('click', function() {
                if (!selectedItemId) {
                    Swal.fire('Oops...', 'Pilih item yang ingin dihapus terlebih dahulu.', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Aksi ini akan menghapus data secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let deleteUrl = '';
                        if (document.querySelector('.products-grid')) {
                            deleteUrl = '../../../backend/admin_hapus_produk.php?id=';
                        } else if (document.querySelector('.service-grid')) {
                            deleteUrl = '../../../backend/admin_hapus_service.php?id=';
                        }
                        window.location.href = deleteUrl + selectedItemId;
                    }
                });
            });
        }

        // 5. Logika untuk Tombol Edit (Lebih Aman)
        if (editBtn) {
            editBtn.addEventListener('click', function() {
                if (!selectedItemId) {
                    Swal.fire('Oops...', 'Pilih item yang ingin diedit terlebih dahulu.', 'warning');
                    return;
                }
                
                let editUrl = '';
                if (document.querySelector('.products-grid')) {
                    editUrl = 'admin-edit-produk.php?id=';
                } else if (document.querySelector('.service-grid')) {
                    editUrl = 'admin-edit-service.php?id=';
                }
                window.location.href = editUrl + selectedItemId;
            });
        }

        // 6. Logika untuk Tombol "Jadikan Hero" (jika ada)
        if(heroBtn) {
            heroBtn.addEventListener('click', function() {
                if (!selectedItemId) {
                    Swal.fire('Oops...', 'Pilih produk yang ingin dijadikan hero banner.', 'warning');
                    return;
                }
                // ... (logika SweetAlert untuk hero yang sudah kita buat sebelumnya) ...
                Swal.fire({
                    title: 'Jadikan Produk Unggulan?',
                    text: "Produk ini akan ditampilkan di banner utama halaman sparepart.",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Jadikan Hero!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/EfkaWorkshop/backend/proses_make_hero.php?id=' + selectedItemId;
                    }
                })
            });
        }
    });
    </script>
    <script src="../script.js"></script>
</body>
</html>