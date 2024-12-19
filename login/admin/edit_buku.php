<?php
session_start();
include '../includes/db.php'; // Pastikan path ke db.php benar

// Cek apakah yang login adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: kelola_buku.php'); // Redirect jika bukan admin
    exit;
}

// Ambil ID buku dari URL
$id = $_GET['id'];

// Ambil data buku berdasarkan ID
$query = "SELECT * FROM buku WHERE id = $id";
$result = $conn->query($query);

if (!$result || $result->num_rows == 0) {
    echo "Buku tidak ditemukan!";
    exit;
}

$row = $result->fetch_assoc();

// Proses penyimpanan perubahan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_book'])) {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];
    
    // Proses upload cover gambar jika ada
    $cover = $_FILES['cover'];
    $cover_path = $row['cover']; // Gunakan cover lama jika tidak diubah
    if ($cover['tmp_name']) {
        $upload_dir = 'uploads';
        $cover_path = $upload_dir . basename($cover['name']);
        move_uploaded_file($cover['tmp_name'], $cover_path);
    }

    $query = "UPDATE buku SET judul = '$judul', penulis = '$penulis', penerbit = '$penerbit', tahun = '$tahun', cover = '$cover_path' WHERE id = $id";

    if ($conn->query($query)) {
        $message = "Buku berhasil diperbarui!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        p {
            color: green;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, select, button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #007bff;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            text-align: center;
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .form-group img {
            max-width: 150px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Book Details</h1>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="judul" value="<?php echo $row['judul']; ?>" placeholder="Book Title" required>
            <input type="text" name="penulis" value="<?php echo $row['penulis']; ?>" placeholder="Author" required>
            <input type="text" name="penerbit" value="<?php echo $row['penerbit']; ?>" placeholder="Publisher" required>
            <input type="number" name="tahun" value="<?php echo $row['tahun']; ?>" placeholder="Year of Publication" required>
            
            <!-- Book Cover Upload -->
            <div class="form-group">
                <label for="cover">Cover Image:</label>
                <input type="file" name="cover" id="cover">
                <?php if (!empty($row['cover'])): ?>
                    <img src="<?php echo $row['cover']; ?>" alt="Current Cover">
                <?php endif; ?>
            </div>

            <button type="submit" name="edit_book" a href="kelola_buku.php">Save Changes</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="kelola_buku.php">Back to Book List</a>
        </div>
    </div>
</body>
</html>
