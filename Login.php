<?php
require 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debugging: Check if the user is found
    $stmt = $conn->prepare("SELECT * FROM tb_users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "User found: ";
        var_dump($user); // Debugging: Menampilkan detail user yang ditemukan
    } else {
        echo "User not found";
        exit; // Menghentikan eksekusi jika user tidak ditemukan
    }

    // Jika user ditemukan, lanjutkan dengan verifikasi password
    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: adminpanel.php");
        } else {
            header("Location: indexx.php");
        }
        exit();

    } else {
        echo "<script>alert('Login failed! Please check your credentials.'); window.location.href='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Login</h2>
                    </div>
                    <div class="card-body">
                        <form action="login.php" method="post"> <!-- Mengarahkan ke login.php -->
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="text-center">
                                <input type="submit" value="Login" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p>Don't have an account? <a href="Register.php">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>