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
    <!-- Navbar -->
    <header class="navbar">
      <div class="navbar-logo">
        <img src="../../../assets/logo-efka.png" alt="EFKA Workshop" class="logo-img" />
      </div>
      <nav class="navbar-menu">
        <a href="../../../index.php" class="nav-link">HOME</a>
        <a href="../../../index.php#aboutus" class="nav-link">ABOUT US</a>
        <a href="#" class="nav-link">SERVICES</a>
        <a href="#" class="nav-link">CONTACT US</a>
      </nav>
      <div class="navbar-icons">
        <div class="cart-icon-container">
            <a href="../checkout/checkout.php"> 
              <img src="../../../assets/icon-cart.png" alt="Cart" class="cart-icon" />
            </a>
            
            <?php
            // Tampilkan badge HANYA JIKA ada item di keranjang (count > 0)
            if ($cart_count > 0):
            ?>
                <span class="cart-indicator"><?php echo $cart_count; ?></span>
            <?php 
            endif; 
            ?>
        </div>
        <?php
        if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
            echo '<span class="welcome-text">Hi, ' . htmlspecialchars($_SESSION["user_name"]) . '</span>';
            echo '<a href="../../../backend/logout.php" class="btn-auth">Logout</a>';
        } else {
            echo '<a id="loginBtn" href="/EfkaWorkshop/Pages/login/login-page.php" class="btn-auth">Login</a>';
            echo '<a id="daftarBtn" href="/EfkaWorkshop/Pages/login/login-page.php" class="btn-auth">Daftar</a>';
        }
        ?>
      </div>
    </header>
    <div class="navbar-spacer"></div>

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
    <script>
      function goHome() {
        window.scrollTo({ top: 0, behavior: "smooth" });
      }

      document.addEventListener('DOMContentLoaded', function () {
          const cartForms = document.querySelectorAll('.sparepart-product-actions form');

          cartForms.forEach(form => {
              form.addEventListener('submit', function (event) {
                  event.preventDefault();

                  fetch(form.action, {
                      method: 'POST',
                      body: new FormData(form)
                  })
                  .then(response => response.json())
                  .then(data => {
                      // Tampilkan pesan dari server
                      alert(data.message);

                      // --- PERUBAHAN DIMULAI DI SINI ---
                      // Cek jika status sukses dan ada data cart_count
                      if (data.status === 'success' && typeof data.cart_count !== 'undefined') {
                          // Cari kontainer ikon keranjang di header
                          const cartContainer = document.querySelector('.cart-icon-container');
                          if (cartContainer) {
                              // Coba cari apakah indikator sudah ada
                              let indicator = cartContainer.querySelector('.cart-indicator');

                              if (!indicator) {
                                  // Jika belum ada, buat elemen span baru
                                  indicator = document.createElement('span');
                                  indicator.className = 'cart-indicator';
                                  cartContainer.appendChild(indicator);
                              }
                              
                              // Perbarui angka di dalam indikator
                              indicator.textContent = data.cart_count;
                          }
                      }
                  })
                  .catch(error => {
                      console.error('Error:', error);
                      alert('Terjadi masalah koneksi.');
                  });
              });
          });
      });
    </script>
  </body>
</html>
