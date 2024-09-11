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
    <!-- Navbar Start -->
    <div class="container-fluid position-relative nav-bar p-0">
        <div class="position-relative px-lg-5" style="z-index: 9;">
            <nav class="navbar navbar-expand-lg bg-secondary navbar-dark py-3 py-lg-0 pl-3 pl-lg-5">
                <a href="indexx.html" class="navbar-brand">
                    <h1 class="text-uppercase text-primary mb-1">AlatCampingKu</h1>
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between px-3" id="navbarCollapse">
                    <div class="navbar-nav ml-auto py-0">
                        <a href="indexx.html" class="nav-item nav-link active">Home</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Kategori Peralatan</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <?php foreach ($categories as $category): ?>
                                    <a href="product.php?category_id=<?= $category['id_category'] ?>"
                                        class="dropdown-item"><?= htmlspecialchars($category['name']) ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <a href="contact.php" class="nav-item nav-link">Contact</a>
                        <a href="orders.php" class="nav-item nav-link">Pesanan</a>

                        <!-- Tampilkan hanya jika pengguna adalah admin -->
                        <?php session_start();
                        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="adminpanel.php" class="nav-item nav-link">Admin Panel</a>
                        <?php endif; ?>

                        <a href="keranjang.php" class="nav-item nav-link">Keranjang</a>
                        <a href="index.html" class="nav-item nav-link">Logout</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <!--Keranjang Start-->
    <?php
    // Periksa apakah session sudah dimulai
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Cek apakah keranjang kosong
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "<p class='text-center'>Keranjang kosong!</p>";
    } else {
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

        foreach ($_SESSION['cart'] as $item) {
            $item_total = $item['harga'] * $item['quantity'];
            $total += $item_total;
            echo "<tr>
                <td><img src='{$item['image_url']}' alt='Product' class='product-image'></td>
                <td>{$item['nama']}</td>
                <td>Rp. " . number_format($item['harga'], 0, ',', '.') . "</td>
                <td><input type='number' class='form-control' value='{$item['quantity']}' min='1'></td>
                <td>Rp. " . number_format($item_total, 0, ',', '.') . "</td>
                <td><a href='hapus_keranjang.php?id={$item['id']}' class='btn btn-danger'>Hapus</a></td>
            </tr>";
        }

        echo "</tbody>
    </table>
    <div class='d-flex justify-content-end'>
        <h4>Total: Rp. " . number_format($total, 0, ',', '.') . "</h4>
    </div>";
        echo '<div class="d-flex justify-content-between mt-4">
            <a href="index.html" class="btn btn-secondary">Lanjut Belanja</a>
            <a href="transaction.html" class="btn btn-primary">Checkout</a>
          </div>';
        echo '</div>';
        echo '</div>';
    }
    ?>
    <!--Keranjang End-->

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
                    <a class="btn btn-lg btn-dark btn-lg-square mr-2" href="#"><i class="fab fa-linkedin-in"></i></a>
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
                <p class="mb-4">Volup amet magna clita tempor. Tempor sea eos vero ipsum. Lorem lorem sit sed elitr sed
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
        <p class="m-0 text-center text-body">Designed by <a href="https://htmlcodex.com">HTML Codex</a></p>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
