<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Eingaben holen
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$pass = $_POST['password'] ?? '';

if (!$username || !$email || !$pass) {
  exit('❌ Benutzername, E-Mail und Passwort erforderlich.');
}

// 2. TypeScript-Skriptpfad holen
$scriptPath = realpath(__DIR__ . '/../backend/logic_layer/users/registerUser.ts');
if (!$scriptPath) {
  exit("❌ TypeScript-Datei nicht gefunden.");
}

// 3. Shell-Befehl vorbereiten
$escapedUsername = escapeshellarg($username);
$escapedEmail = escapeshellarg($email);
$escapedPass = escapeshellarg($pass);

$npxPath = '/var/www/vhosts/lukas-holzmann.de/.nodenv/shims/npx';
putenv("PATH=" . getenv("PATH") . ":/var/www/vhosts/lukas-holzmann.de/.nodenv/shims");

$cmd = "$npxPath ts-node $scriptPath $escapedUsername $escapedEmail $escapedPass";

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

// 7. Erfolg oder Fehlermeldung anzeigen
if ($data['success'] === true) {
  echo "✅ Registrierung erfolgreich!";
} else {
  $reason = $data['reason'] ?? 'unbekannt';
  if ($reason === 'duplicate_email') {
    echo "❌ Diese E-Mail ist bereits registriert.";
  } elseif ($reason === 'insert_failed') {
    $msg = $data['message'] ?? '';
    echo "❌ Fehler beim Speichern.<br><pre>$msg</pre>";
  } else {
    echo "❌ Registrierung fehlgeschlagen.";
  }
}