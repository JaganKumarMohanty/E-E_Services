<?php
session_start();
require 'db.php';

if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit;
}

$order_id = intval($_GET['order_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Thank You - E&E Service Portal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(to bottom right, #e8f5e9, #f1f8e9);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.card {
    border-radius: 15px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    background: #fff;
}
h1 {
    color: #2e7d32;
    font-size: 2.5rem;
    margin-bottom: 20px;
}
p {
    font-size: 1.2rem;
    margin-bottom: 25px;
}
.btn-success {
    border-radius: 10px;
    font-weight: bold;
}
</style>
</head>
<body>

<div class="card">
    <h1>🎉 Thank You!</h1>
    <p>Your order <strong>#<?php echo $order_id; ?></strong> has been placed successfully.</p>
    <p>We will process your order and notify you once it is on the way.</p>
    <a href="index.php" class="btn btn-success px-4 py-2">Back to Home</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
