<?php
// Langkah 1: Koneksi ke database menggunakan PDO
include 'koneksi.php'; // File koneksi ke database

try {
    // Langkah 2: Query untuk memasukkan data
    $sql = "INSERT INTO products (nama, kategori, harga, stock, image_url, description)
            VALUES 
            ('Tenda Consina', 'Tenda', 25000, 10, 'img/Consina.jpg', 'Tenda kapasitas 4 orang'),
            ('Tenda Arei', 'Tenda', 35000, 8, 'img/Arei.jpg', 'Tenda kapasitas 4 orang')";

    // Langkah 3: Eksekusi query
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    echo "Data berhasil dimasukkan ke database";
} catch (PDOException $e) {
    // Jika terjadi kesalahan, tampilkan pesan error
    echo "Error: " . $e->getMessage();
}

// Langkah 4: Tutup koneksi dengan mengatur koneksi ke null
$pdo = null;
?>
