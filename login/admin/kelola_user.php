<?php
session_start();
include '../includes/db.php'; // Pastikan path ke db.php benar

// Cek apakah yang login adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php'); // Redirect jika bukan admin
    exit;
}

// Tambah user baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
    if ($conn->query($query)) {
        $message = "User berhasil ditambahkan!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Ambil data user dengan semua role, prioritas admin dan owner di atas
$user_result = $conn->query("SELECT * FROM users ORDER BY FIELD(role, 'admin', 'owner', 'user'), id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User</title>
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
        p {
            text-align: center;
            color: green;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 500px;
            margin: 0 auto;
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
        .actions a, .actions form button {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
        }
        .actions a {
            background-color: #007bff;
        }
        .actions form button {
            background-color: #dc3545;
            border: none;
        }
        .actions form button:hover {
            background-color: #b02a37;
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
        <h1>Kelola User</h1>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>

        <h2>Tambah User</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="create_user">Tambah User</button>
        </form>

        <h2>Daftar User</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $user_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['role']; ?></td>
                        <td class="actions">
                            <?php if ($row['role'] != 'admin' && $row['role'] != 'owner'): ?>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a>
                                <form method="POST" action="delete_user.php" onsubmit="return confirm('Anda yakin ingin menghapus user ini?');" style="display: inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete_user">Hapus</button>
                                </form>
                            <?php else: ?>
                                <span>Tidak dapat dihapus</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="back-link">
            <a href="admin_dashboard.php">Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>
