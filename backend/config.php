<?php
// File: backend/config.php

// 1. Memulai Session (WAJIB ADA DI PALING ATAS)
session_start();

// 2. Detail Koneksi Database
$host = 'localhost';
$db   = 'db_efkaworkshop';
$user = 'root';
$pass = '';

// 3. Membuat Koneksi (Gaya Object-Oriented)
$conn = new mysqli($host, $user, $pass, $db);

// 4. Pengecekan Koneksi
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// File ini siap digunakan 👍
?>