<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Eingabe holen
$email = $_POST['email'] ?? '';
$pass = $_POST['password'] ?? '';

if (!$email || !$pass) {
  exit('❌ E-Mail und Passwort erforderlich.');
}

// 2. TypeScript-Skriptpfad holen
$scriptPath = realpath(__DIR__ . '/../backend/logic_layer/users/createUser.ts');
if (!$scriptPath) {
  exit("❌ TypeScript-Datei nicht gefunden.");
}

// 3. Shell-Kommando vorbereiten
$escapedEmail = escapeshellarg($email);
$escapedPass = escapeshellarg($pass);

// Absoluter Pfad zu npx + ts-node
$npxPath = '/var/www/vhosts/lukas-holzmann.de/.nodenv/shims/npx';

// Optional: Umgebungsvariable PATH setzen
putenv("PATH=" . getenv("PATH") . ":/var/www/vhosts/lukas-holzmann.de/.nodenv/shims");

$cmd = "$npxPath ts-node $scriptPath $escapedEmail $escapedPass";

// 4. Ausführen
$output = shell_exec($cmd);

// 5. Fehler prüfen
if ($output === null) {
  echo "❌ Fehler: shell_exec() hat nichts zurückgegeben.<br>";
  echo "<pre>Befehl:\n$cmd</pre>";
  exit;
}

// 6. JSON dekodieren
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