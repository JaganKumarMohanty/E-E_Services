<?php
session_start();
require '../db.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['status'])) {
    $request_id = $_POST['request_id'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE service_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $request_id);
    $stmt->execute();
}

// Handle filters
$status_filter = $_GET['status'] ?? '';
$category_filter = $_GET['category'] ?? '';

$where = [];
$params = [];
$types = '';

if ($status_filter !== '') {
    $where[] = "sr.status = ?";
    $params[] = $status_filter;
    $types .= 's';
}
if ($category_filter !== '') {
    $where[] = "sr.category = ?";
    $params[] = $category_filter;
    $types .= 's';
}

$sql = "
SELECT sr.id, sr.category, sr.description, sr.status, sr.created_at,
       u.name AS user_name, u.email AS user_email,
       e.name AS engineer_name, e.contact_number AS engineer_mobile
FROM service_requests sr
LEFT JOIN users u ON sr.user_id = u.id
LEFT JOIN engineers e ON sr.engineer_id = e.id
";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY sr.created_at DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - Service Requests</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #eef2f3, #8e9eab);
      font-family: 'Segoe UI', sans-serif;
      padding: 2rem;
      animation: fadeIn 1s ease-in;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    h2 {
      margin-bottom: 1.5rem;
      color: #333;
      text-align: center;
      animation: fadeIn 1s ease-in;
    }
    .table {
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      animation: fadeIn 1.2s ease-in;
    }
    th {
      background-color: #4a90e2;
      color: white;
    }
    td, th {
      vertical-align: middle;
    }
    .form-select, .form-control {
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    .form-select:focus, .form-control:focus {
      box-shadow: 0 0 5px rgba(74,144,226,0.5);
    }
    .btn-sm {
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }
    .btn-sm:hover {
      background-color: #357ab8;
    }
    .filter-form {
      background: #fff;
      padding: 1rem;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      margin-bottom: 2rem;
      animation: fadeIn 1s ease-in;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>🛠️ Admin Panel - Manage Service Requests</h2>

    <!-- Filter Form -->
    <form method="GET" class="row g-3 filter-form">
      <div class="col-md-4">
        <select name="status" class="form-select">
          <option value="">Filter by Status</option>
          <option value="Pending" <?= $status_filter === 'Pending' ? 'selected' : '' ?>>Pending</option>
          <option value="In Progress" <?= $status_filter === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
          <option value="Resolved" <?= $status_filter === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
          <option value="Rejected" <?= $status_filter === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>
      </div>
      <div class="col-md-4">
        <select name="category" class="form-select">
          <option value="">Filter by Category</option>
          <option value="Electrical" <?= $category_filter === 'Electrical' ? 'selected' : '' ?>>Electrical</option>
          <option value="Plumbing" <?= $category_filter === 'Plumbing' ? 'selected' : '' ?>>Plumbing</option>
          <option value="Appliance Repair" <?= $category_filter === 'Appliance Repair' ? 'selected' : '' ?>>Appliance Repair</option>
          <option value="IT Support" <?= $category_filter === 'IT Support' ? 'selected' : '' ?>>IT Support</option>
        </select>
      </div>
      <div class="col-md-4">
        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
      </div>
    </form>

    <?php if ($result->num_rows > 0): ?>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>User</th>
            <th>Email</th>
            <th>Category</th>
            <th>Description</th>
            <th>Status</th>
            <th>Engineer</th>
            <th>Mobile</th>
            <th>Date</th>
            <th>Update Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td><?= htmlspecialchars($row['user_email']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><strong><?= htmlspecialchars($row['status']) ?></strong></td>
            <td><?= htmlspecialchars($row['engineer_name'] ?? '—') ?></td>
            <td><?= htmlspecialchars($row['engineer_mobile'] ?? '—') ?></td>
            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
            <td>
              <form method="POST" class="d-flex">
                <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                <select name="status" class="form-select form-select-sm me-2">
                  <option value="Pending" <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                  <option value="In Progress" <?= $row['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                  <option value="Resolved" <?= $row['status'] === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                  <option value="Rejected" <?= $row['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Update</button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="alert alert-warning">No service requests found for selected filters.</div>
    <?php endif; ?>
  </div>
</body>
</html>
