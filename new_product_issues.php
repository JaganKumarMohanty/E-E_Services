<?php
require 'db.php'; // your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $customer_name = $_POST['customer_name'];
    $email = $_POST['email'];
    $issue_type = $_POST['issue_type'];
    $description = $_POST['description'];

    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
    }

    $stmt = $conn->prepare("INSERT INTO new_product_issues (product_id, customer_name, email, issue_type, description, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $product_id, $customer_name, $email, $issue_type, $description, $image);
    $stmt->execute();

    echo "<div class='alert alert-success'>Issue reported successfully!</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Report Product Issue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h2>Report an Issue</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Product ID:</label>
      <input type="number" name="product_id" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Your Name:</label>
      <input type="text" name="customer_name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Email:</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Issue Type:</label>
      <select name="issue_type" class="form-control" required>
        <option value="Defective">Defective</option>
        <option value="Not Working">Not Working</option>
        <option value="Missing Parts">Missing Parts</option>
        <option value="Other">Other</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Description:</label>
      <textarea name="description" class="form-control" rows="4" required></textarea>
    </div>
    <div class="mb-3">
      <label>Upload Image (optional):</label>
      <input type="file" name="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Submit Issue</button>
  </form>
</body>
</html>
