<?php
// Ganti 'admin_password' dengan password yang ingin kamu hash
$password = '123';
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Output hasil hash
echo $hashedPassword;
?>
