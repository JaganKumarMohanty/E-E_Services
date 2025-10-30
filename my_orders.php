<?php
session_start();
require 'db.php';

if(empty($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch orders
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Orders</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>My Orders</h2>
    <?php while($order = $result->fetch_assoc()): ?>
        <div class="card mb-3">
            <div class="card-header">
                Order #<?= $order['id'] ?> — <?= $order['order_date'] ?> — Status: <?= $order['status'] ?>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $stmt2 = $conn->prepare("SELECT oi.qty, oi.price, p.name FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=?");
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
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Grand Total</strong></td>
                        <td><strong>₹<?= number_format($grand_total,2) ?></strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>
