<?php
$email = $_POST['email'] ?? '';
$pass = $_POST['password'] ?? '';

if (!$email || !$pass) {
  exit('❌ Email und Passwort erforderlich.');
}

$cmd = escapeshellcmd("npx ts-node ../logic_layer/authUser.ts $email $pass");
$output = shell_exec($cmd);
$data = json_decode($output, true);

if ($data['success']) {
  echo "✅ Login erfolgreich! User ID: " . $data['user_id'];
  // z. B. $_SESSION['user_id'] = $data['user_id'];
} else {
  echo "❌ Login fehlgeschlagen.";
}