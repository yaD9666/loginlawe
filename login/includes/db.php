<?php
// Menyambungkan ke database dengan menggunakan mysqli
$host = 'localhost'; // Ganti dengan host database Anda
$user = 'root'; // Ganti dengan username MySQL Anda
$password = ''; // Ganti dengan password MySQL Anda
$dbname = 'login_perpus'; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $dbname);

// Memeriksa apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
