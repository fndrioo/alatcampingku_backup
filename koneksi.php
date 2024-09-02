<?php
$servername = "localhost"; // atau 127.0.0.1
$username = "root"; // atau username MySQL Anda
$password = ""; // password MySQL Anda, biasanya kosong untuk XAMPP
$dbname = "db_alatacampingku"; // Nama database Anda

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die(); // Menghentikan eksekusi jika koneksi gagal
}
?>
