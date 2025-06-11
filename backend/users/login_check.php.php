<?php
session_start();
$mysqli = new mysqli("localhost", "username", "password", "gym");

if ($mysqli->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $mysqli->connect_error);
}

// Überprüfen, ob der Benutzer bereits angemeldet ist
if (!isset($_SESSION['user_id'])) {
    // Wenn kein Benutzer angemeldet ist, prüfen, ob der "Remember Me"-Cookie vorhanden ist
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];

        // Überprüfen, ob das Token in der Datenbank existiert und nicht abgelaufen ist
        $stmt = $mysqli->prepare("SELECT user_id FROM remember_tokens WHERE token = ? AND expires_at > ?");
        $stmt->bind_param("si", $token, time());
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Token gefunden, Benutzer-ID abrufen
            $stmt->bind_result($userId);
            $stmt->fetch();

            // Benutzer in der Sitzung anmelden
            $_SESSION['user_id'] = $userId;

            echo "Automatisch eingeloggt!";
        } else {
            echo "Token ungültig oder abgelaufen!";
        }
        $stmt->close();
    }
} else {
    echo "Bereits eingeloggt!";
}

$mysqli->close();
?>