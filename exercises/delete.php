<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

if (!isset($_GET['id'])) {
    die("Ungültiger Aufruf.");
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');

// Sicherheit: Nur eigene Übung darf gelöscht werden
$stmt = $pdo->prepare("DELETE FROM exercises WHERE id = ? AND user_id = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);

header("Location: list.php");
exit();
