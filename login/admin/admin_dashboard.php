<?php
// Mulai sesi
session_start();

// Cek apakah pengguna sudah login dan memiliki role sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    // Jika belum login atau bukan role admin, arahkan ke halaman login
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #1f1f1f;
            color: #e50914;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        main {
            max-width: 800px;
            margin: 40px auto;
            background-color: #1f1f1f;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        h1 {
            color: #e50914;
            font-size: 28px;
            text-align: center;
            margin-bottom: 15px;
        }
        p {
            text-align: center;
            font-size: 16px;
            margin-bottom: 30px;
            color: #bdbdbd;
        }
        .menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        .menu a {
            display: inline-block;
            text-decoration: none;
            color: white;
            background: #e50914;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .menu a:hover {
            background: #b71c1c;
            transform: scale(1.05);
        }
        .logout {
            text-align: center;
        }
        .logout a {
            display: inline-block;
            text-decoration: none;
            color: white;
            background-color: #d32f2f;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .logout a:hover {
            background-color: #b71c1c;
            transform: scale(1.05);
        }
        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <main>
        <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <p>Anda berhasil login sebagai admin. Gunakan menu di bawah ini untuk mengelola data aplikasi.</p>

        <div class="menu">
            <a href="kelola_buku.php">Kelola Buku</a>
            <a href="kelola_user.php">Kelola User</a>
        </div>

        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Admin Dashboard. All rights reserved.</p>
    </footer>
</body>
</html>
