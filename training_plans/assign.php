<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');
$user_id = $_SESSION['user_id'];
$plan_id = $_GET['plan_id'] ?? null;

// Plan validieren
$stmt = $pdo->prepare("SELECT * FROM training_plans WHERE id = ? AND user_id = ?");
$stmt->execute([$plan_id, $user_id]);
$plan = $stmt->fetch();

if (!$plan) {
    die("Trainingsplan nicht gefunden oder kein Zugriff.");
}

// Übungen des Nutzers laden
$stmt = $pdo->prepare("SELECT * FROM exercises WHERE user_id = ?");
$stmt->execute([$user_id]);
$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

// POST: Zuordnung speichern
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected = $_POST['exercise'] ?? [];
    $order = $_POST['order'] ?? [];
    $sets = $_POST['sets'] ?? [];
    $reps = $_POST['reps'] ?? [];

    // Vorherige Zuordnungen löschen
    $pdo->prepare("DELETE FROM training_plan_exercises WHERE training_plan_id = ?")->execute([$plan_id]);

    // Neue Zuordnungen einfügen
    foreach ($selected as $exercise_id) {
        $stmt = $pdo->prepare("INSERT INTO training_plan_exercises (training_plan_id, exercise_id, exercise_order, target_sets, target_reps) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $plan_id,
            $exercise_id,
            $order[$exercise_id] ?? 1,
            $sets[$exercise_id] ?? 3,
            $reps[$exercise_id] ?? 10
        ]);
    }

    header("Location: list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Übungen zuweisen</title>
  <link rel="stylesheet" href="/style/style.css">
</head>
<body>

  <h1>Übungen zuweisen an: <?= htmlspecialchars($plan['name']) ?></h1>

  <div class="plan-card">
    <form method="POST" class="assign-form">
      <div class="exercise-table">
        <div class="row header">
          <div>✓</div>
          <div>Übung</div>
          <div>Kategorie</div>
          <div>#</div>
          <div>Sätze</div>
          <div>Wdh.</div>
        </div>

        <?php foreach ($exercises as $ex): ?>
          <div class="row">
            <div><input type="checkbox" name="exercise[]" value="<?= $ex['id'] ?>"></div>
            <div><?= htmlspecialchars($ex['name']) ?></div>
            <div><?= htmlspecialchars($ex['category']) ?></div>
            <div><input type="number" name="order[<?= $ex['id'] ?>]" value="1" min="1"></div>
            <div><input type="number" name="sets[<?= $ex['id'] ?>]" value="3" min="1"></div>
            <div><input type="number" name="reps[<?= $ex['id'] ?>]" value="10" min="1"></div>
          </div>
        <?php endforeach; ?>
      </div>

      <input type="submit" value="Speichern" class="btn-primary" style="margin-top: 1.5rem;">
    </form>
  </div>

  <a href="list.php" class="full-width-button">Zurück zu meinen Plänen</a>
  <!-- <a href="list.php">Zurück zu meinen Plänen</a> -->
  </div>

</body>
</html>

