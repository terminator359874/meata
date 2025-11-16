<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$email || !$password) {
        echo "Заполните все поля.";
        exit;
    }

    // Проверка существующего email
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        echo "Пользователь с таким email уже существует.";
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $passwordHash);
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        echo "Ошибка регистрации: " . $stmt->error;
    }
}
?>
<!-- регистрационная форма (если доступ по GET) -->
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<title>Регистрация</title>
</head>
<body>
<h2>Регистрация</h2>
<form action="register.php" method="post">
  <input type="text" name="username" placeholder="Имя пользователя" required><br>
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Пароль" required><br>
  <button type="submit">Зарегистрироваться</button>
</form>
<p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
</body>
</html>
