<?php
// Mulai sesi
session_start();
include '../includes/db.php'; // Pastikan path ke db.php benar

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php'); // Redirect jika bukan admin
    exit;
}

// Menambah buku baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_buku'])) {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];

    // Proses upload gambar
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        $cover_tmp = $_FILES['cover']['tmp_name'];
        $cover_name = $_FILES['cover']['name'];
        $cover_ext = pathinfo($cover_name, PATHINFO_EXTENSION);

        // Membatasi tipe file yang bisa diupload (hanya gambar)
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($cover_ext), $allowed_ext)) {
            $cover_new_name = uniqid('cover_') . '.' . $cover_ext;
            $upload_dir = '../uploads/'; // Pastikan folder uploads ada dan memiliki permission yang benar
            $cover_path = $upload_dir . $cover_new_name;

            if (move_uploaded_file($cover_tmp, $cover_path)) {
                $query = "INSERT INTO buku (judul, penulis, penerbit, tahun, cover) 
                          VALUES ('$judul', '$penulis', '$penerbit', '$tahun', '$cover_path')";

                $message = $conn->query($query) ? 
                    "Buku berhasil ditambahkan!" : "Error: " . $conn->error;
            } else {
                $message = "Gagal mengupload gambar!";
            }
        } else {
            $message = "Tipe file gambar tidak valid (hanya JPG, JPEG, PNG, atau GIF).";
        }
    } else {
        $message = "Gambar tidak ditemukan!";
    }
}

// Mengambil data buku
$buku_result = $conn->query("SELECT * FROM buku");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
        }
        main {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007bff;
        }
        form input, form button {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table img {
            width: 80px;
            border-radius: 4px;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .actions a {
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .actions a.edit {
            background: #007bff;
        }
        .actions a.edit:hover {
            background: #0056b3;
        }
        .actions a.delete {
            background: #dc3545;
        }
        .actions a.delete:hover {
            background: #a71d2a;
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
        <h1>Kelola Buku</h1>
    </header>
    <main>
        <?php if (isset($message)) echo "<p style='color: green;'>$message</p>"; ?>

        <!-- Form untuk Menambah Buku -->
        <h2>Tambah Buku</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="judul" placeholder="Judul Buku" required>
            <input type="text" name="penulis" placeholder="Penulis" required>
            <input type="text" name="penerbit" placeholder="Penerbit" required>
            <input type="date" name="tahun" placeholder="Tahun" required>
            <input type="file" name="cover" accept="image/*" required>
            <button type="submit" name="create_buku">Tambah Buku</button>
        </form>

        <!-- Daftar Buku -->
        <h2>Daftar Buku</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>Cover</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $buku_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['judul']; ?></td>
                        <td><?php echo $row['penulis']; ?></td>
                        <td><?php echo $row['penerbit']; ?></td>
                        <td><?php echo $row['tahun']; ?></td>
                        <td><img src="<?php echo $row['cover']; ?>" alt="Cover Buku"></td>
                        <td>
                            <div class="actions">
                                <a href="edit_buku.php?id=<?php echo $row['id']; ?>" class="edit">Edit</a>
                                <a href="delete_buku.php?id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Anda yakin ingin menghapus buku ini?');">Hapus</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <footer>
            <p><a href="admin_dashboard.php">Kembali ke Dashboard</a></p>
        </footer>
    </main>
</body>
</html>
