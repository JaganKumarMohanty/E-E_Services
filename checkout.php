<?php
session_start();
require 'db.php';

if(empty($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$stmt = $conn->prepare("SELECT c.product_id, c.qty, p.price FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = $result->fetch_all(MYSQLI_ASSOC);

if(empty($cart_items)){
    die("Cart is empty. <a href='index.php'>Go back</a>");
}

// Calculate total
$total = 0;
foreach($cart_items as $item){
    $total += $item['price'] * $item['qty'];
}

// Insert into orders
$stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert into order_items
$stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?, ?, ?, ?)");
foreach($cart_items as $item){
    $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['qty'], $item['price']);
    $stmt->execute();
}

// Clear cart
$conn->query("DELETE FROM cart WHERE user_id=$user_id");

echo "Order placed successfully! <a href='my_orders.php'>View My Orders</a>";
