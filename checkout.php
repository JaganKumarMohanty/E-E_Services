<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT c.qty, p.name, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

$total = 0;
$items = [];
while ($row = $res->fetch_assoc()) {
  $subtotal = $row['qty'] * $row['price'];
  $total += $subtotal;
  $items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Checkout - E&E Service Portal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: #f9fbe7;
}
.card {
  border-radius: 15px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.section-title {
  color: #2e7d32;
  font-weight: 600;
  font-size: 1.3rem;
  margin-bottom: 15px;
}
</style>
</head>
<body>

<div class="container py-5">
  <h2 class="text-center mb-4 text-success fw-bold">🛒 Checkout</h2>
  
  <div class="row g-4">
    <!-- Left Side: Address & Payment -->
    <div class="col-lg-7">
      <div class="card p-4">
        <h5 class="section-title">1️⃣ Delivery Details</h5>
        <form id="checkoutForm" action="place_order.php" method="post">
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="fullname" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Street Address</label>
            <input type="text" name="street" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">City</label>
            <select name="city" class="form-select" required>
              <option value="">Select City</option>
              <option>Delhi</option>
              <option>Mumbai</option>
              <option>Pune</option>
              <option>Chennai</option>
              <option>Kolkata</option>
              <option>Bengaluru</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Pincode</label>
            <input type="text" name="pincode" id="pincode" class="form-control" pattern="[0-9]{6}" required onkeyup="checkAvailability()">
            <small id="availability" class="text-muted"></small>
          </div>
          <div class="mb-3">
            <label class="form-label">Current Location</label>
            <input type="text" name="current_location" id="currentLocation" class="form-control" placeholder="Click 'Get My Location'" readonly>
            <button type="button" class="btn btn-outline-success btn-sm mt-2" onclick="getLocation()">📍 Get My Location</button>
          </div>

          <hr>

          <h5 class="section-title">2️⃣ Payment Method</h5>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" value="COD" checked>
            <label class="form-check-label">💵 Cash on Delivery</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" value="UPI">
            <label class="form-check-label">📱 UPI Payment</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" value="Card">
            <label class="form-check-label">💳 Debit/Credit Card</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" value="Netbanking">
            <label class="form-check-label">🏦 Netbanking</label>
          </div>

          <input type="hidden" name="total_amount" value="<?php echo $total; ?>">

          <div class="text-center mt-4">
            <button type="submit" class="btn btn-success px-4 py-2">Place Order →</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Right Side: Summary -->
    <div class="col-lg-5">
      <div class="card p-4">
        <h5 class="section-title">🧾 Order Summary</h5>
        <ul class="list-group list-group-flush mb-3">
          <?php foreach($items as $item): ?>
            <li class="list-group-item d-flex justify-content-between">
              <?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['qty']; ?>
              <span>₹<?php echo number_format($item['qty']*$item['price'],2); ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
        <p class="d-flex justify-content-between"><strong>Subtotal:</strong> <span>₹<?php echo number_format($total,2); ?></span></p>
        <p class="d-flex justify-content-between"><strong>Delivery Charge:</strong> <span>₹40</span></p>
        <p class="d-flex justify-content-between"><strong>Total Payable:</strong> <span class="text-success fw-bold">₹<?php echo number_format($total+40,2); ?></span></p>
      </div>
    </div>
  </div>
</div>

<script>
function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      document.getElementById('currentLocation').value =
        `Lat: ${position.coords.latitude.toFixed(4)}, Lon: ${position.coords.longitude.toFixed(4)}`;
    }, function() {
      alert("Unable to fetch your location");
    });
  }
}

function checkAvailability() {
  let pin = document.getElementById('pincode').value;
  let availability = document.getElementById('availability');
  if (pin.length === 6) {
    let availablePins = ['400001','110001','560001','600001','700001','411001','761119'];
    if (availablePins.includes(pin)) {
      availability.textContent = "✅ Delivery available at your location.";
      availability.className = "text-success";
    } else {
      availability.textContent = "❌ Sorry, service not available for this pincode.";
      availability.className = "text-danger";
    }
  } else {
    availability.textContent = "";
  }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
