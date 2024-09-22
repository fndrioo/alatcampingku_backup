<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi ke database dan ambil detail produk dari database
$host = 'localhost';
$dbname = 'db_alatacampingku';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}

$product_id = $_GET['id'];
$query = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$query->execute(['id' => $product_id]);
$product = $query->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found!");
}

// Logika untuk menambah produk ke keranjang
if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $product_in_cart = false;

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product['id']) {
            $item['quantity'] += 1;
            $product_in_cart = true;
            break;
        }
    }

    if (!$product_in_cart) {
        $product['quantity'] = 1;
        $_SESSION['cart'][] = $product;
    }

    echo "<script>alert('Produk berhasil ditambahkan ke keranjang!');</script>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>AlatCampingKu</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Sewa dan Jual Alat Camping" name="description">
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">
    <!-- Google Web Fonts -->
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
                        <a href="indexx.html" class="nav-item nav-link">Home</a>
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
                        <?php if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }
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

    <!-- Detail Start -->
    <div class="container-fluid pt-5">
        <div class="container pt-5">
            <div class="row">
                <div class="col-lg-8 mb-5">
                    <h1 class="display-4 text-uppercase mb-5"><?php echo $product['nama']; ?></h1>
                    <div class="row mx-n2 mb-3">
                        <div class="col-md-6 col-12 px-2 pb-2">
                            <img class="img-fluid w-100" src="<?php echo $product['image_url']; ?>"
                                alt="<?php echo $product['nama']; ?>">
                        </div>
                    </div>
                    <p><?php echo $product['description']; ?></p>
                    <div class="row pt-2">
                        <div class="col-md-3 col-6 mb-2">
                            <span>Kategori: <?php echo $product['kategori']; ?></span>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <span>Harga Sewa: Rp. <?php echo number_format($product['harga'], 0, ',', '.'); ?>
                                /Hari</span>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <span>Stok Tersedia: <?php echo $product['stock']; ?></span>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-md-3 col-6 mb-2">
                            <a class="btn btn-primary px-3" href="transaction.html">Sewa - Rp.
                                <?php echo number_format($product['harga'], 0, ',', '.'); ?> /Hari</a>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <form action="add_to_cart.php" method="POST" class="d-flex align-items-center">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                                <div class="input-group" style="max-width: 150px;">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary" type="button"
                                            id="decreaseQuantity">-</button>
                                    </div>
                                    <input type="number" name="quantity" id="quantity" class="form-control text-center"
                                        value="1" min="1" max="<?= $product['stock'] ?>" aria-label="Quantity">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button"
                                            id="increaseQuantity">+</button>
                                    </div>
                                </div>

                                <button type="submit" name="add_to_cart" class="btn btn-success ml-3"
                                    style="width: auto;">Tambahkan ke Keranjang</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script untuk menambah/mengurangi kuantitas
        document.getElementById('decreaseQuantity').addEventListener('click', function () {
            var quantityInput = document.getElementById('quantity');
            if (quantityInput.value > 1) {
                quantityInput.value--;
            }
        });

        document.getElementById('increaseQuantity').addEventListener('click', function () {
            var quantityInput = document.getElementById('quantity');
            var maxValue = parseInt(quantityInput.getAttribute('max'));
            if (quantityInput.value < maxValue) {
                quantityInput.value++;
            }
        });
    </script>
    <!-- Detail End -->

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
    <!-- (Sama dengan kode sebelumnya) -->

    <!-- JavaScript Libraries -->
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!--Style CSS-->
    <style>
        .input-group .btn {
            width: 60px;
            /* Sesuaikan dengan kebutuhan */
            padding: 5px;
        }

        .input-group .form-control {
            width: 60px;
            /* Sesuaikan lebar input agar pas */
            text-align: center;
        }
    </style>

    <!-- Customized JavaScript -->
    <script src="js/main.js"></script>
    <script>
        document.getElementById('decreaseQuantity').addEventListener('click', function () {
            var quantityInput = document.querySelector('input[name="quantity"]');
            if (quantityInput.value > 1) {
                quantityInput.value--;
            }
        });

        document.getElementById('increaseQuantity').addEventListener('click', function () {
            var quantityInput = document.querySelector('input[name="quantity"]');
            quantityInput.value++;
        });
    </script>
</body>

</html>