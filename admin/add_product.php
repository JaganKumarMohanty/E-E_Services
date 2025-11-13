<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $description = $_POST['description'];
    $price = $_POST['price'];

    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
    } else {
        $image = null;
    }

    $stmt = $conn->prepare("INSERT INTO products (name, category, description, price, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $name, $category, $description, $price, $image);
    $stmt->execute();
    header("Location: admin_panel.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ffecd2 0%, #fcb69f 100%);
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            padding: 2rem;
            width: 100%;
            max-width: 500px;
        }
        .card h3 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }
        .form-label {
            font-weight: 600;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #f77f00;
            border: none;
            border-radius: 10px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #d86500;
        }
    </style>
</head>
<body>
    <div class="card">
        <h3><i class="fas fa-box-open me-2"></i>Add New Product</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter product name" required>
            </div>
            <div class="mb-3">
            <label class="form-label">Category:</label>
            <select name="category" class="form-select" required>
                <option value="">-- Select Category --</option>
                <option value="Laptop">Laptop</option>
                <option value="Mobile">Mobile</option>
                <option value="Headset">Headset</option>
                <option value="Earbuds">Earbuds</option>
                <option value="Other">Other</option>
            </select>
           </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <input type="text" name="description" class="form-control" placeholder="Enter description" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter price" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Add Product</button>
        </form>
    </div>
</body>
</html>
