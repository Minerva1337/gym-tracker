<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM training_plans WHERE user_id = ?");
$stmt->execute([$user_id]);
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Training starten</title>
  <link rel="stylesheet" href="/style/style.css">
</head>
<body>

  <h1>Training starten</h1>

  <div class="plan-card">
    <form method="GET" action="session.php" class="form-group">
      <label for="plan_id">Trainingsplan wählen:</label>
      <select name="plan_id" id="plan_id" required>
        <option value="">-- Bitte wählen --</option>
        <?php foreach ($plans as $plan): ?>
          <option value="<?= $plan['id'] ?>"><?= htmlspecialchars($plan['name']) ?></option>
        <?php endforeach; ?>
      </select>

      <input type="submit" value="Training starten" class="btn-primary">
    </form>
  </div>

  <div class="plan-card" style="max-width: 400px; margin: 2rem auto; text-align: center;">
    <a href="/dashboard.php" class="fullwidth-button">Zurück zum Dashboard</a>
  </div>


</body>
</html>


