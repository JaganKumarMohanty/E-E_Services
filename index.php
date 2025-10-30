<?php
session_start();
require 'db.php';

// fetch products from DB
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<HTML>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E&E Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--<link rel="stylesheet" type="text/css" href="index.css">-->
    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>
<body>
    <!-- Added Header-->
   <header>
     <div class="title">
      <h2><strong>Welcome To E&E Services</strong></h2> 
     </div>
   </header>
   <!-- Added Navbar-->
   <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">E&E</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="aboutus.php">About Us</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Register
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="signup.php">Sign Up</a></li>
            <li><a class="dropdown-item" href="signin.php">Sign In</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="cart.php"><i class="fa fa-shopping-cart" style="font-size:24px"></i></a>
        </li>
        
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
<!-- Added Caraousel-->
<div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="images/carousel1.jpg" height="500px" class="d-block w-100" alt="First Carousel">
    </div>
    <div class="carousel-item">
      <img src="images/carousel2.jpg" height="500px" class="d-block w-100" alt="Second Carousel">
    </div>
    <div class="carousel-item">
      <img src="images/carousel3.jpg" height="500px" class="d-block w-100" alt="Third Carousel">
    </div>
  </div>
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

</body>
</HTML