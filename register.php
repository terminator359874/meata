<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "culinary_site";

// Подключение к БД
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получаем данные формы
$username = $_POST['username'];
$email = $_POST['email'];
$passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Запись в базу
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $passwordHash);

if ($stmt->execute()) {
    // Сохраняем данные в сессию сразу после регистрации
    $_SESSION['user_id'] = $stmt->insert_id; // id нового пользователя
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;

    header("Location: index.php");
    exit;
} else {
    echo "Ошибка: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
