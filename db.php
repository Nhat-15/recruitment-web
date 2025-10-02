<?php
$host = "localhost";
$user = "root";   // mặc định XAMPP là root
$pass = "";       // mặc định không có mật khẩu
$db   = "cnjava"; // tên database bạn đã tạo trong MySQL

$conn = new mysqli($host, $user, $pass, $db);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
// echo "Kết nối thành công!"; // test thử
?>
