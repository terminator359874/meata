<?php
session_start();
require_once 'db.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "Рецепт не найден.";
    exit;
}

$stmt = $conn->prepare("SELECT r.*, u.username AS author_name FROM recipes r LEFT JOIN users u ON r.author = u.id WHERE r.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$recipe = $res->fetch_assoc();
if (!$recipe) {
    echo "Рецепт не найден.";
    exit;
}

$isFav = false;
if (isset($_SESSION['user_id'])) {
    $check = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND recipe_id = ?");
    $check->bind_param("ii", $_SESSION['user_id'], $id);
    $check->execute();
    $chkRes = $check->get_result();
    $isFav = $chkRes->num_rows > 0;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($recipe['title']) ?></title>
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f4f4;
}
.header-block {
    max-width: 1200px;
    margin: auto;
    padding: 30px 20px;
}
.title {
    font-size: 32px;
    font-weight: 700;
}
.meta-line {
    margin-top: 10px;
    color: #555;
}
.top-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
}
.fav-btn {
    background: #5cb85c;
    color: white;
    padding: 10px 14px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
}
.main-img {
    width: 100%;
    max-height: 450px;
    object-fit: cover;
    border-radius: 12px;
    margin: 20px 0;
}
.wrapper {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
    display: flex;
    gap: 20px;
}
.block {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    flex: 1;
}
.subtitle {
    font-size: 22px;
    margin-bottom: 10px;
}
.nutrition-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 15px;
}
.nutri-item {
    flex: 1 1 80px;
    text-align: center;
    padding: 12px;
    border-radius: 10px;
    color: #333;
    font-weight: bold;
}
.nutri-calories { background: #f4a261; }  
.nutri-proteins { background: #80cfa9; } 
.nutri-fats     { background: #f9e79f; }  
.nutri-carbs    { background: #a0c4ff; } 
.nutri-number {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 4px;
}
</style>
</head>
<body>
<div class="header-block">
    <div class="title"><?= htmlspecialchars($recipe['title']) ?></div>
    <div class="meta-line">
        Добавлено: <?= date('d.m.Y', strtotime($recipe['created_at'])) ?> • 
        Время приготовления: <?= htmlspecialchars($recipe['cook_time']) ?>
    </div>
    <div class="meta-line">
        <strong>Тип:</strong> <?= htmlspecialchars($recipe['type'] ?? '—') ?> • 
        <strong>Кухня:</strong> <?= htmlspecialchars($recipe['cuisine'] ?? '—') ?>
    </div>

    <div class="top-actions">
        <div>Автор: <?= htmlspecialchars($recipe['author_name'] ?? 'Неизвестен') ?></div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <button class="fav-btn" onclick="toggleFav(<?= $id ?>, this)">
                <?= $isFav ? 'В избранном' : 'В избранное' ?>
            </button>
        <?php endif; ?>
    </div>

    <?php if ($recipe['image_path']): ?>
        <img class="main-img" src="<?= htmlspecialchars($recipe['image_path']) ?>" alt="Фото рецепта">
    <?php endif; ?>
</div>

<div class="wrapper">
    <div class="block">
        <div class="subtitle">Описание</div>
        <p><?= nl2br(htmlspecialchars($recipe['description'])) ?></p>

        <div class="subtitle">Ингредиенты</div>
        <p><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>

        <div class="subtitle">Инструкция</div>
        <p><?= nl2br(htmlspecialchars($recipe['instructions'])) ?></p>
    </div>

    <div class="block" style="max-width: 300px;">
        <div class="subtitle">Пищевая ценность</div>
        <div class="nutrition-grid">
            <div class="nutri-item nutri-calories">
                <div class="nutri-number"><?= (int)$recipe['calories'] ?></div>
                ККал
            </div>
            <div class="nutri-item nutri-proteins">
                <div class="nutri-number"><?= (float)$recipe['proteins'] ?></div>
                Белки
            </div>
            <div class="nutri-item nutri-fats">
                <div class="nutri-number"><?= (float)$recipe['fats'] ?></div>
                Жиры
            </div>
            <div class="nutri-item nutri-carbs">
                <div class="nutri-number"><?= (float)$recipe['carbs'] ?></div>
                Углеводы
            </div>
        </div>
    </div>
</div>

<script>
function toggleFav(id, btn) {
    fetch("favorite_toggle.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "recipe_id=" + id
    }).then(r => r.text()).then(t => {
        if (t.trim() === "ADDED") btn.textContent = 'В избранном';
        else if (t.trim() === "REMOVED") btn.textContent = 'В избранное';
    });
}
</script>
</body>
</html>