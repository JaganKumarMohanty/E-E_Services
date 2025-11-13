<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$status_filter = $_GET['status'] ?? 'all';

// Build query with optional status filter
$query = "
    SELECT sr.category, sr.description, sr.status, sr.created_at,
           e.name AS engineer_name, e.contact_number,
           (
             SELECT ROUND(AVG(f.rating), 1)
             FROM feedback f
             WHERE f.engineer_name = e.name
           ) AS avg_rating
    FROM service_requests sr
    LEFT JOIN engineers e ON sr.engineer_id = e.id
    WHERE sr.user_id = ?
";
if ($status_filter !== 'all') {
    $query .= " AND sr.status = ?";
}
$query .= " ORDER BY sr.created_at DESC";

$stmt = $conn->prepare($query);
if ($status_filter !== 'all') {
    $stmt->bind_param("is", $user_id, $status_filter);
} else {
    $stmt->bind_param("i", $user_id);
}
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
      background: linear-gradient(to right, #e0f7fa, #fce4ec);
      font-family: 'Segoe UI', sans-serif;
      padding: 2rem;
    }
    .card {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
      padding: 2rem;
    }
    h2 {
      margin-bottom: 1.5rem;
      color: #333;
      text-align: center;
    }
    .table {
      border-radius: 10px;
      overflow: hidden;
    }
    th {
      background-color: #1976d2;
      color: white;
      text-align: center;
    }
    td {
      vertical-align: middle;
    }
    .badge-status {
      padding: 0.4em 0.8em;
      border-radius: 8px;
      font-size: 0.9rem;
    }
    .badge-pending { background-color: #ff9800; color: white; }
    .badge-completed { background-color: #4caf50; color: white; }
    .badge-cancelled { background-color: #f44336; color: white; }
    .rating-star {
      color: #ffc107;
      font-weight: bold;
    }
    .filter-form {
      max-width: 300px;
      margin: 0 auto 1.5rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <h2>Your Service History</h2>

      <form method="GET" class="filter-form">
        <select name="status" class="form-select" onchange="this.form.submit()">
          <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>All Statuses</option>
          <option value="In Progress" <?= $status_filter === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
          <option value="Pending" <?= $status_filter === 'Pending' ? 'selected' : '' ?>>Pending</option>
          <option value="Resolved" <?= $status_filter === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
          <option value="Cancelled" <?= $status_filter === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
      </form>

      <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered table-hover text-center">
          <thead>
            <tr>
              <th>Category</th>
              <th>Description</th>
              <th>Status</th>
              <th>Engineer</th>
              <th>Mobile</th>
              <th>Rating</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['category']) ?></td>
              <td><?= htmlspecialchars($row['description']) ?></td>
              <td>
                <?php
                  $status = strtolower($row['status']);
                  $badgeClass = match($status) {
                    'pending' => 'badge-pending',
                    'completed' => 'badge-completed',
                    'cancelled' => 'badge-cancelled',
                    default => 'bg-secondary text-white'
                  };
                ?>
                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($row['status']) ?></span>
              </td>
              <td><?= htmlspecialchars($row['engineer_name'] ?? '—') ?></td>
              <td><?= htmlspecialchars($row['contact_number'] ?? '—') ?></td>
              <td>
                <?= $row['avg_rating'] !== null
                  ? "<span class='rating-star'>{$row['avg_rating']} ★</span>"
                  : '—' ?>
              </td>
              <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="alert alert-warning text-center">No service requests found for selected status.</div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>