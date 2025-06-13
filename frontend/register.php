<?php
$email = $_POST['email'] ?? '';
$pass = $_POST['password'] ?? '';

if (!$email || !$pass) {
  exit('❌ Email und Passwort erforderlich.');
}

$cmd = escapeshellcmd("npx ts-node ../logic_layer/createUser.ts $email $pass");
$output = shell_exec($cmd);
$data = json_decode($output, true);

if ($data['success']) {
  echo "✅ Registrierung erfolgreich!";
} else {
  echo "❌ Fehler beim Registrieren.";
}