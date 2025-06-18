<?php
$pageTitle = 'Management Sparepart';
$activeMenu = 'sparepart';

require '../../../backend/config.php';
// Template header sekarang hanya berisi <head> dan bagian awal <body>
include '../template-header.php'; 
// Template sidebar akan dipanggil setelahnya
?>

<link rel="stylesheet" href="/EfkaWorkshop/assets/libs/sweetalert2/sweetalert2.min.css">
<script src="/EfkaWorkshop/assets/libs/sweetalert2/sweetalert2.all.min.js"></script>

<style>
    /* GANTI ATAU TAMBAHKAN BLOK-BLOK CSS INI DI style.css ADMIN ANDA */

    /* Mengunci ukuran dasar dan menghilangkan scrollbar default */
    html, body {
        height: 100%;
        margin: 0;
        overflow: hidden;
        font-family: 'Inter', sans-serif; /* Pastikan font konsisten */
    }

    /* Pembungkus utama seluruh halaman admin */
    .page-container {
        display: flex;
        flex-direction: column; /* Menyusun Header dan Main Body secara vertikal */
        height: 100%;
    }

    /* Header atas */
    .top-header {
        flex-shrink: 0; /* Mencegah header dari "gepeng" atau menyusut */
    }

    /* Wadah untuk sidebar dan konten utama */
    .main-body {
        display: flex;
        flex-grow: 1; /* Ini adalah kunci #1: membuat wadah ini mengisi sisa tinggi layar */
        overflow: hidden; /* Mencegah munculnya scrollbar yang tidak diinginkan di sini */
    }

    /* Sidebar di kiri */
    .sidebar {
        flex-shrink: 0; /* Mencegah sidebar menyusut lebarnya */
        width: 250px; /* Beri lebar tetap (sesuaikan jika perlu) */
        /* Jika menu sidebar-mu bisa jadi panjang, tambahkan ini: */
        /* overflow-y: auto; */ 
    }

    /* Area konten utama di kanan */
    .main-content {
        flex-grow: 1;         /* Kunci #2: mengisi sisa lebar setelah sidebar */
        overflow-y: auto;     /* JAGOAN KITA: di sinilah scrollbar seharusnya muncul */
        padding: 2rem;        /* Padding untuk memberi jarak konten dari tepi */
        margin-bottom: 70px;
    }
    .content-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .content-header h1 { margin: 0; }
    .content-actions .btn { margin-left: 0.5rem; }
    .feedback-tabs { display: flex; margin-bottom: 1.5rem; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; width: fit-content; }
    .tab-btn { padding: 10px 20px; background-color: #fff; border: none; cursor: pointer; font-weight: 600; color: #555; transition: all 0.3s ease; }
    .tab-btn:first-child { border-right: 1px solid #ddd; }
    .tab-btn.active { background-color: #FFC72C; color: #1F2937; }
    .feedback-viewport { width: 100%; overflow: hidden; }
    .feedback-slider { display: flex; width: 200%; transition: transform 0.4s ease-in-out; }
    .feedback-slider.show-read { transform: translateX(-50%); }
    .feedback-panel { width: 50%; padding: 0 5px; box-sizing: border-box; }
    .products-grid { /* Style grid produk Anda yang sudah ada */ }
    .product-card {
        cursor: pointer;
        transition: all 0.2s ease-in-out; /* Tambahkan transisi biar mulus */
    }

    /* === INI DIA CSS YANG HILANG === */
    .product-card.selected {
        outline: 3px solid #007bff; /* Garis biru tebal di luar */
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4); /* Bayangan biru yang lebih kentara */
        transform: scale(1.02); /* Sedikit membesar biar lebih nonjol */
    }
    /* =============================== */

    .hero-badge {
        /* ... (CSS hero-badge Anda) ... */
    }
</style>

<body>
    <div class="page-container">
        <?php include '../template-sidebar.php'; ?>

        <div class="main-body">
            <main class="main-content">
                <div class="content-header">
                    <h1>Management Sparepart</h1>
                    <div class="content-actions">
                        <button id="btn-reactivate" class="btn btn-tambah" style="display: none;">Aktifkan Kembali</button>
                        <button id="btn-hero" class="btn btn-hero">Jadikan Hero</button>
                        <button id="btn-edit" class="btn btn-edit">Edit</button>
                        <button id="btn-archive" class="btn btn-hapus">Arsipkan</button>
                        <button id="btn-tambah" class="btn btn-tambah">Tambah</button>
                    </div>
                </div>

                <div class="feedback-tabs">
                    <button id="active-btn" class="tab-btn active">Produk Aktif</button>
                    <button id="archived-btn" class="tab-btn">Diarsipkan</button>
                </div>

                <div class="feedback-viewport">
                    <div id="product-slider" class="feedback-slider">
                        
                        <div class="feedback-panel">
                            <div class="products-grid" id="active-products-grid">
                                </div>
                        </div>

                        <div class="feedback-panel">
                            <div class="products-grid" id="archived-products-grid">
                                </div>
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="../script.js"></script> 
    <script>
    // Logika JavaScript akan kita buat setelah ini
    </script>
</body>
</html>

<?php
// Ambil semua data sparepart dari database
$sql = "SELECT id, part_name, description, price, stock, image_url, is_featured, is_active FROM spareparts ORDER BY id DESC";
$result = $conn->query($sql);

$active_products_html = '';
$archived_products_html = '';

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $formatted_price = number_format($row["price"], 0, ',', '.');
        $is_hero_badge = $row['is_featured'] ? '<span class="hero-badge">★ Unggulan</span>' : '';
        
        $card_html = '
        <div class="product-card" data-id="' . $row['id'] . '" data-is-featured="' . $row['is_featured'] . '">
            ' . $is_hero_badge . '
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

        // Pisahkan HTML berdasarkan status is_active
        if ($row['is_active']) {
            $active_products_html .= $card_html;
        } else {
            $archived_products_html .= $card_html;
        }
    }
}

// Jika tidak ada produk aktif
if (empty($active_products_html)) {
    $active_products_html = "<p>Tidak ada produk aktif.</p>";
}
// Jika tidak ada produk yang diarsipkan
if (empty($archived_products_html)) {
    $archived_products_html = "<p>Tidak ada produk yang diarsipkan.</p>";
}
?>

<script>
// Sisipkan HTML yang sudah dibuat PHP ke dalam grid yang sesuai
document.getElementById('active-products-grid').innerHTML = `<?php echo addslashes($active_products_html); ?>`;
document.getElementById('archived-products-grid').innerHTML = `<?php echo addslashes($archived_products_html); ?>`;

document.addEventListener('DOMContentLoaded', function() {
    // === DEKLARASI ELEMEN ===
    const slider = document.getElementById('product-slider');
    const activeBtn = document.getElementById('active-btn');
    const archivedBtn = document.getElementById('archived-btn');
    const productGrid = document.querySelector('.feedback-viewport'); // Target utama untuk event
    
    const tambahBtn = document.getElementById('btn-tambah');
    const arsipBtn = document.getElementById('btn-archive');
    const aktifkanBtn = document.getElementById('btn-reactivate');
    const editBtn = document.getElementById('btn-edit');
    const heroBtn = document.getElementById('btn-hero');

    let selectedProductId = null;
    let isArchivedTab = false;

    // === LOGIKA TABS ===
    activeBtn.addEventListener('click', function() {
        slider.classList.remove('show-read'); // Gunakan class yang sama dari feedback
        activeBtn.classList.add('active');
        archivedBtn.classList.remove('active');
        isArchivedTab = false;
        updateButtonVisibility();
    });

    archivedBtn.addEventListener('click', function() {
        slider.classList.add('show-read');
        activeBtn.classList.remove('active');
        archivedBtn.classList.add('active');
        isArchivedTab = true;
        updateButtonVisibility();
    });

    // === LOGIKA PEMILIHAN KARTU ===
    productGrid.addEventListener('click', function(e) {
        const card = e.target.closest('.product-card');
        if (!card) return;

        productGrid.querySelectorAll('.selected').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        selectedProductId = card.dataset.id;
    });

    // === LOGIKA TOMBOL AKSI ===
    function updateButtonVisibility() {
        if (isArchivedTab) {
            arsipBtn.style.display = 'none';
            heroBtn.style.display = 'none';
            editBtn.style.display = 'none';
            aktifkanBtn.style.display = 'inline-block';
        } else {
            arsipBtn.style.display = 'inline-block';
            heroBtn.style.display = 'inline-block';
            editBtn.style.display = 'inline-block';
            aktifkanBtn.style.display = 'none';
        }
    }

    tambahBtn.addEventListener('click', () => window.location.href = 'admin_tambah_produk.php');
    
    editBtn.addEventListener('click', () => {
        if (!selectedProductId) return Swal.fire('Oops...', 'Pilih produk untuk diedit.', 'warning');
        window.location.href = 'admin_edit_produk.php?id=' + selectedProductId;
    });

    heroBtn.addEventListener('click', () => {
        if (!selectedProductId) return Swal.fire('Oops...', 'Pilih produk untuk dijadikan hero.', 'warning');
        Swal.fire({
            title: 'Jadikan Produk Unggulan?',
            text: "Produk ini akan jadi banner utama di halaman sparepart.",
            icon: 'info', showCancelButton: true, confirmButtonText: 'Ya, Jadikan Hero!'
        }).then(result => {
            if (result.isConfirmed) window.location.href = '../../backend/proses_make_hero.php?id=' + selectedProductId;
        });
    });

    arsipBtn.addEventListener('click', () => {
        if (!selectedProductId) return Swal.fire('Oops...', 'Pilih produk untuk diarsipkan.', 'warning');
        Swal.fire({
            title: 'Arsipkan Produk?',
            text: "Produk ini tidak akan tampil di halaman customer, tapi riwayatnya tetap aman.",
            icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Arsipkan!'
        }).then(result => {
            if (result.isConfirmed) window.location.href = '../../backend/admin_hapus_produk.php?id=' + selectedProductId; // Menggunakan skrip lama yang sudah diubah jadi soft-delete
        });
    });

    aktifkanBtn.addEventListener('click', () => {
        if (!selectedProductId) return Swal.fire('Oops...', 'Pilih produk untuk diaktifkan kembali.', 'warning');
        window.location.href = '../../backend/proses_reactivate_produk.php?id=' + selectedProductId;
    });

    // Panggil sekali di awal
    updateButtonVisibility();
});
</script>