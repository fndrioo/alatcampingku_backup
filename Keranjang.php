<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi database ada

// Bersihkan data produk di session cart yang tidak lengkap
if (isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) {
        return !empty($item['id']) &&
            !empty($item['nama']) &&
            !empty($item['harga']) &&
            !empty($item['image_url']) &&
            !empty($item['quantity']);
    });
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Keranjang - AlatCampingKu</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rubik&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        /* Membuat tabel lebih lebar */
        .table-custom-width {
            width: 100%;
            max-width: 1200px;
        }

        /* Membuat gambar lebih kecil di dalam tabel */
        .product-image {
            width: 150px;
        }
    </style>
</head>

<body>
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
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Kategori Peralatan</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <?php foreach ($categories as $category): ?>
                                    <?php if ($category['name'] == 'Tenda'): ?>
                                        <a href="tenda.php?category_id=<?= $category['id_category'] ?>"
                                            class="dropdown-item">Tenda</a>
                                    <?php elseif ($category['name'] == 'Backpack'): ?>
                                        <a href="product.php?category_id=<?= $category['id_category'] ?>"
                                            class="dropdown-item">Backpack</a>
                                    <?php elseif ($category['name'] == 'Peralatan Masak'): ?>
                                        <a href="product.php?category_id=<?= $category['id_category'] ?>"
                                            class="dropdown-item">Peralatan Masak</a>
                                    <?php else: ?>
                                        <a href="product.php?category_id=<?= $category['id_category'] ?>"
                                            class="dropdown-item"><?= htmlspecialchars($category['name']) ?></a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <a href="contact.php" class="nav-item nav-link">Contact</a>
                        <a href="orders.php" class="nav-item nav-link">Pesanan</a>

                        <!-- Tampilkan hanya jika pengguna adalah admin -->
                        <?php
                        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="adminpanel.php" class="nav-item nav-link">Admin Panel</a>
                        <?php endif; ?>

                        <a href="keranjang.php" class="nav-item nav-link active">Keranjang</a>
                        <a href="profile.php" class="nav-item nav-link">Profil</a>
                        <a href="index.html" class="nav-item nav-link">Logout</a>
                    </div>
                </div>
            </nav>
        </div>
        </div>

        <!-- Navbar End -->

        <!-- Keranjang Start -->
        <?php
        include 'koneksi.php'; // Koneksi database
        
        if (!isset($_SESSION['id'])) {
            die('User belum login.');
        }

        $user_id = $_SESSION['id'];

        // Query untuk mengambil data keranjang beserta informasi produk
        $sql = "SELECT tb_keranjang.quantity, tb_keranjang.product_id, tb_keranjang.created_at, 
            products.nama, products.harga, products.image_url 
            FROM tb_keranjang 
            JOIN products ON tb_keranjang.product_id = products.id 
            WHERE tb_keranjang.user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) > 0) {
            $total = 0;

            echo '<div class="container d-flex justify-content-center align-items-center py-5" style="min-height: 70vh;">';
            echo '<div class="table-responsive">';
            echo '<h2 class="text-center mb-4">Keranjang Belanja</h2>';
            echo '<table class="table table-striped table-custom-width">
            <thead>
                <tr>
                    <th scope="col">Produk</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Total</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>';

            foreach ($result as $item) {
                $item_total = $item['harga'] * $item['quantity'];
                $total += $item_total;

                echo "<tr>
                <td><img src='{$item['image_url']}' alt='Product' class='product-image'></td>
                <td>{$item['nama']}</td>
                <td>Rp. " . number_format($item['harga'], 0, ',', '.') . "</td>
                <td>
                    <form method='POST' action='update_quantity.php' class='form-inline'>
                        <input type='hidden' name='product_id' value='{$item['product_id']}'>
                        <input type='number' name='quantity' class='form-control' value='{$item['quantity']}' min='1'>
                        <input type='hidden' class='btn btn-primary btn-sm ml-2'></input>
                    </form>
                </td>
                <td>Rp. " . number_format($item_total, 0, ',', '.') . "</td>
                <td><a href='hapus_keranjang.php?id={$item['product_id']}' class='btn btn-danger'>Hapus</a></td>
            </tr>";
            }

            echo "</tbody></table>";
            echo "<div class='d-flex justify-content-right'>
            <h4>Total: Rp. " . number_format($total, 0, ',', '.') . "</h4>
          </div>";

            // Tambahkan tombol checkout
            echo '<div class="d-flex justify-content-between mt-4">
            <a href="indexx.php" class="btn btn-secondary">Lanjut Belanja</a>
            <a href="transaction.php" class="btn btn-primary">Checkout</a>
          </div>';
            echo '</div>';
            echo '</div>';
        } else {
            echo "<div class='container'><p class='text-center'>Keranjang kosong!</p></div>";
        }
        ?>
        <!-- Keranjang End -->

        <!-- Footer Start -->
        <div class="container-fluid bg-secondary py-5 px-sm-3 px-md-5" style="margin-top: 90px;">
            <div class="row pt-5">
                <div class="col-lg-3 col-md-6 mb-5">
                    <h4 class="text-uppercase text-light mb-4">Get In Touch</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt text-white mr-3"></i>123 Street, New York, USA</p>
                    <p class="mb-2"><i class="fa fa-phone-alt text-white mr-3"></i>+012 345 67890</p>
                    <p><i class="fa fa-envelope text-white mr-3"></i>info@example.com</p>
                    <h6 class="text-uppercase text-white py-2">Follow Us</h6>
                    <div class="d-flex justify-content-start">
                        <a class="btn btn-lg btn-dark btn-lg-square mr-2" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-lg btn-dark btn-lg-square mr-2" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-lg btn-dark btn-lg-square mr-2" href="#"><i
                                class="fab fa-linkedin-in"></i></a>
                        <a class="btn btn-lg btn-dark btn-lg-square" href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-5">
                    <h4 class="text-uppercase text-light mb-4">Usefull Links</h4>
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-body mb-2" href="#"><i class="fa fa-angle-right text-white mr-2"></i>Private
                            Policy</a>
                        <a class="text-body mb-2" href="#"><i class="fa fa-angle-right text-white mr-2"></i>Term &
                            Conditions</a>
                        <a class="text-body mb-2" href="#"><i class="fa fa-angle-right text-white mr-2"></i>New Member
                            Registration</a>
                        <a class="text-body mb-2" href="#"><i class="fa fa-angle-right text-white mr-2"></i>Affiliate
                            Programme</a>
                        <a class="text-body mb-2" href="#"><i class="fa fa-angle-right text-white mr-2"></i>Return &
                            Refund</a>
                        <a class="text-body" href="#"><i class="fa fa-angle-right text-white mr-2"></i>Help & FQAs</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-5">
                    <h4 class="text-uppercase text-light mb-4">Newsletter</h4>
                    <p class="mb-4">Volup amet magna clita tempor. Tempor sea eos vero ipsum. Lorem lorem sit sed elitr
                        sed
                        kasd et</p>
                    <div class="w-100 mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control bg-dark border-dark" style="padding: 25px;"
                                placeholder="Your Email">
                            <div class="input-group-append">
                                <button class="btn btn-primary text-uppercase px-3">Sign Up</button>
                            </div>
                        </div>
                    </div>
                    <i>Lorem sit sed elitr sed kasd et</i>
                </div>
            </div>
        </div>
        <div class="container-fluid bg-dark py-4 px-sm-3 px-md-5">
            <p class="mb-2 text-center text-body">&copy; <a href="#">AlatCampingKu</a>. All Rights Reserved.</p>

            <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
            <p class="m-0 text-center text-body">Designed by <a href="https://htmlcodex.com">HTML Codex</a></p>
        </div>
        <!-- Footer End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-chevron-up"></i></a>

        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="lib/tempusdominus/js/moment.min.js"></script>
        <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
        <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

        <!-- Customized Bootstrap Scripts -->
        <script src="js/bootstrap.bundle.min.js"></script>

        <!-- Template Scripts -->
        <script src="js/main.js"></script>

        <!--css-->
        <style>
            .container-margin-top {
                margin-top: 90px;
                /* Sesuaikan dengan margin-bottom footer jika diperlukan */
            }
        </style>
</body>

</html>