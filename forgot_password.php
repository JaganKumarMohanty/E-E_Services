<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND mobile = ?");
    $stmt->bind_param("ss", $email, $mobile);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        // ❌ No hashing — storing plain text password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ? AND mobile = ?");
        $stmt->bind_param("sss", $newPassword, $email, $mobile);
        $stmt->execute();
        $stmt->close();
        $message = "✅ Password updated successfully.";
    } else {
        $message = "❌ Invalid email or mobile number.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reset Password</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #74ebd5, #acb6e5);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .form-container {
      background: white;
      padding: 2rem 2.5rem;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }
    h2 {
      margin-bottom: 1.5rem;
      color: #333;
    }
    input {
      width: 100%;
      padding: 0.75rem;
      margin: 0.5rem 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1rem;
    }
    button {
      width: 100%;
      padding: 0.75rem;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 1rem;
    }
    button:hover {
      background-color: #0056b3;
    }
    .message {
      margin-top: 1rem;
      font-weight: bold;
      color: #d9534f;
    }
    .success {
      color: #28a745;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Reset Your Password</h2>
    <form method="POST">
      <input type="email" name="email" placeholder="Email address" required />
      <input type="text" name="mobile" placeholder="Mobile number" required />
      <input type="password" name="new_password" placeholder="New password" required />
      <button type="submit">Update Password</button>
    </form>
    <?php if (isset($message)): ?>
      <div class="message <?= strpos($message, '✅') !== false ? 'success' : '' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>

