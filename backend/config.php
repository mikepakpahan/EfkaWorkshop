<?php

session_start();

$host = 'localhost';
$db   = 'db_efkaworkshop';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

?>