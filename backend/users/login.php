<?php
session_start();
$mysqli = new mysqli("localhost", "username", "password", "gym");

if ($mysqli->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']) ? true : false;

    // Überprüfen, ob der Benutzer existiert
    $stmt = $mysqli->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $passwordHash);
        $stmt->fetch();

        // Passwort überprüfen
        if (password_verify($password, $passwordHash)) {
            // Login erfolgreich, Sitzung starten
            $_SESSION['user_id'] = $userId;

            // "Remember Me" Funktionalität
            if ($rememberMe) {
                // Generiere ein zufälliges Token
                $token = bin2hex(random_bytes(32));  // Token erzeugen
                $expires = time() + (86400 * 30); // Token läuft nach 30 Tagen ab

                // Speichere Token in der Datenbank
                $stmt = $mysqli->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)
                                          ON DUPLICATE KEY UPDATE token = ?, expires_at = ?");
                $stmt->bind_param("issii", $userId, $token, $expires, $token, $expires);
                $stmt->execute();

                // Setze Cookie im Browser
                setcookie("remember_token", $token, $expires, "/", "", false, true); // Sicherer Cookie

                echo "Login erfolgreich und angemeldet bleiben!";
            } else {
                // Kein "Remember Me", standardmäßige Sitzung
                echo "Login erfolgreich!";
            }
        } else {
            echo "Ungültiges Passwort!";
        }
    } else {
        echo "Benutzername nicht gefunden!";
    }

    $stmt->close();
}

$mysqli->close();
?>