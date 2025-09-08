<?php
header('Content-Type: text/plain');
$data = json_decode(file_get_contents('php://input'), true);

// Validate received data
if (
  empty($data['name']) || empty($data['gender']) || empty($data['age']) || 
  empty($data['dob']) || empty($data['email']) || empty($data['mobile']) || 
  empty($data['password'])
) {
    http_response_code(400);
    echo "Please fill all required fields.";
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password_db = "";
$dbname = "ee";

$conn = new mysqli($servername, $username, $password_db, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo "Connection failed: " . $conn->connect_error;
    exit;
}

// Sanitize inputs
function sanitize($conn, $v) {
    return $conn->real_escape_string(trim($v));
}
$name = sanitize($conn, $data['name']);
$gender = sanitize($conn, $data['gender']);
$age = intval($data['age']);
$dob = sanitize($conn, $data['dob']);
$email = sanitize($conn, $data['email']);
$mobile = sanitize($conn, $data['mobile']);
$password = $data['password']; // Raw for hashing

// Validate minimal server-side
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/^\d{10}$/", $mobile) || $age < 1 || $age > 120) {
    http_response_code(400);
    echo "Invalid data format.";
    exit;
}

// Hash password
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Check if email or mobile exists
$check_query = "SELECT id FROM users WHERE email='$email' OR mobile='$mobile'";
$result = $conn->query($check_query);
if ($result && $result->num_rows > 0) {
    http_response_code(409);
    echo "Email or mobile already registered.";
    exit;
}

// Insert user
$stmt = $conn->prepare("INSERT INTO users (name, gender, age, dob, email, mobile, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssissss", $name, $gender, $age, $dob, $email, $mobile, $passwordHash);

if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    http_response_code(500);
    echo "Database error: " . $stmt->error;
}
$stmt->close();
$conn->close();
