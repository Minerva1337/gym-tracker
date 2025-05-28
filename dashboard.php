<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, name FROM exercises WHERE user_id = ? ORDER BY name ASC");
$stmt->execute([$user_id]);
$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/style/style.css" />
  <meta charset="UTF-8">
  <title>Dashboard</title>
</head>
<body>
  <h1>Willkommen, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
  
<div class="main-card">
  <!-- Training -->
  <section>
    <h2>Training</h2>
    <div class="button-grid">
      <a href="/training_sessions/start.php" class="dashboard-button">Training starten</a>
      <a href="/training_sessions/history.php" class="dashboard-button">Verlauf ansehen</a>
    </div>
  </section>

  <!-- Trainingspläne -->
  <section>
    <h2>Trainingspläne</h2>
    <div class="button-grid">
      <a href="/training_plans/create.php" class="dashboard-button">Neuen Trainingsplan erstellen</a>
      <a href="/training_plans/list.php" class="dashboard-button">Trainingspläne verwaltenn</a>
    </div>
  </section>

  <!-- Übungen -->
  <section>
    <h2>Übungen</h2>
    <div class="button-grid">
      <a href="/exercises/create.php" class="dashboard-button">Neue Übung erstellen</a>
      <a href="/exercises/list.php" class="dashboard-button">Eigene Übungen anzeigen</a>      
    </div>
  </section>


  <!-- Analyse -->
  <h2>Analyse</h2>
  <a href="/analyze/index.php" class="dashboard-button">Analyse einsehen</a>      

</div>


<br><br>
    <!-- Logout -->
    <li><a href="index.html">Logout</a></li>



</body>
</html>
