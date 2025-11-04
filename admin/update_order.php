<?php
session_start();
require '../db.php';

// Check if admin is logged in
if(empty($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit;
}

// Check POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status   = $_POST['status'];

    // Update the order status
    $stmt = $conn->prepare("UPDATE product_orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "✅ Order #$order_id status updated to '$status'.";
    } else {
        $_SESSION['msg'] = "❌ Failed to update status: " . $stmt->error;
    }
}

// Redirect back to admin orders page
header("Location: admin_orders.php");
exit;
?>
