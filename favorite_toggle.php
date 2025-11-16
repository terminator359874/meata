<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "NOT_LOGGED";
    exit;
}

$user_id = intval($_SESSION['user_id']);
$recipe_id = intval($_POST['recipe_id'] ?? 0);
if ($recipe_id <= 0) {
    echo "BAD_ID";
    exit;
}

// проверим наличие рецепта
$chkR = $conn->prepare("SELECT id FROM recipes WHERE id = ?");
$chkR->bind_param("i", $recipe_id);
$chkR->execute();
$rr = $chkR->get_result();
if ($rr->num_rows === 0) {
    echo "NO_RECIPE";
    exit;
}

// есть ли уже
$check = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND recipe_id = ?");
$check->bind_param("ii", $user_id, $recipe_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    $del = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND recipe_id = ?");
    $del->bind_param("ii", $user_id, $recipe_id);
    if ($del->execute()) echo "REMOVED"; else echo "ERR_DEL";
} else {
    $ins = $conn->prepare("INSERT INTO favorites (user_id, recipe_id) VALUES (?, ?)");
    $ins->bind_param("ii", $user_id, $recipe_id);
    if ($ins->execute()) echo "ADDED"; else echo "ERR_ADD";
}
