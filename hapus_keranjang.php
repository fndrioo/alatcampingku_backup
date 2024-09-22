<?php
session_start();
include 'koneksi.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $user_id = $_SESSION['id'];

    $sql = "DELETE FROM tb_keranjang WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
}

header('Location: keranjang.php');
exit();
