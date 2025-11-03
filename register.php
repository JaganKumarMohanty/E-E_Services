<?php
session_start();
require 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // ⚠️ Plain text (not secure, only as per your request)
    $mobile = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO users (name,email,password,mobile) VALUES (?,?,?,?)");
    $stmt->bind_param('ssss',$name,$email,$password,$mobile);
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['user_name'] = $name;
        header('Location: login.php'); exit;
    } else {
        $msg = 'Error: ' . $conn->error;
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: linear-gradient(135deg, #ff6a00, #ee0979);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }
    .register-card {
        max-width: 420px;
        width: 100%;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0px 8px 25px rgba(0,0,0,0.2);
        padding: 35px;
        animation: fadeIn 0.8s ease-in-out;
    }
    .register-card h2 {
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
        text-align: center;
    }
    .form-label {
        font-weight: 600;
        color: #444;
    }
    .form-control {
        border-radius: 10px;
        padding: 12px;
    }
    .btn-primary {
        width: 100%;
        border-radius: 10px;
        padding: 12px;
        font-size: 16px;
        font-weight: 600;
        background: linear-gradient(135deg, #ff6a00, #ee0979);
        border: none;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(238, 9, 121, 0.4);
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }
    .extra-link {
        text-align: center;
        margin-top: 15px;
    }
    .extra-link a {
        text-decoration: none;
        font-weight: 600;
        color: #ee0979;
    }
    .extra-link a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
    <div class="register-card">
        <h2>Create Account</h2>
        <?php if($msg): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input class="form-control" name="name" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Enter a password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input class="form-control" name="phone" placeholder="Enter your phone number">
            </div>
            <button class="btn btn-primary">Register</button>
        </form>
        <div class="extra-link">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>
