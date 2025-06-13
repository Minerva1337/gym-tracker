<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Nutzereingaben aus POST
$email = $_POST['email'] ?? '';
$pass = $_POST['password'] ?? '';

// Eingabe validieren
if (!$email || !$pass) {
  exit('❌ E-Mail und Passwort erforderlich.');
}

// TypeScript-Skriptpfad (anpassen bei Bedarf!)
$scriptPath = realpath(__DIR__ . '/../backend/logic_layer/users/createUser.ts');

// Sicherheit: Shell-Parameter escapen
$escapedEmail = escapeshellarg($email);
$escapedPass = escapeshellarg($pass);

// Kommando vorbereiten
$cmd = "npx ts-node $scriptPath $escapedEmail $escapedPass";

// Shell ausführen
$output = shell_exec($cmd);

// Debug-Ausgabe (nur zum Testen)
// echo "<pre>Shell-Befehl:\n$cmd\n</pre>";
// echo "<pre>Antwort:\n$output\n</pre>";

// JSON dekodieren
$data = json_decode($output, true);

// Erfolgreich?
if (is_array($data) && isset($data['success']) && $data['success'] === true) {
  echo "✅ Registrierung erfolgreich!";
} else {
  echo "❌ Fehler beim Registrieren.";
}