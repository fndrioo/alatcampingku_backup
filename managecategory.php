<?php
require 'koneksi.php'; // Menghubungkan ke database

// Add Category
if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    if (!empty($category_name)) {
        $stmt = $conn->prepare("INSERT INTO tb_category (name) VALUES (?)");
        $stmt->execute([$category_name]);
        header("Location: managecategory.php");
        exit();
    }
}

// Update Category
if (isset($_POST['edit_category'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    if (!empty($category_name)) {
        $stmt = $conn->prepare("UPDATE tb_category SET name = ? WHERE id_category = ?");
        $stmt->execute([$category_name, $category_id]);
        header("Location: managecategory.php");
        exit();
    }
}

// Delete Category
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM tb_category WHERE id_category = ?");
    $stmt->execute([$delete_id]);
    header("Location: managecategory.php");
    exit();
}

// Fetch all categories
$stmt = $conn->prepare("SELECT * FROM tb_category");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch category data for editing
$edit_category = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM tb_category WHERE id_category = ?");
    $stmt->execute([$edit_id]);
    $edit_category = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manage Kategori - Admin Panel</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rubik&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
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
                <a href="indexx.php" class="nav-item nav-link">Home</a>
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
                        <a href="adminpanel.php" class="list-group-item list-group-item-action bg-dark text-light">Dashboard</a>
                        <a href="manageproduct.php" class="list-group-item list-group-item-action bg-dark text-light">Manage Products</a>
                        <a href="manageorder.php" class="list-group-item list-group-item-action bg-dark text-light">Manage Orders</a>
                        <a href="manageuser.php" class="list-group-item list-group-item-action bg-dark text-light">Manage Users</a>
                        <a href="managecategory.php" class="list-group-item list-group-item-action bg-dark text-light active">Manage Category</a>
                        <a href="adminsettings.php" class="list-group-item list-group-item-action bg-dark text-light">Settings</a>
                    </div>
                </div>
            </div>
            <!-- Sidebar End -->

            <!-- Main Content Start -->
            <div class="col-lg-10">
                <div class="container p-4">
                    <h2>Manage Kategori</h2>
                    <p>Here you can manage all the categories listed on AlatCampingKu.</p>

                    <!-- Add New Category Button -->
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">Add New Category</button>

                    <!-- Categories Table -->
                    <table class="table table-striped table-bordered mt-3">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($category['id_category']); ?></td>
                                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                                    <td>
                                        <a href="managecategory.php?edit_id=<?php echo $category['id_category']; ?>" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editCategoryModal" data-id="<?php echo $category['id_category']; ?>" data-name="<?php echo htmlspecialchars($category['name']); ?>">Edit</a>
                                        <a href="managecategory.php?delete_id=<?php echo $category['id_category']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">Hapus</a>
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

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="managecategory.php">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="category_name">Category Name</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="managecategory.php">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_category_name">Category Name</label>
                            <input type="text" class="form-control" id="edit_category_name" name="category_name" required>
                            <input type="hidden" id="edit_category_id" name="category_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="edit_category" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    <div class="container-fluid bg-secondary py-4 px-sm-3 px-md-5">
        <p class="mb-2 text-center text-body">&copy; <a href="#">AlatCampingKu</a>. All Rights Reserved.</p>
        <p class="m-0 text-center text-body">Designed by <a href="https://htmlcodex.com">HTML Codex</a></p>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript for handling modal data -->
    <script>
        $('#editCategoryModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var categoryId = button.data('id');
            var categoryName = button.data('name');
            var modal = $(this);
            modal.find('#edit_category_name').val(categoryName);
            modal.find('#edit_category_id').val(categoryId);
        });
    </script>
</body>
</html>
