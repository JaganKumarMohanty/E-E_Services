<?php
session_start();
require 'db.php';

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Handle image upload
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
    }

    // Auto-assign engineer by category
    $stmt = $conn->prepare("SELECT id FROM engineers WHERE category = ? LIMIT 1");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $stmt->bind_result($engineer_id);
    $stmt->fetch();
    $stmt->close();

    // Insert service request
    $stmt = $conn->prepare("INSERT INTO service_requests (user_id, category, description, engineer_id, name, email, address, mobile, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $user_id = $_SESSION['user_id'];
    $stmt->bind_param("ississsss", $user_id, $category, $description, $engineer_id, $name, $email, $address, $mobile, $image);
    $stmt->execute();
    $stmt->close();

    $success = "✅ Service request submitted successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Service Request</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #c9d6ff, #e2e2e2);
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
      max-width: 600px;
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
      background-color: #4a90e2;
      border: none;
      border-radius: 10px;
      font-weight: bold;
    }
    .btn-primary:hover {
      background-color: #357ab8;
    }
    .alert {
      border-radius: 10px;
      font-size: 0.95rem;
    }
  </style>
</head>
<body>
  <div class="card">
    <h3>Submit Service Request</h3>
    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-2">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-2">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-2">
        <label class="form-label">Address</label>
        <input type="text" name="address" class="form-control" required>
      </div>
      <div class="mb-2">
        <label class="form-label">Mobile Number</label>
        <input type="text" name="mobile" class="form-control" required>
      </div>
      <div class="mb-2">
        <label class="form-label">Category</label>
        <select name="category" class="form-control" required>
          <option value="">Select Category</option>
          <option value="Electrical">Electrical</option>
          <option value="Plumbing">Plumbing</option>
          <option value="Appliance Repair">Appliance Repair</option>
          <option value="IT Support">IT Support</option>
        </select>
      </div>
      <div class="mb-2">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4" placeholder="Describe your issue..." required></textarea>
      </div>
      <div class="mb-2">
        <label class="form-label">Upload Issue Image</label>
        <input type="file" name="image" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary w-100">Submit Request</button>
    </form>
  </div>
</body>
</html>
