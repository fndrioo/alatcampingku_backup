<?php
session_start();
include 'koneksi.php';

// Data produk yang akan dimasukkan ke dalam tabel `products`
$products = [
    [
        'nama' => 'Tenda Patagonia',
        'kategori' => 'Tenda',
        'harga' => 50000,
        'stock' => 10,
        'image_url' => 'img/TendaCamping.png',
        'description' => 'Tenda Patagonia yang nyaman dan kuat, cocok untuk semua kondisi cuaca.'
    ],
    [
        'nama' => 'Tas Decathlon 30L',
        'kategori' => 'Tas Gunung',
        'harga' => 30000,
        'stock' => 15,
        'image_url' => 'img/backpack1.png',
        'description' => 'Tas gunung dengan kapasitas 30L, ideal untuk perjalanan singkat.'
    ],
    [
        'nama' => 'Kompor Portable',
        'kategori' => 'Peralatan Masak',
        'harga' => 20000,
        'stock' => 20,
        'image_url' => 'img/portablestove.png',
        'description' => 'Kompor portable yang praktis dan mudah digunakan di segala medan.'
    ]
];

// Masukkan data produk ke dalam tabel `products`
foreach ($products as $product) {
    $nama = $product['nama'];
    $kategori = $product['kategori'];
    $harga = $product['harga'];
    $stock = $product['stock'];
    $image_url = $product['image_url'];
    $description = $product['description'];

    // Query untuk memasukkan data ke tabel `products`
    $sql = "INSERT INTO products (nama, kategori, harga, stock, image_url, description) 
            VALUES (:nama, :kategori, :harga, :stock, :image_url, :description)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':kategori', $kategori);
    $stmt->bindParam(':harga', $harga);
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':image_url', $image_url);
    $stmt->bindParam(':description', $description);

    if ($stmt->execute()) {
        echo "Produk '$nama' berhasil dimasukkan ke database.<br>";
    } else {
        echo "Error: " . $stmt->errorInfo()[2] . "<br>";
    }
}

// Tutup koneksi
$conn = null;
?>
