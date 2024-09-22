<?php
// Koneksi ke database
include 'koneksi.php'; // Pastikan jalur ini benar

// Data admin yang ingin dimasukkan
$username = 'admin'; // Username admin
$email = 'admin1@alatcampingku.com'; // Email admin
$password = 'admin123'; // Password asli
$role = 'admin'; // Role admin

// Buat hash dari password menggunakan password_hash()
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Cek apakah username atau email sudah ada
$sql = "SELECT COUNT(*) FROM tb_users WHERE username = :username OR email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':email', $email);
$stmt->execute();
$count = $stmt->fetchColumn();

if ($count > 0) {
    echo "Error: Username atau Email sudah digunakan!";
} else {
    // Siapkan query SQL untuk memasukkan data
    $sql = "INSERT INTO tb_users (username, email, password, role) VALUES (:username, :email, :password, :role)";
    $stmt = $pdo->prepare($sql);

    // Bind parameter dan eksekusi statement
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        echo "Data admin berhasil disimpan!";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}

// Tutup koneksi
$conn = null;
?>