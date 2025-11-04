<?php
session_start();
require 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT id,name,password FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param('s',$email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        if ($password === $row['password']) { // plain compare
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['email'] = $email;
            header('Location: dashboard.php'); exit;
        } else {
            $msg = 'Invalid credentials';
        }
    } else {
        $msg = 'User not found';
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: linear-gradient(135deg, #6a11cb, #2575fc);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-card {
        max-width: 400px;
        width: 100%;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0px 8px 20px rgba(0,0,0,0.2);
        padding: 30px;
        animation: fadeIn 0.8s ease-in-out;
    }
    .login-card h2 {
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
        text-align: center;
    }
    .btn-primary, .btn-secondary {
        width: 48%;
        border-radius: 10px;
        padding: 10px;
        font-size: 16px;
        font-weight: 600;
    }
    .btn-container {
        display: flex;
        justify-content: space-between;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>
</head>
<body>
    <div class="login-card">
        <h2>Login</h2>
        <?php if($msg): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control form-control-lg" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control form-control-lg" name="password" placeholder="Enter your password" required>
            </div>
            <div class="btn-container mt-3">
                <button class="btn btn-primary" type="submit">Login</button>
                <a href="register.php" class="btn btn-secondary text-white text-center">Register</a>
            </div>
            <div class="btn-container mt-3">
                <a href="forgot_password.php" class="btn btn-info text-white text-center" style="margin: 0 auto; display: block;"><strong>Reset Password<strong></a>
            </div>
        </form>
    </div>
</body>
</html>
