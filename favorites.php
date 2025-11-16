<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);

$sql = "SELECT r.*, u.username AS author_name, f.id AS fav_id
        FROM favorites f
        JOIN recipes r ON f.recipe_id = r.id
        LEFT JOIN users u ON r.author = u.id
        WHERE f.user_id = ?
        ORDER BY f.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$favs = [];
while ($row = $res->fetch_assoc()) $favs[] = $row;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<title>Избранные рецепты</title>
<style>
body { font-family:Arial,sans-serif; background:#f9f9f9; padding:30px; }
.container { max-width:1000px; margin:auto; }
h2 { margin-bottom:20px; }
.card { display:flex; gap:20px; background:white; padding:20px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); margin-bottom:20px; align-items:center; }
.card img { width:160px; height:120px; object-fit:cover; border-radius:8px; }
.card-content { flex:1; }
.card-content h3 { margin:0 0 10px; }
.card-content h3 a { text-decoration:none; color:#333; }
.card-content p { margin:4px 0; color:#555; }
.remove {
    background:rgb(230, 49, 29); 
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s ease;
}
.remove:hover {
    background: #a93226; 
}
.back-button {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: #5cb85c;
    color: white;
    padding: 12px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: background 0.2s ease;
    z-index: 1000;
}
.back-button:hover {
    background: #4cae4c;
}
</style>
</head>
<body>
<div class="container">
<h2>Мои избранные рецепты</h2>


<?php if (empty($favs)): ?>
    <p>У вас пока нет избранных рецептов.</p>
<?php else: ?>
    <?php foreach ($favs as $r): ?>
        <div class="card">
            <a href="view_recipe.php?id=<?= (int)$r['id'] ?>">
                <img src="<?= $r['image_path'] ? htmlspecialchars($r['image_path']) : 'https://source.unsplash.com/400x300/?food' ?>" alt="">
            </a>
            <div class="card-content">
                <h3>
                    <a href="view_recipe.php?id=<?= (int)$r['id'] ?>">
                        <?= htmlspecialchars($r['title']) ?>
                    </a>
                </h3>
                <p>⏱ <?= htmlspecialchars($r['cook_time']) ?> • 👨‍🍳 <?= htmlspecialchars($r['author_name'] ?? 'Неизвестен') ?></p>
                <p><?= nl2br(htmlspecialchars(mb_substr($r['description'], 0, 200))) ?>...</p>
            </div>
            <div>
                <button onclick="toggleFavorite(<?= (int)$r['id'] ?>, this)" class="remove">Удалить</button>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>

<script>
function toggleFavorite(recipeId, btn) {
    fetch("favorite_toggle.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "recipe_id=" + recipeId
    }).then(r => r.text()).then(t => {
        if (t.trim() === "REMOVED") {
            btn.closest('.card').remove();
        } else {
            alert("Ответ: " + t);
        }
    });
}
</script>
<a href="index.php" class="back-button">← Назад к рецептам</a>
</body>
</html>