<?php
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
?>