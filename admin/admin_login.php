<?php
session_start();
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($adminId, $storedPassword);
    
    if ($stmt->fetch() && $password === $storedPassword) {
        $_SESSION['admin_id'] = $adminId;
        header("Location: admin_panel.php");
        exit;
    } else {
        $error = "Invalid credentials.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: #fff;
            padding: 2rem 2.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-card h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
            font-weight: bold;
        }
        .form-control {
            border-radius: 10px;
            padding: 10px;
        }
        .btn-login {
            width: 100%;
            border-radius: 10px;
            padding: 10px;
            font-weight: bold;
            background: #28a745;
            border: none;
        }
        .btn-login:hover {
            background: #218838;
        }
        .alert {
            border-radius: 10px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Admin Login</h2>
        <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-login">Login</button>

            <a href="index.php" class="btn btn-secondary w-100">Back to Home</a>
        </form>
    </div>
</body>
</html>
