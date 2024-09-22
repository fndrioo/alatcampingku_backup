<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

// Pastikan pengguna sudah login
if (!isset($_SESSION['id'])) {
    header('Location: login.php'); // Redirect ke halaman login jika user belum login
    exit();
}

$user_id = $_SESSION['id'];

// Query untuk mengambil data keranjang berdasarkan user_id
$sql = "SELECT tb_keranjang.quantity, tb_keranjang.product_id, tb_keranjang.created_at, 
        products.nama, products.harga, products.image_url 
        FROM tb_keranjang 
        JOIN products ON tb_keranjang.product_id = products.id 
        WHERE tb_keranjang.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung total belanja
$total_belanja = 0;
foreach ($cart_items as $item) {
    $total_belanja += $item['harga'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>AlatCampingKu - Transaksi</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rubik&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Tambahkan CSS Animasi -->
    <style>
        /* Tambahkan animasi fade-in */
        .animate-fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 1s forwards ease-in-out;
        }

        /* Atur keyframes untuk animasi fade-in */
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .detail-pesanan {
            max-width: 900px;
            margin: 0 auto;
            text-align: left;
        }
    </style>
</head>

<body class="animate-fade-in">
    <!-- Navbar Start -->
    <div class="container-fluid position-relative nav-bar p-0">
        <div class="position-relative px-lg-5" style="z-index: 9;">
            <nav class="navbar navbar-expand-lg bg-secondary navbar-dark py-3 py-lg-0 pl-3 pl-lg-5">
                <a href="indexx.php" class="navbar-brand">
                    <h1 class="text-uppercase text-primary mb-1">AlatCampingKu</h1>
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between px-3" id="navbarCollapse">
                    <div class="navbar-nav ml-auto py-0">
                        <a href="indexx.php" class="nav-item nav-link">Home</a>
                        <a href="keranjang.php" class="nav-item nav-link">Keranjang</a>
                        <a href="logout.php" class="nav-item nav-link">Logout</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Transaction Start -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Transaksi Anda</h2>
        <div class="row">
            <div class="col-lg-8">
                <h4 class="mb-3">Detail Pesanan</h4>
                <div class="detail-pesanan">
                    <?php if (count($cart_items) > 0): ?>
                        <?php foreach ($cart_items as $item): ?>
                            <div class="card p-4 mb-4">
                                <h5 class="mb-3"><?php echo htmlspecialchars($item['nama']); ?></h5>
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
                                    alt="<?php echo htmlspecialchars($item['nama']); ?>" class="img-fluid mb-3"
                                    style="max-width: 200px;">
                                <p>Harga Sewa: <strong>Rp. <?php echo number_format($item['harga'], 0, ',', '.'); ?>
                                        /Hari</strong></p>
                                <p>Jumlah Item: <strong><?php echo $item['quantity']; ?></strong></p>
                                <p>Subtotal: <strong>Rp.
                                        <?php echo number_format($item['harga'] * $item['quantity'], 0, ',', '.'); ?></strong>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Keranjang Anda kosong.</p>
                    <?php endif; ?>
                </div>

                <h4 class="mb-3">Informasi Pembayaran</h4>
                <form action="buktipembayaran.php" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName"
                                placeholder="First Name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName"
                                placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="accountNumber">No. Rekening</label>
                            <input type="text" class="form-control" id="accountNumber" name="accountNumber"
                                placeholder="Nomor Rekening" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Nomor HP</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Nomor HP"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bank">Nama Bank</label>
                        <select id="bank" name="bank" class="form-control">
                            <option selected>Pilih Bank</option>
                            <option>Bank BCA</option>
                            <option>Bank Mandiri</option>
                            <option>Bank BRI</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Checkout</button>
                </form>
            </div>

            <div class="col-lg-4">
                <h4 class="mb-3">Ringkasan Pesanan</h4>
                <div class="card p-4 mb-4">
                    <p>Total Pembayaran: <strong>Rp. <?php echo number_format($total_belanja, 0, ',', '.'); ?></strong>
                    </p>
                </div>
                <h5 class="text-center">Terima kasih telah berbelanja di AlatCampingKu!</h5>
            </div>
        </div>
    </div>
    <!-- Transaction End -->

    <!-- Footer Start -->
    <footer class="bg-secondary py-4">
        <div class="container text-center">
            <p class="text-white">&copy; AlatCampingKu. All Rights Reserved. Designed by <a href="https://htmlcodex.com"
                    class="text-primary">HTML Codex</a></p>
        </div>
    </footer>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>
