<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require '../db.php';

// Fetch all orders
$result = $conn->query("
    SELECT o.*, u.name as user_name 
    FROM product_orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.order_date DESC
");
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders - SabjiMandi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="admin_panel.php">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNavbar">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="admin_orders.php">All Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="add_product.php">Add Product</a></li>
        <li class="nav-item"><a class="nav-link btn btn-danger text-white ms-2" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-4 text-success">All Orders</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while($order = $result->fetch_assoc()): ?>
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    Order #<?= $order['id'] ?> — User: <?= htmlspecialchars($order['user_name']) ?> — <?= date('d M Y H:i', strtotime($order['order_date'])) ?>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Fetch order items using prepared statement
                        $stmt2 = $conn->prepare("
                            SELECT oi.qty, oi.price, p.name 
                            FROM order_items oi 
                            JOIN products p ON oi.product_id = p.id 
                            WHERE oi.order_id = ?
                        ");
                        if ($stmt2) {
                            $stmt2->bind_param("i", $order['id']);
                            $stmt2->execute();
                            $items = $stmt2->get_result();
                            $grand_total = 0;
                            while($item = $items->fetch_assoc()):
                                $total = $item['price'] * $item['qty'];
                                $grand_total += $total;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td><?= $item['qty'] ?></td>
                                <td>₹<?= number_format($item['price'],2) ?></td>
                                <td>₹<?= number_format($total,2) ?></td>
                            </tr>
                        <?php 
                            endwhile;
                            $stmt2->close();
                        } else {
                            echo "<tr><td colspan='4'>Failed to fetch order items.</td></tr>";
                        }
                        ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Grand Total</strong></td>
                            <td><strong>₹<?= number_format($grand_total,2) ?></strong></td>
                        </tr>
                        </tbody>
                    </table>

                    <!-- Update Order Status -->
                    <?php $order_status = $order['status'] ?? 'Pending'; ?>
<form method="post" action="update_order.php" class="mt-2 d-flex align-items-center gap-2">
    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
    <select name="status" class="form-select w-auto">
        <option value="Pending" <?= $order_status == 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Processing" <?= $order_status == 'Processing' ? 'selected' : '' ?>>Processing</option>
        <option value="Delivered" <?= $order_status == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
        <option value="Cancelled" <?= $order_status == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
    </select>
    <button type="submit" class="btn btn-success btn-sm">Update Status</button>
</form>

                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info">No orders found.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
