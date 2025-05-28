<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');
$user_id = $_SESSION['user_id'];
$week = $_GET['week'] ?? null;

if (!$week) {
    die("Keine Woche ausgewählt.");
}

$year = substr($week, 0, 4);
$weekNum = substr($week, 4);

// Montag der KW berechnen
$startDate = new DateTime();
$startDate->setISODate((int)$year, (int)$weekNum);
$dates = [];
for ($i = 0; $i < 7; $i++) {
    $day = clone $startDate;
    $day->modify("+{$i} days");
    $dates[$day->format('Y-m-d')] = $day->format('l, d.m.Y');
}

// Trainingssessions dieser Woche abrufen
$placeholders = implode(',', array_fill(0, count($dates), '?'));
$sql = "SELECT id, session_date FROM training_sessions WHERE user_id = ? AND session_date IN ($placeholders) ORDER BY session_date";
$stmt = $pdo->prepare($sql);
$stmt->execute(array_merge([$user_id], array_keys($dates)));
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sessionMap = [];
foreach ($sessions as $s) {
    $sessionMap[$s['session_date']] = $s['id'];
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Woche <?= htmlspecialchars($weekNum) ?>/<?= htmlspecialchars($year) ?></title>
  <link rel="stylesheet" href="/style/style.css">
</head>
<body>

  <h1>KW <?= htmlspecialchars($weekNum) ?>/<?= htmlspecialchars($year) ?></h1>

  <div class="plan-card">
  <?php foreach ($dates as $date => $label): ?>
    <div class="week-entry">
      <p class="week-label"><?= $label ?>:</p>
      <?php if (isset($sessionMap[$date])): ?>
        <a href="/sessions/view.php?id=<?= $sessionMap[$date] ?>" class="analyze-button">
          Training anzeigen
        </a>
      <?php else: ?>
        <span class="no-training">Kein Training</span>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>



  <div class="plan-card" style="padding: 1rem; margin-top: 2rem;">
    <a href="/analyze/frequency.php" class="fullwidth-button">Zurück zur Wochenübersicht</a>
  </div>

</body>
</html>

