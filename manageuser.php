<?php
require 'koneksi.php'; // Menghubungkan ke database

// Mengambil data pengguna dari database tanpa kolom status
$stmt = $conn->prepare("SELECT id, username, email, role FROM tb_users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Manage Users - Admin Panel AlatCampingKu</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rubik&display=swap" rel="stylesheet">

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
    <nav class="navbar navbar-expand-lg bg-secondary navbar-dark py-3 px-4">
        <a href="indexx.html" class="navbar-brand">
            <h1 class="text-uppercase text-primary mb-1">Admin Panel - AlatCampingKu</h1>
        </a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
            <div class="navbar-nav ml-auto py-0">
                <a href="indexx.html" class="nav-item nav-link">Home</a>
                <a href="index.html" class="nav-item nav-link">Logout</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Start -->
            <div class="col-lg-2 bg-dark">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <h4 class="text-light">Admin Menu</h4>
                    <div class="list-group list-group-flush w-100">
                        <a href="dashboard.html" class="list-group-item list-group-item-action bg-dark text-light">Dashboard</a>
                        <a href="manage-products.html" class="list-group-item list-group-item-action bg-dark text-light">Manage Products</a>
                        <a href="manage-orders.html" class="list-group-item list-group-item-action bg-dark text-light">Manage Orders</a>
                        <a href="manage-users.php" class="list-group-item list-group-item-action bg-dark text-light active">Manage Users</a>
                        <a href="settings.html" class="list-group-item list-group-item-action bg-dark text-light">Settings</a>
                    </div>
                </div>
            </div>
            <!-- Sidebar End -->

            <!-- Main Content Start -->
            <div class="col-lg-10">
                <div class="container p-4">
                    <h2>Manage Users</h2>
                    <p>Here you can manage all the users registered on AlatCampingKu.</p>
                    
                    <!-- Users Table -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary">View</button>
                                    <button class="btn btn-sm btn-warning">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Main Content End -->
        </div>
    </div>

    <!-- Footer Start -->
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