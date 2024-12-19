<?php
session_start();
include '../includes/db.php'; // Pastikan path ke db.php benar

// Cek apakah yang login adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php'); // Redirect jika bukan admin
    exit;
}

// Ambil ID user dari URL
$id = $_GET['id'];

// Ambil data user berdasarkan ID
$query = "SELECT * FROM users WHERE id = $id";
$result = $conn->query($query);

if (!$result || $result->num_rows == 0) {
    echo "User tidak ditemukan!";
    exit;
}

$row = $result->fetch_assoc();

// Proses penyimpanan perubahan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = "UPDATE users SET username = '$username', password = '$password', role = '$role' WHERE id = $id";

    if ($conn->query($query)) {
        $message = "User berhasil diperbarui!";
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
    <title>Edit User</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit User</h1>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>

        <form method="POST">
            <input type="text" name="username" value="<?php echo $row['username']; ?>" placeholder="Username" required>
            <input type="password" name="password" value="<?php echo $row['password']; ?>" placeholder="Password" required>
            <select name="role" required>
                <option value="user" <?php if ($row['role'] == 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if ($row['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select>
            <button type="submit" name="edit_user">Simpan Perubahan</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="kelola_user.php">Kembali ke Daftar User</a>
        </div>
    </div>
</body>
</html>
