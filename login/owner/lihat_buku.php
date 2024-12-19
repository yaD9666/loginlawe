<?php
// Memulai sesi
session_start();

// Mengecek apakah pengguna sudah login dan memiliki role yang sesuai
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
    header('Location: ../login.php');
    exit;
}

// Sertakan file database untuk mengambil data buku
include '../includes/db.php';

// Query awal untuk mengambil semua data buku
$query = "SELECT * FROM buku";
$result = $conn->query($query);

// Pencarian berdasarkan huruf pertama
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $firstChar = $conn->real_escape_string(mb_substr($search, 0, 1)); // Mengambil huruf pertama
    $query = "SELECT * FROM buku WHERE LEFT(judul, 1) = '$firstChar'";
    $result = $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Buku</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #141414;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            font-size: 28px;
            color: #e50914;
            margin-bottom: 10px;
        }

        .search-bar {
            margin: 20px auto;
            text-align: center;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            width: 60%;
            font-size: 16px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #222;
            color: #fff;
            text-transform: capitalize;
        }

        .search-bar button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #e50914;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #f40612;
        }

        .reset-btn {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #444;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
            display: inline-block;
        }

        .reset-btn:hover {
            background-color: #555;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: #222;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #444;
        }

        .card img.fallback {
            object-fit: contain;
            background-color: #333;
        }

        .card-content {
            padding: 15px;
            text-align: center;
        }

        .card-content h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #fff;
        }

        .card-content p {
            font-size: 14px;
            color: #999;
            margin: 5px 0;
        }

        .btn-back {
            display: block;
            margin: 30px auto;
            padding: 10px 20px;
            background-color: #e50914;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
            max-width: 200px;
        }

        .btn-back:hover {
            background-color: #f40612;
        }

        footer {
            margin-top: 30px;
            text-align: center;
            color: #777;
            font-size: 14px;
        }

        footer a {
            color: #e50914;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Data Buku</h1>

        <div class="search-bar">
            <form method="GET">
                <input type="text" name="search" placeholder="Cari buku berdasarkan huruf pertama..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Cari</button>
                <a href="lihat_buku.php" class="reset-btn">Reset</a>
            </form>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <div class="grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <?php if (!empty($row['cover']) && file_exists("../uploads/" . $row['cover'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($row['cover']); ?>" alt="Cover Buku">
                        <?php else: ?>
                            <img src="fallback.jpg" alt="Cover Tidak Tersedia" class="fallback">
                        <?php endif; ?>
                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                            <p><strong>Penerbit:</strong> <?php echo htmlspecialchars($row['penerbit']); ?></p>
                            <p><strong>Tahun:</strong> <?php echo htmlspecialchars($row['tahun']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; font-size: 18px;">Tidak ada buku ditemukan.</p>
        <?php endif; ?>

        <a href="owner_dashboard.php" class="btn-back">Kembali ke Dashboard</a>
    </div>

    <footer>
        <p>&copy; 2024 Lihat Buku. All Rights Reserved.</p>
    </footer>
</body>
</html>
