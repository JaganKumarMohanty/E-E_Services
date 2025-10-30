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
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Cart</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>My Cart</h2>
    <table class="table table-bordered">
        <thead>
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
            <tr>
                <td>
                    <?php if(!empty($row['image']) && file_exists('uploads/'.$row['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" style="height:50px;">
                    <?php endif; ?>
                    <?php echo htmlspecialchars($row['name']); ?>
                </td>
                <td><?php echo $row['qty']; ?></td>
                <td>₹<?php echo number_format($row['price'],2); ?></td>
                <td>₹<?php echo number_format($total,2); ?></td>
            </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
            <td><strong>₹<?php echo number_format($grand_total,2); ?></strong></td>
        </tr>
        </tbody>
    </table>
    <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
</div>
</body>
</html>
