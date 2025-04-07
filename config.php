<?php
// config.php
$host = "localhost";
$username = "اسم_المستخدم_قاعدة_البيانات";
$password = "كلمة_المرور_قاعدة_البيانات";
$dbname = "اسم_قاعدة_البيانات";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>