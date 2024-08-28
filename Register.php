<?php
require 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    // Cek apakah username sudah ada
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tb_users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "<script>alert('Username already exists! Please choose another username.'); window.location.href='Register.html';</script>";
        exit;
    }

    // Jika username belum ada, lanjutkan dengan registrasi
    $stmt = $conn->prepare("INSERT INTO tb_users (username, password, email) VALUES (:username, :password, :email)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        // Simpan informasi user di session
        $_SESSION['username'] = $username;
        
        // Redirect ke landing page
        header("Location: indexx.php");
        exit;
    } else {
        echo "<script>alert('Registration failed! Please try again.'); window.location.href='Register.html';</script>";
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
