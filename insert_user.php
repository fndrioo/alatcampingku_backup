<?php
// Koneksi ke database
include 'koneksi.php'; // Pastikan jalur ini benar

// Data user yang ingin dimasukkan
$email = 'admin1@alatcampingku.com'; // Email asli
$password = 'admin123'; // Password asli

// Buat hash dari password menggunakan password_hash()
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Cek apakah email sudah ada
$sql = "SELECT COUNT(*) FROM login_system WHERE email = :email";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();
$count = $stmt->fetchColumn();

if ($count > 0) {
    echo "Error: Email sudah digunakan!";
} else {
    // Siapkan query SQL untuk memasukkan data
    $sql = "INSERT INTO login_system (email, password) VALUES (:email, :password)";
    $stmt = $conn->prepare($sql);

    // Bind parameter dan eksekusi statement
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    if ($stmt->execute()) {
        echo "Data user berhasil disimpan!";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}

// Tutup koneksi
$conn = null;
?>
