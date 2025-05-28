<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');
$user_id = $_SESSION['user_id'];

// Alle Sessions des Nutzers laden (neueste zuerst)
$stmt = $pdo->prepare("
    SELECT ts.id, ts.session_date, ts.notes, tp.name AS plan_name
    FROM training_sessions ts
    LEFT JOIN training_plans tp ON ts.plan_id = tp.id
    WHERE ts.user_id = ?
    ORDER BY ts.session_date DESC, ts.id DESC
");
$stmt->execute([$user_id]);
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trainingsverlauf</title>
  <link rel="stylesheet" href="/style/style.css">
</head>
<body>

  <h1>📈 Dein Trainingsverlauf</h1>

  <?php if (count($sessions) === 0): ?>
    <p class="no-exercises">Du hast bisher noch keine Trainings aufgezeichnet.</p>
  <?php endif; ?>

  <?php foreach ($sessions as $session): ?>
    <div class="plan-card">
      <h2><?= htmlspecialchars($session['session_date']) ?> – <?= htmlspecialchars($session['plan_name'] ?? 'Freies Training') ?></h2>

      <?php if ($session['notes']): ?>
        <p><strong>📝 Notizen:</strong> <?= nl2br(htmlspecialchars($session['notes'])) ?></p>
      <?php endif; ?>

      <?php
        $stmt = $pdo->prepare("
          SELECT e.name, e.category, te.sets, te.reps, te.weight
          FROM training_entries te
          JOIN exercises e ON te.exercise_id = e.id
          WHERE te.session_id = ?
          ORDER BY e.name, te.sets
        ");
        $stmt->execute([$session['id']]);
        $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?>

      <?php if ($entries): ?>
        <div class="exercise-table">
          <div class="row header">
            <div>Übung</div>
            <div>Kategorie</div>
            <div>Satz</div>
            <div>Wdh.</div>
            <div>kg</div>
          </div>

          <?php foreach ($entries as $entry): ?>
            <div class="row">
              <div><?= htmlspecialchars($entry['name']) ?></div>
              <div><?= htmlspecialchars($entry['category']) ?></div>
              <div><?= (int)$entry['sets'] ?></div>
              <div><?= (int)$entry['reps'] ?></div>
              <div><?= (float)$entry['weight'] ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="no-exercises"><em>Keine Einträge vorhanden.</em></p>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>

  <div class="plan-card" style="padding: 0; background: none; box-shadow: none; margin-top: 2rem;">
    <a href="/dashboard.php" class="btn-primary" style="display: block; text-align: center;">Zurück zum Dashboard</a>
  </div>

</body>
</html>
