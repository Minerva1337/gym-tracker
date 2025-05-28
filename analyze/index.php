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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analyse-Übersicht</title>
  <link rel="stylesheet" href="/style/style.css">
</head>
<body>

  <h1>Analyse-Übersicht</h1>

  <div class="plan-card">
  <h2>Leistungsentwicklung</h2>
  <div class="button-grid">
    <?php foreach ($exercises as $ex): ?>
      <a href="/analyze/analyze.php?exercise_id=<?= $ex['id'] ?>" class="analyze-button">
        Leistungsindex – <?= htmlspecialchars($ex['name']) ?>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<div class="plan-card">
  <h2>Durchschnitts Gewicht</h2>
  <div class="button-grid">
    <?php foreach ($exercises as $ex): ?>
      <a href="/analyze/average_weight.php?exercise_id=<?= $ex['id'] ?>" class="analyze-button">
        Ø Gewicht – <?= htmlspecialchars($ex['name']) ?>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<div class="plan-card">
  <h2>Trainingsfrequenz</h2>
  <a href="/analyze/frequency.php" class="analyze-button fullwidth-analyze">
    Häufigkeit nach Woche
  </a>
</div>


  <div class="plan-card">
    <h2>Weitere Analyse-Tools (geplant)</h2>
    <ul class="feature-list">
      <li>Vergleich zwischen zwei Übungen</li>
      <li>Trendverlauf (Gewicht / Wiederholungen)</li>
      <li>Beste Leistungen nach Übung</li>
      <li>Pausenverhalten (zukünftig)</li>
    </ul>
  </div>

  <div class="plan-card" style="padding: 1rem; margin-top: 2rem;">
    <a href="/dashboard.php" class="fullwidth-button">Zurück zum Dashboard</a>
  </div>

</body>
</html>
