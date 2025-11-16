<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<title>Добавить рецепт</title>
<style>
body {
  font-family: Arial;
  background: #f9f9f9;
  padding: 30px;
}

form {
  max-width: 500px;
  margin: auto;
  background: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  align-items: center;
}

h2, h3 {
  text-align: center;
}

.form-group {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
}

input, textarea, select {
  width: 100%;
  max-width: 500px;
  margin-bottom: 15px;
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

label {
  width: 100%;
  max-width: 500px;
  margin-bottom: 5px;
  font-weight: bold;
}

button {
  background: #28a745;
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 8px;
  cursor: pointer;
  margin-top: 10px;
}

a {
  text-decoration: none;
  color: #007bff;
}
.submit-button {
  background: #28a745;
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 8px;
  cursor: pointer;
  margin-top: 20px;
  font-weight: bold;
}

.submit-button:hover {
  background: #218838;
}
.submit-container {
  width: 100%;
  max-width: 500px;
  text-align: right;
}
</style>
</head>
<body>
<form action="handle_add_recipe.php" method="post" enctype="multipart/form-data">
  <h2>Добавить рецепт</h2>

  <div class="form-group">
    <input type="text" name="title" placeholder="Название" required>
  </div>

  <div class="form-group">
    <input type="text" name="cook_time" placeholder="Время приготовления (например: 30 минут)">
  </div>

  <div class="form-group">
    <textarea name="description" placeholder="Описание" rows="4"></textarea>
  </div>

  <div class="form-group">
    <textarea name="ingredients" placeholder="Ингредиенты" rows="4"></textarea>
  </div>

  <div class="form-group">
    <textarea name="instructions" placeholder="Инструкция приготовления" rows="6"></textarea>
  </div>

  <div class="form-group">
    <label>Тип рецепта:</label>
    <select name="type" required>
      <option value="">Выберите тип</option>
      <option value="Завтрак">Завтрак</option>
      <option value="Обед">Обед</option>
      <option value="Ужин">Ужин</option>
    </select>
  </div>

  <div class="form-group">
    <label>Кухня:</label>
    <select name="cuisine" required>
      <option value="">Выберите кухню</option>
      <option value="Итальянская">Итальянская</option>
      <option value="Японская">Японская</option>
      <option value="Казахская">Казахская</option>
      <option value="Французская">Французская</option>
    </select>
  </div>

  <h3>Пищевая ценность</h3>

  <div class="form-group">
    <input type="number" name="calories" placeholder="Калории (ККал)">
    <input type="number" step="0.1" name="proteins" placeholder="Белки (г)">
    <input type="number" step="0.1" name="fats" placeholder="Жиры (г)">
    <input type="number" step="0.1" name="carbs" placeholder="Углеводы (г)">
  </div>

  <div class="form-group">
    <label>Картинка (jpeg/png):</label>
    <input type="file" name="image" accept="image/*">
  </div>

  <div class="submit-container">
  <button type="submit" class="submit-button">Добавить</button>
</div>
</form>
</body>
</html>