<?php
session_start();
if (!isset($_SESSION['user_id'])) {
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
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #e0f7fa, #e1bee7);
      min-height: 100vh;
    }
    .profile-card {
      background: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .profile-img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #007bff;
    }
    .navbar-brand {
      font-weight: bold;
    }
    .nav-link:hover {
      text-decoration: underline;
    }
    .section-title {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 20px;
      color: #333;
    }
    .card-box {
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      transition: 0.3s;
    }
    .card-box:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .btn-custom {
      background-color: #007bff;
      color: white;
      border-radius: 5px;
    }
    .btn-custom:hover {
      background-color: #0056b3;
    }
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

<!-- 🌟 Main Body -->
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="profile-card text-center">
        <h3><?= htmlspecialchars($_SESSION['user_name']); ?></h3>
        <p class="text-muted">Registered User | Electronic & Electrician Services</p>
        <hr>
        <div class="row text-start">
          <div class="col-md-6">
            <h5 class="section-title">Your Services</h5>
            <ul class="list-group">
              <li class="list-group-item">Last Service: AC Repair</li>
              <li class="list-group-item">Next Booking: 1 Nov 2025</li>
              <li class="list-group-item">Total Issues Reported: 5</li>
            </ul>
          </div>
          <div class="col-md-6">
            <h5 class="section-title">Quick Actions</h5>
            <div class="d-grid gap-2">
              <a href="new_product_issues.php" class="btn btn-custom">Report New Issue</a>
              <a href="service_history.php" class="btn btn-outline-primary">View Service History</a>
              <a href="#" class="btn btn-outline-secondary">Contact Support</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
