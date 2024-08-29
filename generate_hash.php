<?php
$password = 'admin123'; // Ganti dengan password yang ingin di-hash
$hash = md5($password);
echo "MD5 Hash dari '$password' adalah: $hash";
?>
