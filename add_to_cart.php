<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi database ada

if (isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Ambil informasi produk dari database
    $stmt = $pdo->prepare("SELECT nama, harga, image_url FROM products WHERE id = :id");
    $stmt->bindParam(':id', $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Inisialisasi keranjang jika belum ada
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Cek apakah produk sudah ada di keranjang
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] += $quantity; // Update jumlah
                $found = true;
                break;
            }
        }

        // Jika produk belum ada, tambahkan produk baru
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product_id,
                'nama' => $product['nama'],
                'harga' => $product['harga'],
                'image_url' => $product['image_url'],
                'quantity' => $quantity
            ];
        }

        // Redirect atau set notifikasi
        header('Location: keranjang.php?message=Produk telah ditambahkan ke keranjang');
        exit;
    } else {
        echo 'Produk tidak ditemukan!';
    }
} else {
    echo 'Data produk tidak valid!';
}
?>
