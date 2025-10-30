<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = "⚠️ Both email and password are required.";
    } else {
        // DB connection
        $conn = new mysqli("localhost", "root", "", "ee");
        if ($conn->connect_error) {
            die("DB connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT id, name, password_hash FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $name, $password_hash);
            $stmt->fetch();

            if (password_verify($password, $password_hash)) {
                // Login success
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $name;
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "❌ ID and Password are incorrect. Please try again!";
            }
        } else {
            $error = "❌ ID and Password are incorrect. Please try again!";
        }

        $stmt->close();
        $conn->close();
    }

    // If error exists, trigger popup
    if (!empty($error)) {
        echo "<script>alert('$error');</script>";
    }
}
?>
<?php
include 'signin.php' ?>
