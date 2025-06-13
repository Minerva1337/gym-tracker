<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Eingabe holen
$email = $_POST['email'] ?? '';
$pass = $_POST['password'] ?? '';

if (!$email || !$pass) {
  exit('❌ E-Mail und Passwort erforderlich.');
}

// 2. TS-Skript vorbereiten
$scriptPath = realpath(__DIR__ . '/../backend/logic_layer/users/createUser.ts');
if (!$scriptPath) {
  exit("❌ TypeScript-Datei nicht gefunden.");
}

$escapedEmail = escapeshellarg($email);
$escapedPass = escapeshellarg($pass);
$cmd = "npx ts-node $scriptPath $escapedEmail $escapedPass";

// 3. Ausführen
$output = shell_exec($cmd);

// 4. Fehler prüfen
if ($output === null) {
  echo "❌ Fehler: shell_exec() hat nichts zurückgegeben.<br>";
  echo "<pre>Befehl:\n$cmd</pre>";
  exit;
}

// 5. Debug-Ausgabe (nur zur Entwicklung)
// echo "<pre>Antwort:\n$output</pre>";

// 6. Sicheres JSON-Dekodieren
$data = json_decode($output, true);

if (!is_array($data)) {
  echo "❌ Ungültige Antwort vom TypeScript-Skript.<br>";
  echo "<pre>Rohdaten:\n$output</pre>";
  exit;
}

// 7. Erfolg prüfen
if ($data['success'] === true) {
  echo "✅ Registrierung erfolgreich!";
} else {
  echo "❌ Fehler beim Registrieren.";
}