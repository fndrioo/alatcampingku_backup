<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi database

if (!isset($_POST['product_id']) || !isset($_POST['quantity']) || !isset($_SESSION['id'])) {
    die('Invalid request.');
}

$product_id = $_POST['product_id'];
$quantity = (int)$_POST['quantity'];
$user_id = $_SESSION['id'];

if ($quantity < 1) {
    $quantity = 1; // Pastikan kuantitas minimal adalah 1
}

// Query untuk memperbarui kuantitas produk dalam keranjang
$sql = "UPDATE tb_keranjang SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$stmt->execute();

// Setelah memperbarui, kembali ke halaman keranjang
header('Location: keranjang.php');
exit();
?>
