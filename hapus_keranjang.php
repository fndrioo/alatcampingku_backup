<?php
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    // Hapus produk dari keranjang
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
    }

    // Redirect kembali ke halaman keranjang
    header('Location: keranjang.php?message=Produk telah dihapus dari keranjang');
    exit;
}
?>
