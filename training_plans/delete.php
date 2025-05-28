<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');

$plan_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if ($plan_id) {
    $stmt = $pdo->prepare("DELETE FROM training_plans WHERE id = ? AND user_id = ?");
    $stmt->execute([$plan_id, $user_id]);
}

header("Location: list.php");
exit();
