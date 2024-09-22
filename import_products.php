<?php
session_start();
include 'koneksi.php';

// Data produk peralatan masak yang akan dimasukkan ke dalam tabel `products`
$products = [
    [
        'nama' => 'Kompor 1 Tungku',
        'kategori' => 'Peralatan Masak',
        'harga' => 20000,
        'stock' => 10,
        'image_url' => 'img/portablestove.png',
        'description' => 'Kompor dengan 1 tungku, menggunakan gas, praktis untuk perjalanan camping.'
    ],
    [
        'nama' => 'Kompor Rinnai 2 Tungku',
        'kategori' => 'Peralatan Masak',
        'harga' => 50000,
        'stock' => 8,
        'image_url' => 'img/kompor2tungku.png',
        'description' => 'Kompor Rinnai dengan 2 tungku, kuat dan efisien, cocok untuk memasak di alam bebas.'
    ],
    [
        'nama' => 'Kompor Cosmos 1 Tungku',
        'kategori' => 'Peralatan Masak',
        'harga' => 35000,
        'stock' => 12,
        'image_url' => 'img/komporcosmos.jpg',
        'description' => 'Kompor Cosmos dengan 1 tungku, ringan dan menggunakan gas, ideal untuk camping.'
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

    $stmt = $pdo->prepare($sql);
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
