<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $user_id = $_SESSION['user_id'];

    if ($name) {
        $stmt = $pdo->prepare("INSERT INTO training_plans (user_id, name) VALUES (?, ?)");
        $stmt->execute([$user_id, $name]);
        header("Location: list.php");
        exit();
    } else {
        $error = "Name darf nicht leer sein.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trainingsplan erstellen</title>
  <link rel="stylesheet" href="/style/style.css">
</head>
<body>

  <h1>Neuen Trainingsplan erstellen</h1>

  <div class="plan-card">
    <?php if (!empty($error)) : ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="form-group">
      <label for="name">Name des Plans:</label>
      <input type="text" name="name" id="name" required>
      <input type="submit" value="Erstellen" class="btn-primary">
    </form>
  </div>

  <div class="button-grid" style="margin-top: 2rem;">
    <a href="list.php">Pläne anzeigen</a>
    <a href="/dashboard.php">Zurück zum Dashboard</a>
  </div>

</body>
</html>
