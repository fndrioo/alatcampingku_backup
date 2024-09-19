<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi database

if (!isset($_GET['id']) || !isset($_SESSION['id'])) {
    die('Invalid request.');
}

$product_id = $_GET['id'];
$user_id = $_SESSION['id'];

// Query untuk menghapus produk dari keranjang
$sql = "DELETE FROM tb_keranjang WHERE user_id = :user_id AND product_id = :product_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$stmt->execute();

// Setelah menghapus, kembali ke halaman keranjang
header('Location: keranjang.php');
exit();
?>
