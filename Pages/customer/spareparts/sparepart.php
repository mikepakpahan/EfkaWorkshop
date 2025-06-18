<?php
include '../../../backend/config.php';

$cart_count = 0;

// Cek jika pengguna sudah login, baru kita hitung keranjangnya
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $user_id = $_SESSION['user_id'];

    // Query untuk MENGHITUNG jumlah baris/jenis item di tabel carts untuk user ini
    $sql_count = "SELECT COUNT(id) AS total_items FROM carts WHERE user_id = ?";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->bind_param("i", $user_id);
    $stmt_count->execute();
    $result_count = $stmt_count->get_result();
    
    if ($result_count) {
        $row_count = $result_count->fetch_assoc();
        $cart_count = $row_count['total_items'];
    }
}

// --- LOGIKA BARU UNTUK HERO BANNER ---
$hero_product = null; // Inisialisasi variabel

// Query untuk mengambil satu produk yang ditandai sebagai featured
$sql_hero = "SELECT id, part_name, description, image_url FROM spareparts WHERE is_featured = 1 LIMIT 1";
$result_hero = $conn->query($sql_hero);

if ($result_hero && $result_hero->num_rows > 0) {
    $hero_product = $result_hero->fetch_assoc();
}
// --- AKHIR LOGIKA HERO BANNER ---
?>

?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sparepart - Efka Workshop</title>
    <link rel="stylesheet" href="sparepart.css" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <style>
      .add-cart-btn {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
      }

      .welcome-text {
      color: white;
      font-size: 1.5rem;
      margin-right: 15px;
      align-self: center;
      }
    </style>
  </head>
  <body style="font-family: 'Open Sans Condensed', Arial, sans-serif">
    <?php
    include '../header.php';
    ?>

    <!-- Hero Banner -->
    <?php 
    // Tampilkan section ini HANYA JIKA ada produk yang ditandai sebagai hero
    if ($hero_product): 
    ?>
        <section class="sparepart-hero" style="background: #fff6f6">
            <div class="sparepart-hero-content">
                <div>
                    <div class="sparepart-hero-label" style="color: #232323">Produk Unggulan Kami</div>
                    <h1 class="sparepart-hero-title" style="color: #232323"><?php echo htmlspecialchars($hero_product['part_name']); ?></h1>
                    <div class="sparepart-hero-desc" style="color: #232323"><?php echo htmlspecialchars($hero_product['description']); ?></div>
                    <button id="hero-add-to-cart-btn" class="sparepart-hero-btn" data-product-id="<?php echo $hero_product['id']; ?>">
                        BELI SEKARANG
                    </button>
                </div>
                <div class="sparepart-hero-img">
                    <img src="<?php echo htmlspecialchars($hero_product['image_url']); ?>" alt="<?php echo htmlspecialchars($hero_product['part_name']); ?>" />
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Latest Products -->
    <section class="sparepart-products-section">
      <div class="sparepart-products-container">
        <h2 class="sparepart-products-title">LATEST PRODUCTS</h2>
        <div class="sparepart-products-grid">
          <?php
          // 1. Query untuk mengambil semua sparepart yang stoknya ada
          $sql = "SELECT id, part_name, description, price, image_url FROM spareparts WHERE stock > 0 ORDER BY id";
          $result = $conn->query($sql);

          // 2. Cek jika ada produk
          if ($result->num_rows > 0) {
              // 3. Looping untuk setiap produk
              while($row = $result->fetch_assoc()) {
                  // Format harga agar lebih mudah dibaca
                  $formatted_price = number_format($row["price"], 0, ',', '.');
                  
                  // 4. Tampilkan HTML card untuk setiap produk menggunakan template Anda
                  echo '
                    <div class="sparepart-product-card">
                        <img src="' . htmlspecialchars($row["image_url"]) . '" alt="' . htmlspecialchars($row["part_name"]) . '" class="sparepart-product-img" />
                        <div class="sparepart-product-title">' . htmlspecialchars($row["part_name"]) . '</div>
                        <div class="sparepart-product-desc">' . htmlspecialchars($row["description"]) . '</div>
                        <div class="sparepart-product-price">Rp ' . $formatted_price . '</div>
                        <div class="sparepart-product-actions">';
                        
                        // -- LOGIKA TAMBAHAN DIMULAI DI SINI --
                        if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
                            // Jika PENGGUNA SUDAH LOGIN, tampilkan form add to cart
                            echo '
                            <form action="../../../backend/add_to_cart.php" method="POST">
                                <input type="hidden" name="product_id" value="' . $row['id'] . '">
                                <button type="submit" class="add-cart-btn">
                                    <img src="../../../assets/add-cart.png" alt="Add to Cart" class="add-cart-icon" />
                                </button>
                            </form>';
                        } else {
                        }

                    echo '
                        </div>
                    </div>
                    ';
              }
          } else {
              echo "<p>Belum ada sparepart yang dijual saat ini.</p>";
          }
          ?>
        </div>
      </div>
    </section>
    <!-- Floating Home Button - Fixed di sudut kanan bawah -->
    <button onclick="goHome()" class="floating-home-btn">
      <img src="../../../assets/arrow.png" alt="Home" class="floating-home-img" />
    </button>
<?php
      include '../footer.php';
    ?>
    <script>
      // Fungsi ini bisa diletakkan di luar atau di dalam DOMContentLoaded
      function goHome() {
          window.scrollTo({ top: 0, behavior: "smooth" });
      }

      document.addEventListener('DOMContentLoaded', function () {
          
          // ==========================================================
          // FUNGSI JAGOAN UNTUK MENAMBAHKAN ITEM KE KERANJANG (AJAX)
          // ==========================================================
          function handleAddToCart(productId) {
              if (!productId) {
                  alert('ID Produk tidak ditemukan!');
                  return;
              }

              // Siapkan data untuk dikirim ke backend
              const formData = new FormData();
              formData.append('product_id', productId);

              // Kirim data menggunakan Fetch API
              fetch('../../../backend/add_to_cart.php', { // Pastikan path ini benar
                  method: 'POST',
                  body: formData
              })
              .then(response => response.json())
              .then(data => {
                  // Tampilkan notifikasi (kita pakai SweetAlert yang sudah ada)
                  Swal.fire({
                      title: data.status === 'success' ? 'Berhasil!' : 'Oops...',
                      text: data.message,
                      icon: data.status, // 'success' atau 'error'
                      timer: 1500, // Notifikasi hilang setelah 1.5 detik
                      showConfirmButton: false
                  });

                  // Update indikator keranjang jika sukses
                  if (data.status === 'success' && typeof data.cart_count !== 'undefined') {
                      const cartContainer = document.querySelector('.cart-icon-container');
                      if (cartContainer) {
                          let indicator = cartContainer.querySelector('.cart-indicator');
                          if (!indicator) {
                              indicator = document.createElement('span');
                              indicator.className = 'cart-indicator';
                              cartContainer.appendChild(indicator);
                          }
                          indicator.textContent = data.cart_count;
                      }
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
                  Swal.fire('Error', 'Terjadi masalah koneksi.', 'error');
              });
          }


          // ==========================================================
          // 1. EVENT LISTENER UNTUK TOMBOL-TOMBOL DI GRID PRODUK
          // ==========================================================
          const cartForms = document.querySelectorAll('.sparepart-product-actions form');
          cartForms.forEach(form => {
              form.addEventListener('submit', function (event) {
                  event.preventDefault(); // Mencegah refresh
                  const productId = form.querySelector('input[name="product_id"]').value;
                  handleAddToCart(productId); // Panggil fungsi jagoan
              });
          });


          // ==========================================================
          // 2. EVENT LISTENER UNTUK TOMBOL "BELI SEKARANG" DI HERO BANNER
          // ==========================================================
          const heroBtn = document.getElementById('hero-add-to-cart-btn');
          if (heroBtn) {
              heroBtn.addEventListener('click', function () {
                  const productId = this.dataset.productId; // Ambil ID dari atribut data-*
                  handleAddToCart(productId); // Panggil fungsi jagoan yang sama
              });
          }

      });
  </script>
  </body>
</html>
