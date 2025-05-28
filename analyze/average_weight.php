<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');
$user_id = $_SESSION['user_id'];
$exercise_id = $_GET['exercise_id'] ?? null;

$stmt = $pdo->prepare("SELECT id, name FROM exercises WHERE user_id = ? ORDER BY name ASC");
$stmt->execute([$user_id]);
$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$exercise_id) {
    $exercise_id = $exercises[0]['id'] ?? null;
}

if (!$exercise_id) {
    die("Keine Übung verfügbar.");
}

$stmt = $pdo->prepare("SELECT name FROM exercises WHERE id = ? AND user_id = ?");
$stmt->execute([$exercise_id, $user_id]);
$exercise = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$exercise) {
    die("Übung nicht gefunden.");
}

$query = "
    SELECT ts.session_date,
           AVG(te.weight) AS avg_weight
    FROM training_sessions ts
    JOIN training_entries te ON ts.id = te.session_id
    WHERE ts.user_id = ? AND te.exercise_id = ?
    GROUP BY ts.session_date
    ORDER BY ts.session_date ASC
";

$stmt = $pdo->prepare($query);
$stmt->execute([$user_id, $exercise_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$values = [];
foreach ($rows as $row) {
    $labels[] = date("d.m.Y", strtotime($row['session_date']));
    $values[] = round((float)$row['avg_weight'], 2);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ø Gewicht: <?= htmlspecialchars($exercise['name']) ?></title>
  <link rel="stylesheet" href="/style/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <h1>Durchschnitts Gewicht pro Trainingseinheit</h1>

  <div class="plan-card">
    <form method="get" class="exercise-selector">
      <label for="exercise_id">Übung auswählen:</label>
      <select name="exercise_id" id="exercise_id" onchange="this.form.submit()">
        <?php foreach ($exercises as $ex): ?>
          <option value="<?= $ex['id'] ?>" <?= $ex['id'] == $exercise_id ? 'selected' : '' ?>>
            <?= htmlspecialchars($ex['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </form>

    <div style="background-color: #1f1f1f; border-radius: 10px; padding: 1rem;">
      <canvas id="weightChart"></canvas>
    </div>
  </div>

  <div class="plan-card" style="padding: 1rem; margin-top: 2rem;">
    <a href="/analyze/index.php" class="fullwidth-button">Zurück zur Analyse-Übersicht</a>
  </div>

  <script>
    const labels = <?= json_encode($labels) ?>;
    const values = <?= json_encode($values) ?>;
    const minValue = Math.min(...values);
    const maxValue = Math.max(...values);

    const ctx = document.getElementById('weightChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Ø Gewicht (kg)',
          data: values,
          borderColor: '#bb87fd',
          backgroundColor: 'rgba(187, 135, 253, 0.2)',
          tension: 0.3,
          fill: false,
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: false,
            suggestedMin: minValue - 2,
            suggestedMax: maxValue + 2,
            title: {
              display: true,
              text: 'Gewicht (kg)',
              color: '#e3e3e3'
            },
            ticks: { color: '#e3e3e3' },
            grid: { color: '#333' }
          },
          x: {
            title: {
              display: true,
              text: 'Datum',
              color: '#e3e3e3'
            },
            ticks: { color: '#e3e3e3' },
            grid: { color: '#333' }
          }
        },
        plugins: {
          legend: {
            labels: { color: '#e3e3e3' }
          },
          tooltip: {
            mode: 'index',
            intersect: false
          }
        }
      }
    });
  </script>

</body>
</html>
