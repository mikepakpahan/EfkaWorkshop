<?php
include 'backend/config.php';
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="Pages/customer/landing/style.css" />
    <title>Efka Workshop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="scripts.js"></script>
    <style>
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
        <img src="assets/logo-efka.png" alt="Logo EFKA" class="logo-img" />
    </div>
    <nav id="mainNav" class="navbar-menu">
        <a href="#aboutus" class="nav-link">About Us</a>
        <a href="Pages/customer/spareparts/sparepart.php" class="nav-link">Spareparts</a>
        <a href="#services" class="nav-link">Services</a> 
        <a href="#footer" class="nav-link">Contact Us</a> </nav>
    
    <div class="navbar-auth">
        <?php
        if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
            echo '<span class="welcome-text">Hi, ' . htmlspecialchars($_SESSION["user_name"]) . '</span>';
            echo '<a href="backend/logout.php" class="btn-auth">Logout</a>';
        } else {
            echo '<a id="loginBtn" href="Pages/login/login-page.php" class="btn-auth">Login</a>';
            echo '<a id="daftarBtn" href="Pages/login/login-page.php" class="btn-auth">Daftar</a>';
        }
        ?>
    </div>
    
    <button id="navToggle" class="navbar-toggle" aria-label="Menu">
        <span class="navbar-toggle-bar"></span>
        <span class="navbar-toggle-bar"></span>
        <span class="navbar-toggle-bar"></span>
    </button>
</header>
    <!-- Mobile Nav Dropdown -->
    <nav id="mobileNav" class="mobile-nav-dropdown">
      <a href="#" class="mobile-nav-link">About Us</a>
      <a href="sparepart.html" class="mobile-nav-link">Spareparts</a>
      <a href="#services" class="mobile-nav-link">Services</a>
      <a href="#footer" class="mobile-nav-link">Contact Us</a>
      <div class="mobile-auth">
        <?php
        if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
            echo '<span class="welcome-text">Hi, ' . htmlspecialchars($_SESSION["user_name"]) . '</span>';
            echo '<a href="backend/logout.php" class="btn-auth">Logout</a>';
        } else {
            echo '<a id="loginBtn" href="Pages/login/login-page.php" class="btn-auth">Login</a>';
            echo '<a id="daftarBtn" href="Pages/login/login-page.php" class="btn-auth">Daftar</a>';
        }
        ?>
      </div>
    </nav>
    <div class="navbar-spacer"></div>

    <!-- Hero -->
    <section class="hero-section">
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <div class="hero-title-group hero-title-group-large hero-title-group-left">
          <div class="hero-subtitle">BERKENDARA DENGAN NYAMAN</div>
          <h1 class="hero-title">
            Premium Motor <span class="highlight">Detailing</span><br />
            & Repair <span class="highlight">Solutions</span>
          </h1>
          <div class="hero-desc">lapet ini gadong kocak lapet ini gadong kocak lapet ini gadong kocak lapet ini gadong kocak lapet ini gadong kocak lapet ini gadong kocak lapet ini gadong kocak</div>
          <div class="hero-actions">
            <?php
            if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
                // Jika SUDAH LOGIN, tombol akan berfungsi normal (scroll ke form)
                echo '<a href="#aboutus" class="btn-primary">BUAT JANJI</a>';
            } else {
                // Jika BELUM LOGIN, tombol akan memunculkan alert
                echo '<a href="#" onclick="alert(\'Anda harus login terlebih dahulu untuk membuat janji.\');" class="btn-primary">BUAT JANJI</a>';
            }
            ?>
            <a href="#services" class="btn-link">READ MORE &rarr;</a>
          </div>
        </div>
        <div class="hero-features">
          <div class="feature-card">
            <div class="feature-title">Expertise & Profesional</div>
            <div class="feature-desc">Kami memiliki mekanis yang handal dan profesional dalam menangani berbagai jenis motor</div>
          </div>
          <div class="feature-card">
            <div class="feature-title">24/7 Ready Support</div>
            <div class="feature-desc">Layanan kami tersedia 24 jam setiap hari untuk menangani permintaan customer</div>
          </div>
          <div class="feature-card">
            <div class="feature-title">Free Consulting</div>
            <div class="feature-desc">Kami menyediakan layanan konsultasi gratis kepada customer untuk membantu customer merawat motor.</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Layanan Servis -->
    <section class="min-h-screen w-full py-16 px-0" id="services" style="background: #1b2649">
          <div class="max-w-7xl mx-auto px-4">
              <div class="mb-12">
                  <div class="text-[#FFC72C] text-base sm:text-lg font-bold mb-2 tracking-widest uppercase">Our Services</div>
                  <h2 class="text-white text-4xl sm:text-5xl font-bold mb-2">Delivering <span class="text-[#FFC72C]">Superior</span> Motor Detailing<br />& Repair</h2>
              </div>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                  <?php
                  // 1. Buat query untuk mengambil semua data dari tabel services
                  $sql = "SELECT service_name, description, image_url FROM services ORDER BY id";
                  $result = $conn->query($sql);

                  // 2. Cek apakah ada data yang ditemukan
                  if ($result->num_rows > 0) {
                      // 3. Lakukan looping untuk setiap baris data
                      while($row = $result->fetch_assoc()) {
                          // 4. Tampilkan HTML card untuk setiap data, dengan nilai dinamis
                          //    Kita gunakan struktur HTML card Anda sebagai template.
                          echo '
                          <div class="bg-transparent">
                              <div class="relative">
                                  <img src="' . htmlspecialchars($row["image_url"]) . '" alt="' . htmlspecialchars($row["service_name"]) . '" class="w-full h-56 object-cover rounded-t-md" />
                                  <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-80 p-4 rounded-b-md">
                                      <div class="text-white font-bold text-lg mb-1">' . htmlspecialchars($row["service_name"]) . '</div>
                                      <div class="text-gray-200 text-sm">' . htmlspecialchars($row["description"]) . '</div>
                                  </div>
                              </div>
                          </div>';
                      }
                  } else {
                      // Tampilkan pesan jika tidak ada data layanan
                      echo '<p class="text-white">Saat ini belum ada layanan yang tersedia.</p>';
                  }
                  ?>
              </div>
              </div>
      </section>

    <!-- About Us -->
    <section class="min-h-screen w-full pt-20 pb-16 px-0" id="aboutus" style="background: #0c0a27">
      <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-8">
        <!-- Left Content -->
        <div class="flex-1 text-white about-left-top">
          <div class="text-[#FFC72C] text-base sm:text-lg font-bold mb-2 tracking-widest uppercase">WHO WE ARE ?</div>
          <h2 class="whitespace-nowrap text-3xl sm:text-4xl md:text-5xl font-bold mb-4 leading-tight">Motor Detailing And Repair<br />Services You Can Rely On</h2>
          <div class="text-gray-200 text-base sm:text-lg mb-6 max-w-xl">lapet ni gadong kocak lapet ni gadong kocak lapet ni gadong kocak lapet ni gadong kocak lapet ni gadong kocak lapet ni gadong kocak lapet ni gadong kocak</div>
          <div class="flex flex-wrap gap-8 mb-6">
            <div class="flex items-center gap-3">
              <img src="assets/icon-staff.png" alt="Staff" class="w-8 h-8" />
              <div>
                <div class="font-semibold text-white">Professional & Creative Staff</div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <img src="assets/icon-warranty.png" alt="Warranty" class="w-8 h-8" />
              <div>
                <div class="font-semibold text-white">Warranties & Guarantees</div>
              </div>
            </div>
          </div>
          <div class="flex items-center gap-3 mt-2">
            <img src="assets/telephone.png" alt="Telepon" class="w-6 h-6" />
            <span class="text-white text-base font-semibold">+62 812 3456 7890</span>
          </div>
        </div>

        <!-- Right Form -->
        <div class="about-right">
          <form class="request-form">
            <input type="text" placeholder="Name" class="form-input" />
            <input type="email" placeholder="Email" class="form-input" />
            <textarea id="message" placeholder="Message" rows="6" class="form-textarea"></textarea>
            <?php
            if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
                // Jika SUDAH LOGIN, tombol akan berfungsi normal (scroll ke form)
                echo '<button type="submit" class="form-btn">REQUEST A SERVICE</button>';
            } else {
                // Jika BELUM LOGIN, tombol akan memunculkan alert
                echo '<button type="submit" onclick="alert(\'Anda harus login terlebih dahulu untuk membuat janji.\');" class="form-btn">REQUEST A SERVICE</button>';
            }
            ?>
            
          </form>
        </div>
      </div>
    </section>

    <!-- Floating Home Button - Fixed di sudut kanan bawah -->
    <button onclick="goHome()" class="fixed bottom-6 right-6 hover:bg-gray-700 text-white p-4 rounded-full shadow-2xl hover:shadow-3xl transition-all duration-300 z-50 hover:scale-110">
      <img src="assets/arrow.png" alt="Home" class="w-10 h-10" />
    </button>
    <script>
      // Responsive Navbar Toggle
      const navToggle = document.getElementById("navToggle");
      const mobileNav = document.getElementById("mobileNav");
      navToggle.addEventListener("click", function () {
        mobileNav.classList.toggle("open");
      });
      // Hide mobile nav on link click
      mobileNav.querySelectorAll("a").forEach((link) => {
        link.addEventListener("click", () => {
          mobileNav.classList.remove("open");
        });
      });
      function goHome() {
        window.scrollTo({ top: 0, behavior: "smooth" });
      }
      // Feature card hover/click effect
      document.querySelectorAll(".feature-card").forEach((card) => {
        card.addEventListener("mouseenter", function () {
          this.classList.add("feature-card-hover");
        });
        card.addEventListener("mouseleave", function () {
          this.classList.remove("feature-card-hover");
        });
        card.addEventListener("click", function () {
          document.querySelectorAll(".feature-card").forEach((c) => c.classList.remove("feature-card-active"));
          this.classList.add("feature-card-active");
        });
      });
    </script>
  </body>

  <!-- Footer -->
  <footer id="footer" class="bg-[#13162B] text-white py-0">
    <div class="w-full px-0 pt-0 pb-0" style="background: #13162b">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between max-w-[1200px] mx-auto py-6 px-4 gap-4">
        <!-- Logo & Tagline -->
        <div class="flex flex-col md:items-start w-full md:w-auto">
          <div class="flex items-center w-full">
            <div class="flex items-center justify-center rounded-2xl w-full ml-[-0.5cm]" style="background: #ffb300; height: 75px">
              <img src="assets/logo-efka.png" alt="EFKA Logo" class="object-contain h-[80px] w-auto mx-auto" />
            </div>
          </div>
          <div class="mt-2 text-xs text-white font-mono text-center md:text-center" style="letter-spacing: 1px">Motorcycle Service &amp;<br />Repair</div>
        </div>
        <!-- Address & Contact -->
        <div class="flex-1 flex flex-col justify-center md:ml-9">
          <div class="text-white font-mono text-sm mb-2" style="letter-spacing: 1px">
            <span> Jl. Mekar Jaya No. 27, RT 03/RW 05 Kelurahan Tanjung Sari, Kecamatan Medan Selayang </span>
            <span> Kota Medan, Sumatera Utara 20131 </span>
          </div>
          <div class="text-white font-mono text-sm mb-2" style="letter-spacing: 1px">(061) 7881 2345</div>
          <a href="mailto:info@efkaworkshop.com" class="text-white font-mono text-sm underline mb-2" style="letter-spacing: 1px">info@efkaworkshop.com</a>
        </div>
        <!-- Social & Copyright -->
        <div class="flex flex-col items-center md:items-end gap-2 w-full md:w-auto">
          <div class="flex gap-4 mb-2">
            <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded bg-transparent hover:bg-[#23294A] transition">
              <img src="assets/logo-ig.png" alt="Instagram" class="w-5 h-5" />
            </a>
            <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded bg-transparent hover:bg-[#23294A] transition">
              <img src="assets/logo-wa.png" alt="WhatsApp" class="w-5 h-5" />
            </a>
            <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded bg-transparent hover:bg-[#23294A] transition">
              <img src="assets/logo-x.png" alt="Twitter/X" class="w-5 h-5" />
            </a>
          </div>
          <div class="text-xs text-white font-mono text-center md:text-right mb-2" style="letter-spacing: 1px">© 2025 Efka Workshop. All Rights reserved</div>
        </div>
      </div>
    </div>
  </footer>
</html>
