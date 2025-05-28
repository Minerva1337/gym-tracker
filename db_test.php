<?php
$host = 'localhost';
$dbname = 'gym';
$user = 'lukas';
$pass = 'Mkpi44ja!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    echo "✅ Verbindung erfolgreich!";
} catch (PDOException $e) {
    echo "❌ Fehler: " . $e->getMessage();
}