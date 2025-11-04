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
  <title>User Profile | Electronic Service Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f4f6f9; }
    .profile-card { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .profile-img { width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 3px solid #007bff; }
    .navbar-brand { font-weight: bold; }
    .nav-link:hover { text-decoration: underline; }
  </style>
</head>
<body>

<!-- 🔷 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><?= "Welcome, " . htmlspecialchars($_SESSION['user_name']) . "!"; ?> To E&E ServicePortal</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="new_product_issues.php">Report Issue</a></li>
        <li class="nav-item"><a class="nav-link" href="service_history.php">Service History</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Support</a></li>
        <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


