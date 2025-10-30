<?php
session_start();
require 'db.php';

// only logged-in users can add to cart
if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $qty = max(1, intval($_POST['qty']));

    // check if product already in cart
    $stmt = $conn->prepare("SELECT id, qty FROM cart WHERE user_id=? AND product_id=?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // update quantity
        $new_qty = $row['qty'] + $qty;
        $stmt = $conn->prepare("UPDATE cart SET qty=? WHERE id=?");
        $stmt->bind_param("ii", $new_qty, $row['id']);
        $stmt->execute();
    } else {
        // insert new record
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, qty) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $qty);
        $stmt->execute();
    }
}

header("Location: cart.php");
exit;
