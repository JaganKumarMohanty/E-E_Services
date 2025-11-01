<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Not logged in → redirect to login page
    header("Location: index.php");
    exit;
}
// fetch products from DB
require 'db.php';
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Profile | Electronic Service Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body { background-color: #f4f6f9; }
    .profile-card { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .profile-img { width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 3px solid #007bff; }
    .navbar-brand { font-weight: bold; }
    .nav-link:hover { text-decoration: underline; }
     /* search bar */
    .serachbar {
      display: flex;
      justify-content: center; /* Center content horizontally */
      align-items: center;
      background-color: #333;
      padding: 10px;
    }

    .search-container {
      display: flex;
      align-items: center;
    }

    .search-container input[type="text"] {
      padding: 8px;
      font-size: 16px;
      border: none;
      border-radius: 4px 0 0 4px;
    }

    .search-container button {
      padding: 8px 12px;
      font-size: 16px;
      border: none;
      background-color: #4CAF50;
      color: white;
      border-radius: 0 4px 4px 0;
      cursor: pointer;
    }

    .search-container button:hover {
      background-color: #45a049;
    }
     /* carousel*/
    .carousel {
      position: relative;
      max-width: 1600px;
      margin: auto;
      overflow: hidden;
      
    }

    .slides {
      display: flex;
      transition: transform 0.5s ease-in-out;
      width: 100%;
    }

    .slides img {
      width: 100%;
      height: auto;
    }

    .nav-button {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(0,0,0,0.5);
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
      font-size: 18px;
    }

    .prev {
      left: 10px;
    }

    .next {
      right: 10px;
    }
    /*Footer */
    .footer{
   	background:#0b650c;
   	text-align: center;
   	width: 100%;
   	height: auto;
   
} 
.footer h3{
   font-size: 18px;
   padding:8px;
   text-align: center;
   color: white;
}
  </style>
</head>
<body>

<!-- 🔷 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <strong style="color: white;"><p class="text-muted" ><?= "Welcome, " . htmlspecialchars($_SESSION['name']) . "!"; ?></strong>
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    <div class="seachbar">
    <form class="search-container" onsubmit="handleSearch(event)">
      <input type="text" id="searchInput" placeholder="Search..." />
      <button type="submit">Go</button>
    </form>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="Service_Portal.php">ServicePortal</a></li>
        <li class="nav-item"><a class="nav-link active" href="cart.php"><i class="fa fa-shopping-cart" style="font-size:24px"></i></a></li>
        <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="carousel">
    <div class="slides" id="slides">
      <img src="images/car1.jpg"  alt="Slide 1">
      <img src="images/car2.jpg"  alt="Slide 2">
      <img src="images/car3.jpg"  alt="Slide 3">
    </div>
    <button class="nav-button prev" onclick="moveSlide(-1)">❮</button>
    <button class="nav-button next" onclick="moveSlide(1)">❯</button>
  </div>

  <!--Added Grid -->
<div class="container mt-4">
    <div class="row">
        <?php while($p = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <?php if(!empty($p['image']) && file_exists('admin/uploads/'.$p['image'])): ?>
                    <img src="admin/uploads/<?php echo htmlspecialchars($p['image']); ?>" class="card-img-top" alt="">
                <?php else: ?>
                    <img src="https://via.placeholder.com/400x300?text=No+Image" class="card-img-top" alt="">
                <?php endif; ?>
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo htmlspecialchars($p['name']); ?></h5>
                    <h5 class="card-title"><?php echo htmlspecialchars($p['description']); ?></h5>
                    <p><strong>₹ <?php echo number_format($p['price'],2); ?></strong></p>
                    <form method="post" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                        <div class="input-group mb-2">
                            <input type="number" name="qty" value="1" min="1" class="form-control" style="max-width:90px">
                            <button class="btn btn-primary" type="submit">Add to cart</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<!-- Added Footer-->
<div class="container-fluid footer">
      <h3>© 2025 E&E Services. All Rights Reserved | Design by Jagan!</h3>
 </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  //script for carousel
let currentIndex = 0;
    const slides = document.getElementById('slides');
    const totalSlides = slides.children.length;

    function moveSlide(direction) {
      currentIndex = (currentIndex + direction + totalSlides) % totalSlides;
      slides.style.transform = `translateX(-${currentIndex * 100}%)`;
    }

    // Auto-slide every 3 seconds
    setInterval(() => moveSlide(1), 3000);
</script>
</body>
</html>


