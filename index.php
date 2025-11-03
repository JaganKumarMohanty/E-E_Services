
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E&E Service Portal</title>

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- GOOGLE FONT -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>

/* ---------- GLOBAL ---------- */
body {
  font-family: 'Poppins', sans-serif;
  background-color: #f6f7f8;
}

/* Navbar */
.navbar-brand {
  font-size: 28px;
  font-weight: 700;
  color: #198754 !important;
}
.nav-link {
  font-weight: 500;
  transition: 0.3s;
}
.nav-link:hover {
  color: #198754 !important;
  transform: translateY(-2px);
}

/* Hero Section */
.hero-section {
  height: 70vh;
  background-image: linear-gradient(rgba(25,135,84,0.40), rgba(25,135,84,0.40)),
  url("images/car2.jpg");
  background-size: cover;
  background-position: center;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  color: white;
}
.hero-btn {
  padding: 12px 28px;
  font-weight: 600;
  border-radius: 30px;
}

/* Facility cards */
.facility-card {
  border-radius: 20px;
  transition: 0.5s;
}
.facility-card:hover {
  transform: translateY(-10px);
  background: #198754 !important;
  color: white;
}

/* Location map */
.card iframe {
  border-radius: 16px;
}

/* Animation */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(25px); }
  to { opacity: 1; transform: translateY(0); }
}
.fade-in { animation: fadeIn 1.2s ease-in-out; }

/* Footer */
.footer-top {
  background: #198754;
  color: white;
  padding: 40px 0;
}
.footer-bottom {
  background: #145c3f;
  color: white;
  padding: 10px 0;
}
.footer-links a {
  display: block;
  color: white;
  text-decoration: none;
  margin-bottom: 6px;
}
.footer-links a:hover {
  color: #ffe97a;
  margin-left: 6px;
}
</style>

</head>
<body>

<!-- ✅ NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php">E&E Service Portal</a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="signup.php">User Register</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ✅ HERO SECTION -->
<section class="hero-section text-center fade-in">
  <h1 class="display-3 fw-bold">Smart Products. Skilled Service. Daily ⚡</h1>
  <p class="lead mt-3">Delivered directly from Shop to your doorstep.</p>
  <a href="dashboard.php" class="btn btn-light hero-btn">Shop Now</a>
</section>

<!-- ✅ OUR FACILITIES -->
<section class="container my-5">
  <h2 class="text-center mb-4 text-success">✨ Our Facilities</h2>
  <div class="row g-4">

    <div class="col-md-4">
      <div class="card p-4 text-center shadow facility-card">
        🚚 <h5 class="fw-bold mt-2">Fast Delivery</h5>
        <p>Certified electricians. Trusted products. Prompt delivery.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-4 text-center shadow facility-card">
        🛡️ <h5 class="fw-bold mt-2">Quality Assurance</h5>
        <p>We ensure the best quality & freshness.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-4 text-center shadow facility-card">
        💰 <h5 class="fw-bold mt-2">Affordable Prices</h5>
        <p>Best price — no middlemen involved.</p>
      </div>
    </div>

  </div>
</section>

<!-- ✅ STORE LOCATIONS -->
<section class="container my-5">
  <h2 class="text-center mb-4 text-success">📍 Our Store Locations</h2>

  <div class="row g-4">

    <!-- Location 1 -->
    <div class="col-md-6 fade-in">
      <div class="card shadow border-0 p-0">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.11046901863!2d-122.41941558468137!3d37.77492927975902!"
          width="100%" height="350" allowfullscreen loading="lazy">
        </iframe>
      </div>
      <h5 class="fw-bold text-center mt-3">Main Market Store</h5>
      <p class="text-muted text-center">Near Bus Stand, City - Pincode</p>
    </div>

    <!-- Location 2 -->
    <div class="col-md-6 fade-in">
      <div class="card shadow border-0 p-0">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.11046901863!2d-122.41941558468137!3d37.77492927975902!"
          width="100%" height="350" allowfullscreen loading="lazy">
        </iframe>
      </div>
      <h5 class="fw-bold text-center mt-3">Branch Store</h5>
      <p class="text-muted text-center">Near Railway Station, City - Pincode</p>
    </div>

  </div>
</section>

<!-- ✅ FOOTER -->
<footer>
  <div class="footer-top text-center">
    <h4 class="footer-title">E&E Service Portal</h4>
    <p>Innovative. Affordable. Straight to Your Door.</p>
  </div>

  <div class="footer-bottom text-center">
    <small>© 2025 E&E Service Portal. All rights reserved.</small>
  </div>
</footer>

</body>
</html>
