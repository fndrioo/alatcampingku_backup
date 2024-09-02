<?php
require 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    $role = 'user'; // Role default

    // Validasi input (opsional tetapi disarankan)
    if (empty($username) || empty($password) || empty($email)) {
        echo "<script>alert('All fields are required!'); window.location.href='Register.php';</script>";
        exit;
    }

    // Cek apakah username atau email sudah ada
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tb_users WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "<script>alert('Username atau Email sudah digunakan!'); window.location.href='Register.php';</script>";
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Siapkan query SQL untuk memasukkan data
    $stmt = $conn->prepare("INSERT INTO tb_users (username, email, password, role) VALUES (:username, :email, :password, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        // Simpan informasi user di session
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Redirect ke landing page
        header("Location: indexx.php");
        exit;
    } else {
        echo "<script>alert('Registration failed! Please try again.'); window.location.href='Register.php';</script>";
    }
}
?>






    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header text-center">
                            <h2>Register</h2>
                        </div>
                        <div class="card-body">
                            <form action="register.php" method="post"> <!-- Mengarahkan ke register.php -->
                                <div class="form-group">
                                    <label for="username">Username:</label>
                                    <input type="text" id="username" name="username" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" id="password" name="password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                                <div class="text-center">
                                    <input type="submit" value="Register" class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center">
                            <p>Already have an account? <a href="Login.php">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
