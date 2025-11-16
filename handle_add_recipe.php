<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$cook_time = trim($_POST['cook_time'] ?? '');
$ingredients = trim($_POST['ingredients'] ?? '');
$instructions = trim($_POST['instructions'] ?? '');
$type = trim($_POST['type'] ?? '');
$cuisine = trim($_POST['cuisine'] ?? '');
$author_id = intval($_SESSION['user_id']);

$calories = $_POST['calories'] !== '' ? intval($_POST['calories']) : null;
$proteins = $_POST['proteins'] !== '' ? floatval($_POST['proteins']) : null;
$fats     = $_POST['fats']     !== '' ? floatval($_POST['fats'])     : null;
$carbs    = $_POST['carbs']    !== '' ? floatval($_POST['carbs'])    : null;

$image_path = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $newName = uniqid() . '.' . $ext;
    $target = 'images/' . $newName;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $image_path = $target;
    }
}

$stmt = $conn->prepare("INSERT INTO recipes 
(title, description, cook_time, image_path, author, ingredients, instructions, calories, proteins, fats, carbs, type, cuisine) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssssissidddss", 
    $title, $description, $cook_time, $image_path, $author_id, 
    $ingredients, $instructions, $calories, $proteins, $fats, $carbs,
    $type, $cuisine);

if ($stmt->execute()) {
    header("Location: view_recipe.php?id=" . $stmt->insert_id);
} else {
    echo "Ошибка при добавлении рецепта: " . $conn->error;
}