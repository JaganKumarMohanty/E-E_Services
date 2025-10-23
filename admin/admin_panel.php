<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require '../db.php';

$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Admin Dashboard</h2>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>

    <a href="add_product.php" class="btn btn-primary mb-3">Add New Product</a>
    <a href="../logout.php" class="btn btn-danger mb-3">Logout</a>
    <table class="table table-bordered">
        <tr><th>ID</th><th>Name</th><th>Price</th><th>Image</th><th>Actions</th></tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= $row['price'] ?></td>
            <td><img src="uploads/<?= $row['image'] ?>" width="80"></td>
            <td>
                <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
