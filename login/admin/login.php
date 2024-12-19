<?php
// Mulai sesi
session_start();

// Pastikan path ke db.php benar
include '../includes/db.php'; // Pastikan path menuju db.php sesuai dengan lokasi yang benar

// Proses login ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form login
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari user berdasarkan username
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    // Jika user ditemukan
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if ($password == $user['password']) {
            // Simpan informasi sesi
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];

            // Cek peran pengguna
            if ($user['role'] == 'admin') {
                // Redirect ke dashboard admin
                header('Location: admin_dashboard.php');
                exit;
            } elseif ($user['role'] == 'user') {
                // Redirect ke dashboard user
                header('Location: ../user/user_dashboard.php');
                exit;
            } elseif ($user['role'] == 'owner') {
                // Redirect ke dashboard owner
                header('Location: ../owner/owner_dashboard.php');
                exit;
            } else {
                // Jika role tidak dikenal
                $error = "Role tidak dikenali!";
            }
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        p {
            color: #ff4d4d;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #333;
            border-radius: 5px;
            background-color: #2a2a2a;
            color: #ffffff;
        }
        input:focus {
            outline: none;
            border-color: #007bff;
        }
        button {
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
        .footer p {
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="footer">
            <p>© 2024 </p>
        </div>
    </div>
</body>
</html>
