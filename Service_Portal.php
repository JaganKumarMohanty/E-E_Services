<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];
$last_service = "N/A";
$next_booking = "N/A";
$total_issues = 0;

// Last service
$sql = "SELECT category FROM service_requests WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($service_type);
if ($stmt->fetch()) {
    $last_service = $service_type;
}
$stmt->close();

// Next booking
$sql2 = "SELECT created_at FROM service_requests WHERE user_id = ? AND created_at > CURDATE() ORDER BY created_at ASC LIMIT 1";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$stmt2->bind_result($booking_date);
if ($stmt2->fetch()) {
    $next_booking = date("j M Y", strtotime($booking_date));
}
$stmt2->close();

// Total issues
$sql3 = "SELECT COUNT(*) FROM service_requests WHERE user_id = ?";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $user_id);
$stmt3->execute();
$stmt3->bind_result($issue_count);
if ($stmt3->fetch()) {
    $total_issues = $issue_count;
}
$stmt3->close();
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
      transition: transform 0.3s ease;
    }
    .profile-card:hover {
      transform: translateY(-5px);
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
      color: #007bff;
    }
    .list-group-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 1rem;
    }
    .btn-custom {
      background-color: #007bff;
      color: white;
      border-radius: 5px;
      transition: background-color 0.3s ease;
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
        <li class="nav-item"><a class="nav-link" href="new_product_issues.php">Report Issue</a></li>
        <li class="nav-item"><a class="nav-link" href="service_history.php">Service History</a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Support</a></li>
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
        <h3 class="mb-2"><?= htmlspecialchars($_SESSION['user_name']); ?></h3>
        <p class="text-muted mb-3">👤 Registered User | Electronic & Electrician Services</p>
        <hr class="mb-4">
        <div class="row text-start">
          <div class="col-md-6">
            <h5 class="section-title">🔧 Your Services</h5>
            <ul class="list-group">
              <li class="list-group-item"><span>🛠️</span> Last Service: <?= htmlspecialchars($last_service); ?></li>
              <li class="list-group-item"><span>📅</span> Next Booking: <?= htmlspecialchars($next_booking); ?></li>
              <li class="list-group-item"><span>📊</span> Total Issues Reported: <?= htmlspecialchars($total_issues); ?></li>
            </ul>
          </div>
          <div class="col-md-6">
            <h5 class="section-title">⚡ Quick Actions</h5>
            <div class="d-grid gap-2">
              <a href="new_product_issues.php" class="btn btn-custom">📝 Report New Issue</a>
              <a href="service_history.php" class="btn btn-outline-primary">📜 View Service History</a>
              <a href="contact.php" class="btn btn-outline-secondary">📞 Contact Support</a>
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