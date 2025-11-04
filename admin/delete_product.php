<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

require '../db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin_panel.php?error=Invalid+Product+ID");
    exit;
}

$id = intval($_GET['id']);

// 🔹 Fetch product image
$stmt = $conn->prepare("SELECT image FROM products WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $image = $row['image'];

    // 🔹 Delete image file from uploads folder
    if (!empty($image) && file_exists("uploads/" . $image)) {
        unlink("uploads/" . $image);
    }
}

// 🔹 Now delete product record
$stmt = $conn->prepare("DELETE FROM products WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin_panel.php?msg=Product+Deleted+Successfully");
} else {
    header("Location: admin_panel.php?error=Failed+To+Delete+Product");
}

exit;
?>
