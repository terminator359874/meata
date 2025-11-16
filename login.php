<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        echo "Заполните все поля.";
        exit;
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit;
        } else {
            echo "Неверный пароль.";
            exit;
        }
    } else {
        echo "Пользователь не найден.";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<title>Вход</title>
</head>
<body>
<h2>Вход</h2>
<form action="login.php" method="post">
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Пароль" required><br>
  <button type="submit">Войти</button>
</form>
<p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
</body>
</html>
