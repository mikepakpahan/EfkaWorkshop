<?php

$server = "localhost";
$user = "root";
$pass = "";
$dbName = "db_efkaworkshop";

$conn = new mysqli($server, $user, $pass, $dbName);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
