<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_perpus";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil id yang ingin dihapus dari URL
$id = $_GET['id']; // Pastikan untuk memvalidasi dan sanitasi input

// Query SQL untuk menghapus data
$sql = "DELETE FROM buku WHERE id = $id";

// Menjalankan query
if ($conn->query($sql) === TRUE) {
    // Menampilkan alert jika penghapusan berhasil
    echo "<script>alert('Buku berhasil dihapus'); window.location.href='kelola_buku.php';</script>";
} else {
    // Menampilkan alert jika terjadi error
    echo "<script>alert('Error: " . $conn->error . "'); window.location.href='kelola_buku.php';</script>";
}

// Menutup koneksi
$conn->close();
?>
