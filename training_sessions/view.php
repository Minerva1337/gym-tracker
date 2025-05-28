<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');
$user_id = $_SESSION['user_id'];
$session_id = $_GET['session_id'] ?? $_GET['id'] ?? null;

if (!$session_id || !is_numeric($session_id)) {
    die("Keine gültige Session-ID angegeben.");
}

// Sessiondaten abrufen
$stmt = $pdo->prepare("SELECT ts.session_date, tp.name AS plan_name FROM training_sessions ts LEFT JOIN training_plans tp ON ts.plan_id = tp.id WHERE ts.id = ? AND ts.user_id = ?");
$stmt->execute([$session_id, $user_id]);
$session = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$session) {
    die("Session nicht gefunden oder gehört nicht dir.");
}

// Trainingssätze abrufen
$stmt = $pdo->prepare("SELECT e.name AS exercise_name, te.sets, te.reps, te.weight, te.notes FROM training_entries te JOIN exercises e ON te.exercise_id = e.id WHERE te.session_id = ? ORDER BY e.name, te.sets ASC");
$stmt->execute([$session_id]);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Strukturieren nach Übung
$grouped = [];
foreach ($entries as $entry) {
    $grouped[$entry['exercise_name']][] = $entry;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Training vom <?= htmlspecialchars($session['session_date']) ?></title>
  <style>
    body { font-family: sans-serif; max-width: 800px; margin: 40px auto; }
    h2, h3 { margin-top: 24px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    .back-link { margin-top: 20px; display: inline-block; }
  </style>
</head>
<body>
  <h2>Training vom <?= date("d.m.Y", strtotime($session['session_date'])) ?> (Plan: <?= htmlspecialchars($session['plan_name'] ?? 'Unbekannt') ?>)</h2>

  <?php foreach ($grouped as $exercise_name => $sätze): ?>
    <h3>Übung: <?= htmlspecialchars($exercise_name) ?></h3>
    <table>
      <tr>
        <th>Satz</th>
        <th>Wdh.</th>
        <th>Gewicht (kg)</th>
        <th>Notiz</th>
      </tr>
      <?php foreach ($sätze as $satz): ?>
        <tr>
          <td><?= $satz['sets'] ?></td>
          <td><?= $satz['reps'] ?></td>
          <td><?= $satz['weight'] ?></td>
          <td><?= htmlspecialchars($satz['notes']) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endforeach; ?>

  <a class="back-link" href="/analyze/frequency.php">⬅️ Zurück zur Häufigkeit</a>
</body>
</html>
