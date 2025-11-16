<?php
// db.php
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "culinary_site";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
