<?php
$host = 'localhost'; // Ganti dengan host database Anda
$dbname = 'db_alatacampingku'; // Nama database
$username = 'root'; // Username database Anda
$password = ''; // Password database Anda

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>