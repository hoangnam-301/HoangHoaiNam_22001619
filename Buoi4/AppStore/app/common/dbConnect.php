<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "shopping_cart";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>