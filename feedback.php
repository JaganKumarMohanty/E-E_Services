<?php
session_start();
require_once 'db.php'; // uses $conn from your MySQLi db.php

// Fetch engineer names
$engineers = [];
$result = $conn->query("SELECT name FROM engineers ORDER BY name ASC");
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $engineers[] = $row['name'];
  }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_POST['user_id'] ?? '';
  $type = $_POST['feedback_type'] ?? '';
  $product_id = $_POST['product_id'] ?? null;
  $engineer = $_POST['engineer_name'] ?? null;
  $rating = $_POST['rating'] ?? '';
  $comments = $_POST['comments'] ?? '';

  if ($user_id && $type && $rating && (
      ($type === 'product' && $product_id) ||
      ($type === 'service' && $engineer)
    )) {
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, type, product_id, engineer_name, rating, comments) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssis", $user_id, $type, $product_id, $engineer, $rating, $comments);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Thank you for your feedback!'); window.location.href='feedback.php';</script>";
    exit;
  } else {
    echo "<script>alert('Please complete all required fields.'); window.history.back();</script>";
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Feedback</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .rating label {
      font-size: 1.5rem;
      cursor: pointer;
      color: #ccc;
    }
    .rating input:checked ~ label {
      color: #ffc107;
    }
  </style>
  <script>
    function toggleFields() {
      const type = document.getElementById('feedback_type').value;
      document.getElementById('engineer_group').style.display = type === 'service' ? 'block' : 'none';
      document.getElementById('product_group').style.display = type === 'product' ? 'block' : 'none';
    }
  </script>
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4 text-center">Submit Your Feedback</h2>
    <form method="POST" class="card p-4 shadow-sm">
      <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ?? '12345'; ?>">

      <div class="mb-3">
        <label class="form-label">Feedback Type</label>
        <select name="feedback_type" id="feedback_type" class="form-select" onchange="toggleFields()" required>
          <option value="">-- Select --</option>
          <option value="product">Product</option>
          <option value="service">Service</option>
        </select>
      </div>

      <div class="mb-3" id="product_group" style="display:none;">
        <label class="form-label">Product ID</label>
        <input type="text" name="product_id" class="form-control" placeholder="Enter Product ID">
      </div>

      <div class="mb-3" id="engineer_group" style="display:none;">
        <label class="form-label">Engineer Name</label>
        <select name="engineer_name" class="form-select">
          <option value="">-- Select Engineer --</option>
          <?php foreach ($engineers as $name): ?>
            <option value="<?php echo htmlspecialchars($name); ?>"><?php echo htmlspecialchars($name); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Rating</label>
        <div class="rating d-flex gap-2">
          <input type="radio" name="rating" value="1" id="star1" required><label for="star1">★</label>
          <input type="radio" name="rating" value="2" id="star2"><label for="star2">★★</label>
          <input type="radio" name="rating" value="3" id="star3"><label for="star3">★★★</label>
          <input type="radio" name="rating" value="4" id="star4"><label for="star4">★★★★</label>
          <input type="radio" name="rating" value="5" id="star5"><label for="star5">★★★★★</label>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Comments</label>
        <textarea name="comments" class="form-control" rows="4" placeholder="Share your thoughts..."></textarea>
      </div>

      <button type="submit" class="btn btn-primary w-100">Submit Feedback</button>
    </form>
  </div>
</body>
</html>