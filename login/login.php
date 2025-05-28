<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// DB-Zugangsdaten
$host = 'localhost';
$dbname = 'gym';
$user = 'lukas';
$pass = 'Mkpi44ja!';

// Formularwerte
$email = trim($_POST['email']);
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    header("Location: login.html?error=Bitte alle Felder ausfüllen.");
    exit();
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        header("Location: login.html?error=E-Mail oder Passwort falsch.");
        exit();
    }

    // Login erfolgreich
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    header("Location: /dashboard.php");
    exit();

} catch (PDOException $e) {
    die("Datenbankfehler: " . $e->getMessage());
}
