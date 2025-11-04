<?php
session_start();
require 'db.php';

if (isset($_GET['q'])) {
    $search = mysqli_real_escape_string($conn, $_GET['q']);

    $sql = "SELECT * FROM products WHERE name LIKE '%$search%'";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Search Results</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<?php include "navbar.php"; ?>  <!-- OPTIONAL: if navbar is separate -->

<div class="container mt-5">
    <h3 class="text-success mb-4">🔍 Search Results for: <strong><?php echo htmlspecialchars($search); ?></strong></h3>

    <div class="row">

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($p = $result->fetch_assoc()): ?>

            <div class="col-md-4 mb-4">
                <div class="card product-card shadow border-0">
                    <?php if (!empty($p['image']) && file_exists('uploads/'.$p['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($p['image']); ?>" class="card-img-top" alt="">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/400x300?text=No+Image" class="card-img-top" alt="">
                    <?php endif; ?>

                    <div class="card-body text-center">
                        <h5 class="fw-bold"><?php echo htmlspecialchars($p['name']); ?></h5>
                        <p class="text-success fs-5">₹ <?php echo number_format($p['price'],2); ?></p>

                        <form method="post" action="add_to_cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                            <div class="input-group justify-content-center mb-3" style="max-width: 180px; margin: 0 auto;">
                                <input type="number" name="qty" value="1" min="1" class="form-control text-center border-success">
                                <button class="btn btn-success" type="submit">Add to Cart</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php endwhile; ?>
        <?php else: ?>
            <h4 class="text-danger text-center">❌ No products found.</h4>
        <?php endif; ?>

    </div>
</div>

</body>
</html>
