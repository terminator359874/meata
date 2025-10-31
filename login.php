<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "culinary_site";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

// Проверка пользователя
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        // Сохраняем данные в сессию
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['username'] = $row['username']; // вот этого не хватало!
        
        header("Location: index.php");
        exit;
    } else {
        echo "Неверный пароль!";
    }
} else {
    echo "Пользователь не найден!";
}

$stmt->close();
$conn->close();
?>
