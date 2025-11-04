<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">

    <!-- Brand -->
    <a class="navbar-brand fw-bold fs-4 text-success" href="index.php">
      SabjiMandi
    </a>

    <!-- Toggle Btn -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu Items -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">

        <?php if (!empty($_SESSION['user_id'])): ?>

          <!-- Username -->


          <!-- Search Form -->
<form class="d-flex ms-3" action="search.php" method="GET">
  <input class="form-control me-2" type="search" name="q" placeholder="Search vegetables/fruits..." required>
  <button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>
</form>

          <li class="nav-item me-3">
            <span class="text-secondary fw-semibold">
              👋 Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </span>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="cart.php">🛒 Cart</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="my_orders.php">📦 My Orders</a>
          </li>

          <li class="nav-item">
            <a class="nav-link text-success fw-bold" href="farmer/dashboard.php">Farmer Dashboard</a>
          </li>

        <?php else: ?>

          <li class="nav-item">
            <a class="nav-link" href="register.php">User Register</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="login.php">Login</a>
          </li>

        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link" href="admin_login.php">Admin</a>
        </li>

        <?php if (!empty($_SESSION['user_id'])): ?>
          <li class="nav-item ms-2">
            <a class="btn btn-outline-danger btn-sm" href="logout.php">Logout</a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

