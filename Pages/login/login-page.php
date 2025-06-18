<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <title>Login Page | Efka Workshop</title>
  </head>

  <body>
    <div class="container" id="container">
      <div class="form-container sign-up">
          <form id="signup-form" action="../../backend/register.php" method="POST">
              <h1>Create Account</h1>
              <span>or use your email for registration</span>
              <input type="text" placeholder="Name" name="name" required />
              <input type="email" placeholder="Email" name="email" required />

              <div class="password-wrapper">
                  <input type="password" id="signup-password" placeholder="Password" name="password" required />
                  <i class="fas fa-eye" id="toggle-password"></i>
              </div>

              <div id="password-criteria" class="password-criteria">
                  <p id="length" class="criteria-item invalid">Minimal 8 karakter</p>
                  <p id="capital" class="criteria-item invalid">Mengandung huruf besar</p>
                  <p id="number" class="criteria-item invalid">Mengandung angka</p>
                  <p id="special" class="criteria-item invalid">Mengandung simbol (!@#$...)</p>
              </div>
              <button type="submit" id="signup-button">Sign Up</button>
          </form>
      </div>
      <div class="form-container sign-in">
        <form action="../../backend/login.php" method="POST">
          <h1>Sign In</h1>
          <span>or use your email password</span>
          <input type="email" placeholder="Email" name="email" required />
          <div class="password-wrapper">
              <input type="password" id="signin-password" placeholder="Password" name="password" required />
              <i class="fas fa-eye" id="toggle-password-signin"></i>
          </div>
          <button type="submit">Sign In</button>
        </form>
      </div>
      <div class="toggle-container">
        <div class="toggle">
          <div class="toggle-panel toggle-left">
            <h1>Masuk Garasi, Bro!</h1>
            <p>Daftarin dirimu dan jadi bagian dari keluarga EFKA. Dapetin akses penuh buat booking servis dan belanja sparepart ori.</p>
            <button class="hidden" id="login">Sign In</button>
          </div>
          <div class="toggle-panel toggle-right">
            <h1>Panasin Mesin Lagi</h1>
            <p>Kangen sama deru mesin yang halus? Masukkan detail akunmu untuk melanjutkan servis terbaik.</p>
            <button class="hidden" id="register">Sign Up</button>
          </div>
        </div>
      </div>
    </div>

    <script src="script.js"></script>
      <?php
          include '../../backend/config.php';

          if (isset($_SESSION['success_message'])) {
            echo "
            <script>
                Swal.fire({
                    title: 'Pendaftaran Berhasil!',
                    text: '" . $_SESSION['success_message'] . "',
                    icon: 'success',
                    confirmButtonColor: '#FFC72C'
                });
            </script>
            ";
            unset($_SESSION['success_message']);
        }

        if (isset($_SESSION['error_message'])) {
            echo "
            <script>
                Swal.fire({
                    title: 'Login Gagal',
                    text: '" . $_SESSION['error_message'] . "',
                    icon: 'error',
                    confirmButtonColor: '#FFC72C'
                });
            </script>`
            ";
            unset($_SESSION['error_message']);
        }
        ?>
      ?>
      
  </body>
</html>
