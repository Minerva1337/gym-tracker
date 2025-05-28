<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// DB-Verbindung aufbauen
$host = 'localhost';
$dbname = 'gym';
$user = 'lukas';
$pass = 'Mkpi44ja!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die("Verbindungsfehler: " . $e->getMessage());
}

// Formulardaten abholen
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Eingaben prüfen
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    header("Location: register.html?error=Bitte alle Felder ausfüllen.");
    exit();
}

if ($password !== $confirm_password) {
    header("Location: register.html?error=Passwörter stimmen nicht überein.");
    exit();
}

if (strlen($password) < 6) {
    header("Location: register.html?error=Passwort muss mindestens 6 Zeichen haben.");
    exit();
}

// Prüfen ob Benutzer oder E-Mail schon existieren
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
$stmt->execute(['username' => $username, 'email' => $email]);
if ($stmt->fetch()) {
    header("Location: register.html?error=Benutzername oder E-Mail bereits vergeben.");
    exit();
}

// Passwort hashen und speichern
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");
$stmt->execute([
    'username' => $username,
    'email' => $email,
    'password_hash' => $hash
]);

// Erfolg → Weiterleitung oder Login
header("Location: /login/login.html");
exit();
