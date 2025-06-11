<?php
session_start();
$mysqli = new mysqli("localhost", "username", "password", "gym");

if ($mysqli->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $mysqli->connect_error);
}

// Überprüfen, ob der Benutzer eingeloggt ist
if (isset($_SESSION['user_id'])) {
    // Token aus der Datenbank löschen
    $stmt = $mysqli->prepare("DELETE FROM remember_tokens WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();

    // Cookie löschen
    setcookie("remember_token", "", time() - 3600, "/");  // Setze das Ablaufdatum auf die Vergangenheit

    // Sitzung beenden
    session_unset();
    session_destroy();

    echo "Abmeldung erfolgreich!";
} else {
    echo "Keine Sitzung aktiv!";
}

$mysqli->close();
?>