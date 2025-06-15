<?php

$pageTitle = 'Sparepart Order';
$activeMenu = 'order';

include '../template-header.php';
include '../template-sidebar.php';
?>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="../style.css">

<div class="main-content">

  <div class="order-container">
<div class="search-wrapper">
  <div class="search-bar">
    <img src="/EfkaWorkshop/assets/hamburger.png" alt="menu" class="menu-icon" />
    <input type="text" placeholder="Search Order" />
    <img src="/EfkaWorkshop/assets/search.png" alt="search" class="search-icon" />
  </div>
</div>


    <div class="order-list">
<?php
$orders = [
  [
    "name" => "Michael Babista Pakpahan",
    "email" => "michael@gmail.com",
    "products" => [
      ["name" => "Belt", "qty" => 1, "price" => 200000],
      ["name" => "Aki", "qty" => 1, "price" => 250000],
      ["name" => "Oli", "qty" => 2, "price" => 45000],
    ]
  ],
  // Gandakan 9 kali (atau buat array loop 9x)
];

for ($i = 0; $i < 9; $i++) {
  $order = $orders[0]; // pakai data pertama untuk contoh
  $total = 0;

  echo '<div class="order-card">';
  echo '  <div class="profile">';
  echo '    <img src="/EfkaWorkshop/assets/profil.png" alt="profile">';
  echo '    <div>';
  echo '      <div class="profile-name">' . $order["name"] . '</div>';
  echo '      <div class="profile-email">' . $order["email"] . '</div>';
  echo '    </div>';
  echo '  </div>';

  echo '  <table>';
  echo '    <tr><td><strong>Nama Produk</strong></td><td><strong>Jumlah</strong></td><td><strong>Harga</strong></td></tr>';
  foreach ($order["products"] as $product) {
    $subTotal = $product["qty"] * $product["price"];
    $total += $subTotal;
    echo "<tr><td>{$product['name']}</td><td>{$product['qty']}</td><td>Rp" . number_format($product['price'], 0, ',', '.') . "</td></tr>";
  }
  echo "<tr><td colspan='2'><strong>Total</strong></td><td><strong>Rp" . number_format($total, 0, ',', '.') . "</strong></td></tr>";
  echo '  </table>';

  echo '  <div class="buttons">';
  echo '    <button class="btn-accept">Accept</button>';
  echo '    <button class="btn-delete">Delete</button>';
  echo '  </div>';
  echo '</div>';
}
?>

<script src="../script.js"></script>
