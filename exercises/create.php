<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

// Verbindung
$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $user_id = $_SESSION['user_id'];

    if ($name) {
        $stmt = $pdo->prepare("INSERT INTO exercises (user_id, name, category, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $category, $description]);
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
  <title>Übung erstellen</title>
  <link rel="stylesheet" href="/style/style.css">
</head>
<body>

  <h1>Neue Übung erstellen</h1>

  <div class="plan-card">
    <?php if (!empty($error)) : ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="form-group">
      <label for="name">Name der Übung:</label>
      <input type="text" name="name" id="name" required>

      <label for="category">Kategorie:</label>
      <input type="text" name="category" id="category" placeholder="Push, Pull, Core..." required>

      <label for="description">Beschreibung:</label>
      <textarea name="description" id="description" rows="4"></textarea>

      <input type="submit" value="Erstellen" class="btn-primary">
    </form>
  </div>

  <div class="plan-card" style="padding: 1rem; margin-top: 2rem;">
    <a href="list.php" class="fullwidth-button">Eigene Übungen anzeigen</a>
  </div>

</body>
</html>
