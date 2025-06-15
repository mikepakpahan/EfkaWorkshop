<?php

$pageTitle = 'Dashboard';
$activeMenu = 'dashboard';

include '../template-header.php';
include '../template-sidebar.php';
?>
<link rel="stylesheet" href="dashboard.css">
<link rel="stylesheet" href="../style.css">
  <div class="main-content">
    <div class="content-actions">
      <button id="btn-edit" class="btn btn-edit">Edit</button>
      <button id="btn-hapus" class="btn btn-hapus">Hapus</button>
      <button id="btn-tambah" class="btn btn-tambah">Tambah</button>
    </div>
    <!-- Dashboard Cards Start -->
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-title">Total Order</div>
            <div class="card-value">1.200</div>
            <div class="card-desc">selama ini</div>
        </div>
        <div class="dashboard-card">
            <div class="card-title">Total Penjualan Sparepart</div>
            <div class="card-value">1.200</div>
            <div class="card-desc">selama ini</div>
        </div>
        <div class="dashboard-card">
            <div class="card-title">Total Bookking</div>
            <div class="card-value">342</div>
            <div class="card-desc">selama ini</div>
        </div>
        <div class="dashboard-card">
            <div class="card-title">Total Order</div>
            <div class="card-value">200</div>
            <div class="card-desc">Hari ini</div>
        </div>
        <div class="dashboard-card">
            <div class="card-title">Total Penjualan Sparepart</div>
            <div class="card-value">178</div>
            <div class="card-desc">Hari ini</div>
        </div>
        <div class="dashboard-card">
            <div class="card-title">Total Bookking</div>
            <div class="card-value">21</div>
            <div class="card-desc">Hari ini ini</div>
        </div>
        <div class="dashboard-card">
            <div class="card-title">xxxxx</div>
            <div class="card-value">xxxx</div>
            <div class="card-desc">xxxx</div>
        </div>
        <div class="dashboard-card">
            <div class="card-title">xxxxxxxxxxxx xxxxx</div>
            <div class="card-value">xxxx</div>
            <div class="card-desc">xxxxxxxxxxxx</div>
        </div>
        <div class="dashboard-card">
            <div class="card-title">xxxxxxxxxxxxxx</div>
            <div class="card-value">xxx</div>
            <div class="card-desc">xxxxxxxxxx</div>
        </div>
    </div>
    <!-- Dashboard Cards End -->
  </div>
<script src="../script.js"></script>