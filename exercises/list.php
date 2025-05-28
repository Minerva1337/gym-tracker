<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM exercises WHERE user_id = ?");
$stmt->execute([$user_id]);
$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meine Übungen</title>
  <link rel="stylesheet" href="/style/style.css">
</head>
<body>

  <h1>Meine Übungen</h1>

  <?php if (count($exercises) === 0): ?>
    <p class="no-exercises">Du hast noch keine Übungen erstellt.</p>
  <?php endif; ?>

  <?php foreach ($exercises as $ex): ?>
    <div class="plan-card">
      <h2><?= htmlspecialchars($ex['name']) ?></h2>
      <p><strong>Kategorie:</strong> <?= htmlspecialchars($ex['category']) ?></p>
      <?php if (!empty($ex['description'])): ?>
        <p><strong>Beschreibung:</strong><br><?= nl2br(htmlspecialchars($ex['description'])) ?></p>
      <?php endif; ?>
      <a href="delete.php?id=<?= $ex['id'] ?>" 
        onclick="return confirm('Wirklich löschen?');" 
        class="btn-secondary fullwidth-action">Löschen
      </a>
    </div>
  <?php endforeach; ?>

  <div class="button-grid" style="margin-top: 2rem;">
    <a href="create.php">Neue Übung erstellen</a>
    <a href="/dashboard.php">Zurück zum Dashboard</a>
  </div>

</body>
</html>

