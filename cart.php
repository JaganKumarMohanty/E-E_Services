<?php
session_start();
require 'db.php';

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT c.id, c.qty, p.name, p.price, p.image 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Cart - SabjiMandi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: linear-gradient(to bottom right, #e8f5e9, #f1f8e9);
  min-height: 100vh;
}
.navbar {
  background: linear-gradient(90deg, #43a047, #2e7d32);
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
.navbar-brand {
  font-size: 28px;
  font-weight: bold;
  color: white !important;
}
.btn-success {
  border-radius: 10px;
  font-weight: bold;
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">🌿 SabjiMandi</a>
    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><span class="nav-link text-white">👋 Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span></li>
        <li class="nav-item"><a class="nav-link text-white" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="my_orders.php">My Orders</a></li>
        <li class="nav-item"><a class="nav-link text-warning fw-bold" href="cart.php">🛒 Cart</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main Container -->
<div class="container py-5">
  <div class="card shadow-lg border-0 mx-auto" style="max-width: 900px; border-radius: 20px; background: rgba(255,255,255,0.95);">
    <div class="card-body p-4">
      <h2 class="text-center mb-4 text-success fw-bold">🛍️ My Shopping Cart</h2>

      <?php if($result->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
          <thead style="background-color: #81c784; color: white;">
            <tr>
              <th>Product</th>
              <th>Qty</th>
              <th>Price</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
          <?php $grand_total = 0; ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <?php $total = $row['price'] * $row['qty']; ?>
            <?php $grand_total += $total; ?>
            <tr style="background-color: #f9fbe7;">
              <td class="text-start d-flex align-items-center" style="gap:10px;">
                <?php if(!empty($row['image']) && file_exists('uploads/'.$row['image'])): ?>
                  <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="" style="height:60px;width:60px;border-radius:8px;object-fit:cover;">
                <?php else: ?>
                  <img src="https://via.placeholder.com/60" alt="" style="border-radius:8px;">
                <?php endif; ?>
                <span class="fw-semibold text-dark"><?php echo htmlspecialchars($row['name']); ?></span>
              </td>
              <td><span class="badge bg-success fs-6 px-3 py-2"><?php echo $row['qty']; ?></span></td>
              <td>₹<?php echo number_format($row['price'],2); ?></td>
              <td class="text-success fw-bold">₹<?php echo number_format($total,2); ?></td>
            </tr>
          <?php endwhile; ?>
          <tr style="background-color:#e8f5e9;">
            <td colspan="3" class="text-end fw-bold fs-5">Grand Total:</td>
            <td class="fw-bold fs-5 text-success">₹<?php echo number_format($grand_total,2); ?></td>
          </tr>
          </tbody>
        </table>
      </div>

      <div class="text-center mt-4 d-flex justify-content-center gap-3">
    
    <!-- Continue Shopping Button -->
    <a href="index.php" class="btn btn-outline-success px-4 py-2">
        ← Add More Products
    </a>

    <!-- Proceed to Checkout Button -->
    <a href="checkout.php" class="btn btn-success px-4 py-2">
        Proceed to Checkout →
    </a>
</div>


      <?php else: ?>
        <div class="alert alert-info text-center mt-4">
          Your cart is empty 🛒 <br>
          <a href="index.php" class="btn btn-outline-success mt-3">Shop Now</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
