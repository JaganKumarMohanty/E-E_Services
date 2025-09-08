<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Not logged in → redirect to login page
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; margin: 0; padding: 0; }
        .navbar { background: #007bff; padding: 15px; color: white; }
        .container { max-width: 800px; margin: 50px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        .logout-btn { display: inline-block; padding: 10px 15px; background: red; color: white; text-decoration: none; border-radius: 5px; }
        .logout-btn:hover { background: darkred; }
    </style>
</head>
<body>

<div class="navbar">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['email']); ?> 👋</h2>
</div>

<div class="container">
    <h3>Dashboard</h3>
    <p>You are successfully logged in! 🎉</p>

    <a class="logout-btn" href="logout.php">Logout</a>
</div>

</body>
</html>

