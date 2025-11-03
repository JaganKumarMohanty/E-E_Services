<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $fullname = $_POST['fullname'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];
    $current_location = $_POST['current_location'];
    $payment_method = $_POST['payment_method'];
    $total_amount = $_POST['total_amount'];

    // Insert into product_orders table
    $stmt = $conn->prepare("INSERT INTO product_orders (user_id, fullname, street, city, pincode, current_location, payment_method, total_amount, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    if (!$stmt) {
        die("Prepare failed (product_orders): " . $conn->error);
    }
    $stmt->bind_param("issssssd", $user_id, $fullname, $street, $city, $pincode, $current_location, $payment_method, $total_amount);
    if (!$stmt->execute()) {
        die("Execute failed (product_orders): " . $stmt->error);
    }

    $order_id = $stmt->insert_id;
    $stmt->close();

    // Fetch cart items with price from products table
    $sql = "SELECT c.product_id, c.qty, p.price 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id=?";
    $stmt2 = $conn->prepare($sql);
    if (!$stmt2) {
        die("Prepare failed (cart): " . $conn->error);
    }
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $res = $stmt2->get_result();

    // Insert items into order_items
    while ($row = $res->fetch_assoc()) {
        $stmt3 = $conn->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?, ?, ?, ?)");
        if (!$stmt3) {
            die("Prepare failed (order_items): " . $conn->error);
        }
        $stmt3->bind_param("iiid", $order_id, $row['product_id'], $row['qty'], $row['price']);
        if (!$stmt3->execute()) {
            die("Execute failed (order_items): " . $stmt3->error);
        }
        $stmt3->close();
    }
    $stmt2->close();

    // Clear cart
    $stmt4 = $conn->prepare("DELETE FROM cart WHERE user_id=?");
    if (!$stmt4) {
        die("Prepare failed (clear cart): " . $conn->error);
    }
    $stmt4->bind_param("i", $user_id);
    $stmt4->execute();
    $stmt4->close();

    // Redirect to thank you page
    header("Location: thank_you.php?order_id=" . $order_id);
    exit;
} else {
    header("Location: cart.php");
    exit;
}
?>
