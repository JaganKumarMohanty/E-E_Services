<?php
session_start();
require 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch service requests with engineer details
$stmt = $conn->prepare("
    SELECT sr.category, sr.description, sr.status, sr.created_at,
           e.name AS engineer_name, e.contact_number
    FROM service_requests sr
    LEFT JOIN engineers e ON sr.engineer_id = e.id
    WHERE sr.user_id = ?
    ORDER BY sr.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Service History</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f4f6f9;
      font-family: 'Segoe UI', sans-serif;
      padding: 2rem;
    }
    .table {
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h2 {
      margin-bottom: 1.5rem;
      color: #333;
    }
    th {
      background-color: #4a90e2;
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Your Service History</h2>
    <?php if ($result->num_rows > 0): ?>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Category</th>
            <th>Description</th>
            <th>Status</th>
            <th>Engineer</th>
            <th>Mobile</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['engineer_name'] ?? '—') ?></td>
            <td><?= htmlspecialchars($row['contact_number'] ?? '—') ?></td>
            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="alert alert-warning">No service requests found.</div>
    <?php endif; ?>
  </div>
</body>
</html>
