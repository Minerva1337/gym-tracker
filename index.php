<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Startseite</title>
</head>
<body>
  <h1>Willkommen beim Fitness-Tracker</h1>

  <p><a href="register.html">Registrieren</a></p>
  <p><a href="login.html">Einloggen</a></p>
</body>
</html>
