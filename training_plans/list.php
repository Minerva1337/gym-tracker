<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');
$user_id = $_SESSION['user_id'];

// Alle Trainingspläne des Nutzers holen
$stmt = $pdo->prepare("SELECT * FROM training_plans WHERE user_id = ?");
$stmt->execute([$user_id]);
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/style/style.css" />  
  <meta charset="UTF-8">
  <title>Meine Trainingspläne</title>
</head>
<body>
<h1>Meine Trainingspläne</h1>

<?php foreach ($plans as $plan): ?>
  <div class="plan-card">
    <h2><?= htmlspecialchars($plan['name']) ?></h2>
    <div class="button-grid">
      <a href="assign.php?plan_id=<?= $plan['id'] ?>" class="btn-primary">Übungen zuordnen</a>
      <a href="delete.php?id=<?= $plan['id'] ?>" class="btn-primary" onclick="return confirm('Wirklich löschen?');">Löschen</a>
    </div>

    <?php
    $stmt = $pdo->prepare("
      SELECT e.name, e.category, tpe.exercise_order, tpe.target_sets, tpe.target_reps
      FROM training_plan_exercises tpe
      JOIN exercises e ON tpe.exercise_id = e.id
      WHERE tpe.training_plan_id = ?
      ORDER BY tpe.exercise_order ASC
    ");
    $stmt->execute([$plan['id']]);
    $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php if ($exercises): ?>
      <div class="exercise-table">
        <div class="row header">
          <div>#</div>
          <div>Übung</div>
          <div>Kategorie</div>
          <div>Sätze</div>
          <div>Wdh.</div>
        </div>
        <?php foreach ($exercises as $ex): ?>
          <div class="row">
            <div><?= (int)$ex['exercise_order'] ?></div>
            <div><?= htmlspecialchars($ex['name']) ?></div>
            <div><?= htmlspecialchars($ex['category']) ?></div>
            <div><?= (int)$ex['target_sets'] ?></div>
            <div><?= (int)$ex['target_reps'] ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="no-exercises">Noch keine Übungen zugewiesen.</p>
    <?php endif; ?>
  </div>
<?php endforeach; ?>

<div class="button-grid" style="margin-top: 2rem;">
  <a href="create.php" class="btn-primary">Neuen Plan erstellen</a>
  <a href="/dashboard.php" class="btn-primary">Zurück zum Dashboard</a>
</div>

</body>
</html>
