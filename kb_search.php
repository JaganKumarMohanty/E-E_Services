<?php
require 'db.php';
$search_term = $_GET['q'] ?? '';
$results = [];

if ($search_term) {
  $stmt = $conn->prepare("
    SELECT title, description, pdf_path
    FROM kb_articles
    WHERE issue_keywords LIKE CONCAT('%', ?, '%')
    ORDER BY created_at DESC
  ");
  $stmt->bind_param("s", $search_term);
  $stmt->execute();
  $results = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>KB Article Search</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gradient p-4" style="background: linear-gradient(to right, #e3f2fd, #fce4ec);">
  <div class="container">
    <h2 class="text-center mb-4">Search KB Articles</h2>
    <form method="GET" class="mb-4">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search by issue (e.g. battery, flicker)" value="<?= htmlspecialchars($search_term) ?>" required>
        <button class="btn btn-primary" type="submit">Search</button>
      </div>
    </form>

    <?php if ($search_term): ?>
      <h5>Results for "<strong><?= htmlspecialchars($search_term) ?></strong>":</h5>
      <?php if ($results->num_rows > 0): ?>
        <?php while ($row = $results->fetch_assoc()): ?>
          <div class="card p-3 mb-3 shadow-sm">
            <h5><?= htmlspecialchars($row['title']) ?></h5>
            <p><?= htmlspecialchars($row['description']) ?></p>
          <a href="<?= 'http://localhost/E&E_Services/admin/' . htmlspecialchars($row['pdf_path']) ?>" class="btn btn-outline-primary" target="_blank">View PDF</a>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="alert alert-warning mt-3">No articles found for that issue.</div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</body>
</html>