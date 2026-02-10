<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "law_system");
mysqli_set_charset($conn, "utf8mb4");

if (!$conn) { die("خطأ في الاتصال: " . mysqli_connect_error()); }

function protect() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit(); 
    }
}
?>