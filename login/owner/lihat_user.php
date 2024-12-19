<?php
session_start();
include '../includes/db.php'; // Pastikan path ke db.php benar

// Cek apakah yang login adalah owner
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'owner') {
    header('Location: ../login.php'); // Redirect jika bukan owner
    exit;
}

// Ambil data user dengan semua role, prioritas admin dan owner di atas
$user_result = $conn->query("SELECT * FROM users ORDER BY FIELD(role, 'admin', 'owner', 'user'), id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Data Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lihat Data Pengguna</h1>

        <h2>Daftar Pengguna</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $user_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['role']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="back-link">
            <a href="owner_dashboard.php">Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>
