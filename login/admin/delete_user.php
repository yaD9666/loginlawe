<?php
session_start();
include '../includes/db.php'; // Pastikan path ke db.php benar

// Cek apakah yang login adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php'); // Redirect jika bukan admin
    exit;
}

// Cek apakah tombol delete_user di klik
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $id = $_POST['id'];

    // Query untuk menghapus user berdasarkan id
    $query = "DELETE FROM users WHERE id = $id";

    if ($conn->query($query)) {
        $message = "User berhasil dihapus!";
    } else {
        $message = "Error: " . $conn->error;
    }

    // Redirect kembali ke halaman kelola user dengan pesan
    header("Location: kelola_user.php?message=" . urlencode($message));
    exit;
}
?>
