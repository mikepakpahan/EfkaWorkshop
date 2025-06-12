<?php
include '../../../backend/config.php';
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
        <a href="../checkout/checkout.php"> <img src="../../../assets/icon-cart.png" alt="Cart" class="cart-icon" /></a>
        <?php
        if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
            echo '<span class="welcome-text">Hi, ' . htmlspecialchars($_SESSION["user_name"]) . '</span>';
            echo '<a href="../../../backend/logout.php" class="btn-auth">Logout</a>';
        } else {
            echo '<a id="loginBtn" href="Pages/login/login-page.php" class="btn-auth">Login</a>';
            echo '<a id="daftarBtn" href="Pages/login/login-page.php" class="btn-auth">Daftar</a>';
        }
        ?>
      </div>
    </header>
    <div class="navbar-spacer"></div>

    <!-- Hero Banner -->
    <section class="sparepart-hero" style="background: #fff6f6">
      <div class="sparepart-hero-content">
        <div>
          <div class="sparepart-hero-label" style="color: #232323">World Best Quality</div>
          <h1 class="sparepart-hero-title" style="color: #232323">NEW OIL PRODUCT</h1>
          <div class="sparepart-hero-desc" style="color: #232323">deskripsi produk nya aja nanti ini</div>
          <a href="#" class="sparepart-hero-btn">BELI SEKARANG</a>
        </div>
        <div class="sparepart-hero-img">
          <img src="../../../assets/oil-products.png" alt="Oil Product" />
        </div>
      </div>
    </section>

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
                            // Jika BELUM LOGIN, bisa tampilkan link ke halaman login atau tidak menampilkan apa-apa
                            // Di sini kita tidak tampilkan apa-apa agar rapi.
                        }
                        // -- LOGIKA TAMBAHAN SELESAI --

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
          // Ambil semua form 'add-to-cart'
          const cartForms = document.querySelectorAll('.sparepart-product-actions form');

          cartForms.forEach(form => {
              form.addEventListener('submit', function (event) {
                  // 1. Mencegah form untuk submit dan me-refresh halaman
                  event.preventDefault();

                  // 2. Kirim data form menggunakan Fetch API (AJAX)
                  fetch(form.action, {
                      method: 'POST',
                      body: new FormData(form)
                  })
                  .then(response => response.json()) // 3. Ubah jawaban dari server menjadi objek JSON
                  .then(data => {
                      // 4. Tampilkan pesan dari server sebagai alert
                      alert(data.message);
                  })
                  .catch(error => {
                      // Tangani jika ada error jaringan
                      console.error('Error:', error);
                      alert('Terjadi masalah koneksi.');
                  });
              });
          });
      });
    </script>
  </body>
</html>
