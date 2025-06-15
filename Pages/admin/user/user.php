<?php
<<<<<<< HEAD

$pageTitle = 'Management User';
=======
$pageTitle = 'User';
>>>>>>> Jodi
$activeMenu = 'user';

include '../template-header.php';
include '../template-sidebar.php';
?>
<<<<<<< HEAD
<link rel="stylesheet" href="../style.css">
<script src="../script.js"></script>
=======
<link rel="stylesheet" href="user.css">
<link rel="stylesheet" href="../style.css">

<div class="main-content">
  <div class="content-actions">
    <button id="btn-edit" class="btn btn-edit">Edit</button>
    <button id="btn-hapus" class="btn btn-hapus">Hapus</button>
    <button id="btn-tambah" class="btn btn-tambah">Tambah</button>
  </div>

  <div class="user-container">
    <div class="search-bar">
      <img src="/EfkaWorkshop/assets/hamburger.png" alt="menu" class="menu-icon" />
      <input type="text" placeholder="Search" />
      <img src="/EfkaWorkshop/assets/search.png" alt="search" class="search-icon" />
    </div>

    <div class="user-list">
      <?php
      $users = [
        ["name" => "Michael Babista Pakpahan", "email" => "michael@gmail.com"],
        ["name" => "Alex God", "email" => "AlexGod@gmail.com"],
        ["name" => "John Walker", "email" => "JohnWalker@gmail.com"],
        ["name" => "Riski Ambatakam", "email" => "michael@gmail.com"],
        ["name" => "Tukijem jemikem", "email" => "michael@gmail.com"],
        ["name" => "wowo lwowo", "email" => "michael@gmail.com"]
      ];

      foreach ($users as $user) {
        echo '
        <div class="user-card">
          <div class="user-info">
            <img src="/EfkaWorkshop/assets/profil.png" alt="profile" class="profile-img">
            <div>
              <div class="user-name">' . $user["name"] . '</div>
              <div class="user-email">' . $user["email"] . '</div>
            </div>
          </div>
          <img src="/EfkaWorkshop/assets/delete.png" alt="delete" class="delete-icon">
        </div>';
      }
      ?>
    </div>
  </div>
</div>

<script src="../script.js"></script>
>>>>>>> Jodi
