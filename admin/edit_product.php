<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require '../db.php';

$id = $_GET['id'];

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $category = $_POST['category'];

    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
    } else {
        $image = $product['image'];  // keep existing
    }

    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, image=?, category=? WHERE id=?");
    $stmt->bind_param("sdssi", $name, $price, $image, $category, $id);
    $stmt->execute();

    header("Location: admin_panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<div class="card p-4 shadow-sm">
    <h2 class="mb-4">✏ Edit Product</h2>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">Product Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price (₹):</label>
            <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" class="form-control" required>
        </div>

        <!-- Category Dropdown -->
        <div class="mb-3">
            <label class="form-label">Category:</label>
            <select name="category" class="form-select" required>
                <option value="">-- Select Category --</option>
                <option value="Laptop">Laptop</option>
                <option value="Mobile">Mobile</option>
                <option value="Other">Other</option>
            </select>
           </div>

        <div class="mb-3">
            <label class="form-label">Product Image:</label>
            <input type="file" name="image" class="form-control">
            <img src="uploads/<?= $product['image'] ?>" width="100" class="mt-2 rounded">
        </div>

        <button type="submit" class="btn btn-primary w-100">✅ Update Product</button>
    </form>
</div>

</body>
</html>
