<?php
session_start();

// Pastikan keranjang ada
if (isset($_SESSION['cart'])) {
    // Ambil ID produk yang ingin diupdate
    $id = $_POST['id'];
    $action = $_POST['action'];

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            if ($action == 'increase') {
                $item['quantity']++;
            } elseif ($action == 'decrease' && $item['quantity'] > 1) {
                $item['quantity']--;
            }
            break;
        }
    }
}

// Redirect kembali ke halaman keranjang
header("Location: keranjang.php");
exit();
?>
