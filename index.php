<?php
session_start();
require_once 'db.php';

// Получим избранные текущего пользователя
$favorites = [];
if (isset($_SESSION['user_id'])) {
    $uid = intval($_SESSION['user_id']);
    $favQ = $conn->prepare("SELECT recipe_id FROM favorites WHERE user_id = ?");
    $favQ->bind_param("i", $uid);
    $favQ->execute();
    $favRes = $favQ->get_result();
    while ($r = $favRes->fetch_assoc()) $favorites[] = (int)$r['recipe_id'];
}

// Получим параметры фильтра
$searchTerm = $_GET['q'] ?? '';
$type = $_GET['type'] ?? '';
$cuisine = $_GET['cuisine'] ?? '';

// Построим SQL с фильтрами
$sql = "SELECT r.*, u.username AS author FROM recipes r LEFT JOIN users u ON r.author = u.id WHERE 1=1";
$params = [];
$types = "";

if ($searchTerm !== '') {
    $sql .= " AND r.title LIKE ?";
    $params[] = '%' . $searchTerm . '%';
    $types .= "s";
}
if ($type !== '' && $type !== 'Все') {
    $sql .= " AND r.type = ?";
    $params[] = $type;
    $types .= "s";
}
if ($cuisine !== '' && $cuisine !== 'Все') {
    $sql .= " AND r.cuisine = ?";
    $params[] = $cuisine;
    $types .= "s";
}
$sql .= " ORDER BY r.created_at DESC";

// Выполним запрос
if ($types !== "") {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
} else {
    $res = $conn->query($sql);
}

// Соберём рецепты
$recipes = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $recipes[] = $row;
    }
} else {
    echo "Ошибка SQL: " . $conn->error;
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>MEALA - Рецепт</title>
<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background: #f9f9f9;
}
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 30px;
  background: white;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}
nav a {
  margin: 0 10px;
  text-decoration: none;
  color: #333;
  font-weight: bold;
  position: relative;
}
nav a.active {
  color: #28a745;
}
.container {
  display: flex;
  flex-wrap: wrap;
  padding: 20px 30px;
  gap: 20px;
}
.filter {
  width: 230px;
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
.recipes {
  flex: 1;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 20px;
}
.card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  position: relative;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}
.card img.recipe-img {
  width: 100%;
  height: 180px;
  object-fit: cover;
}
.card .info {
  padding: 15px;
}
.favorite {
  position: absolute;
  top: 10px;
  right: 10px;
  background: white;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  border: none;
}
.favorite svg {
  width: 20px;
  height: 20px;
}
.favorite.active svg {
  fill: #e0245e;
  animation: pop 0.3s ease;
}
@keyframes pop {
  0% { transform: scale(1); }
  50% { transform: scale(1.3); }
  100% { transform: scale(1); }
}
.add-recipe {
  margin-left: 10px;
  padding: 8px 12px;
  border-radius: 8px;
  background: #28a745;
  color: white;
  text-decoration: none;
}
form.search-filter input,
form.search-filter select {
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

/* === ВАЖНО: одинаковая ширина select === */
.filter form select {
  width: 100%;
  padding: 10px;
  margin-bottom: 12px;
  border-radius: 6px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

form.search-filter button {
  padding: 8px 12px;
  border: none;
  border-radius: 6px;
  background: #28a745;
  color: white;
}
.badge {
  display: inline-block;
  background: #eee;
  color: #555;
  font-size: 12px;
  padding: 4px 8px;
  border-radius: 6px;
  margin-right: 5px;
}
@media (max-width: 768px) {
  .container {
    flex-direction: column;
  }
  .filter {
    width: 100%;
  }
  .recipes {
    grid-template-columns: 1fr;
  }
}
.search-bar {
  margin: 20px 30px;
  display: flex;
  gap: 10px;
}

.search-bar input {
  flex: 1;
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

.search-bar button {
  padding: 10px 16px;
  border: none;
  border-radius: 6px;
  background: #28a745;
  color: white;
  cursor: pointer;
}

</style>
</head>
<body>
<header>
    <h1>MEALA</h1>
    <nav>
        <a href="index.php">Рецепты</a>
        <a href="#">Кухни</a>
        <a href="#">Авторы</a>
        <a href="favorites.php">Избранное</a>
    </nav>
    <div>
      <?php if (isset($_SESSION['username'])): ?>
        <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
        <a href="add_recipe.php" class="add-recipe">Добавить рецепт</a>
        <a href="logout.php" style="margin-left:10px;color:#fff;background:rgb(230, 49, 29);padding:8px 12px;border-radius:8px;text-decoration:none;">Выйти</a>
      <?php else: ?>
        <a href="login.php" class="add-recipe" style="background:#007bff;">Войти</a>
      <?php endif; ?>
    </div>
</header>

<form method="GET" class="search-bar">
  <input type="text" name="q" placeholder="Поиск рецепта..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
  <button type="submit">Найти</button>
</form>


<div class="container">
    <aside class="filter">
    <form method="GET">

  <select name="type">
    <option value="">Все</option>
    <option value="Завтрак" <?= ($_GET['type'] ?? '') === 'Завтрак' ? 'selected' : '' ?>>Завтрак</option>
    <option value="Обед" <?= ($_GET['type'] ?? '') === 'Обед' ? 'selected' : '' ?>>Обед</option>
    <option value="Ужин" <?= ($_GET['type'] ?? '') === 'Ужин' ? 'selected' : '' ?>>Ужин</option>
  </select>

  <select name="cuisine">
    <option value="">Все</option>
    <option value="Японская" <?= ($_GET['cuisine'] ?? '') === 'Японская' ? 'selected' : '' ?>>Японская</option>
    <option value="Итальянская" <?= ($_GET['cuisine'] ?? '') === 'Итальянская' ? 'selected' : '' ?>>Итальянская</option>
    <option value="Казахская" <?= ($_GET['cuisine'] ?? '') === 'Казахская' ? 'selected' : '' ?>>Казахская</option>
  </select>

  <button type="submit" style="padding:8px 12px;border:none;border-radius:6px;background:#28a745;color:white;">Найти</button>

</form>
    </aside>

    <section class="recipes">
        <?php foreach ($recipes as $rec): 
            $id = (int)$rec['id'];
            $isFav = in_array($id, $favorites);
            $img = $rec['image_path'] ? htmlspecialchars($rec['image_path']) : 'https://source.unsplash.com/400x300/?food';
        ?>
        <div class="card">
            <button class="favorite <?= $isFav ? 'active' : '' ?>" onclick="toggleFavorite(<?= $id ?>, this)" title="Добавить в избранное">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#888">
                    <path d="M12 21s-7-4.35-9-6.5S1 10.5 5 7.5 12 9 12 9s3.5-3.5 7-2.5 3 4.5 2 6.5-9 8-9 8z" />
                </svg>
            </button>

            <img class="recipe-img" src="<?= $img ?>" alt="<?= htmlspecialchars($rec['title']) ?>">
            <div class="info">
                <h3><?= htmlspecialchars($rec['title']) ?></h3>
                <p>⏱ <?= htmlspecialchars($rec['cook_time']) ?></p>
                <p>👨‍🍳 <?= htmlspecialchars($rec['author'] ?? 'Неизвестен') ?></p>
                <a class="btn-view" href="view_recipe.php?id=<?= $id ?>" style="display:inline-block;margin-top:8px;padding:8px 12px;background:#28a745;color:#fff;border-radius:6px;text-decoration:none;">Смотреть рецепт</a>
            </div>
        </div>
        <?php endforeach; ?>
    </section>
</div>

<script>
function toggleFavorite(recipeId, btn) {
    fetch("favorite_toggle.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "recipe_id=" + recipeId
    })
    .then(r => r.text())
    .then(text => {
        if (text.trim() === "ADDED") {
            btn.classList.add('active');
        } else if (text.trim() === "REMOVED") {
            btn.classList.remove('active');
        } else if (text.trim() === "NOT_LOGGED") {
            alert("Войдите, чтобы использовать избранное.");
        }
    })
    .catch(err => console.error(err));
}
</script>

</body>
</html>
