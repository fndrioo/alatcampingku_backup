<?php
// Add to cart processing script
session_start();
include 'koneksi.php'; // Pastikan koneksi database ada

// Cek apakah user_id ada di session
if (!isset($_SESSION['id'])) {
    die('User ID tidak ditemukan di session.');
}

$user_id = $_SESSION['id']; // ID pengguna dari session
$product_id = $_POST['product_id']; // ID produk yang ditambahkan
$quantity = $_POST['quantity']; // Jumlah produk yang ditambahkan

// Cek detail produk dari database
$query_product = "SELECT * FROM products WHERE id = :product_id";
$stmt_product = $conn->prepare($query_product);
$stmt_product->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$stmt_product->execute();
$product = $stmt_product->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die('Produk tidak ditemukan.');
}

// Inisialisasi keranjang untuk user jika belum ada
if (!isset($_SESSION['cart'][$user_id])) {
    $_SESSION['cart'][$user_id] = [];
}

// Simpan data produk ke keranjang berdasarkan user_id
$found = false;

// Cek apakah produk sudah ada di keranjang
foreach ($_SESSION['cart'][$user_id] as &$item) {
    if ($item['id'] == $product['id']) {
        $item['quantity'] += $quantity;
        $found = true;
        break;
    }
}

// Jika produk belum ada, tambahkan
if (!$found) {
    $_SESSION['cart'][$user_id][] = [
        'id' => $product['id'],
        'nama' => $product['nama'],
        'harga' => $product['harga'],
        'image_url' => $product['image_url'],
        'quantity' => $quantity
    ];
}

// Redirect ke halaman keranjang atau tampilkan isi keranjang
header("Location: keranjang.php");
exit();
