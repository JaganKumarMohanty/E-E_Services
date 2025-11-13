<?php
session_start();
require '../db.php';

// Optional: restrict access to admin only
// if ($_SESSION['role'] !== 'admin') { header("Location: login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'] ?? '';
  $keywords = $_POST['issue_keywords'] ?? '';
  $description = $_POST['description'] ?? '';
  $pdf = $_FILES['pdf_file'] ?? null;

  if ($title && $keywords && $pdf && $pdf['type'] === 'application/pdf') {
    $upload_dir = 'uploads/';
    $filename = time() . '_' . basename($pdf['name']);
    $target_path = $upload_dir . $filename;

    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    if (move_uploaded_file($pdf['tmp_name'], $target_path)) {
      $stmt = $conn->prepare("INSERT INTO kb_articles (title, issue_keywords, description, pdf_path) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $title, $keywords, $description, $target_path);
      $stmt->execute();
      echo "<script>alert('KB article uploaded successfully!'); window.location.href='kb_upload.php';</script>";
      exit;
    } else {
      echo "<script>alert('Failed to upload PDF.');</script>";
    }
  } else {
    echo "<script>alert('Please fill all fields and upload a valid PDF.');</script>";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Upload KB Article</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
  <div class="container">
    <h2 class="mb-4">Admin: Upload KB Article</h2>
    <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Issue Keywords (comma-separated)</label>
        <input type="text" name="issue_keywords" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Upload PDF</label>
        <input type="file" name="pdf_file" accept="application/pdf" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Upload Article</button>
    </form>
  </div>
</body>
</html>