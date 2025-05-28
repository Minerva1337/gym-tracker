<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');
$user_id = $_SESSION['user_id'];
$exercise_id = $_GET['exercise_id'] ?? null;
$range = $_GET['range'] ?? 'all';

if (!$exercise_id) {
    die("Keine Übung ausgewählt.");
}

$stmt = $pdo->prepare("SELECT name FROM exercises WHERE id = ? AND user_id = ?");
$stmt->execute([$exercise_id, $user_id]);
$exercise = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$exercise) {
    die("Übung nicht gefunden.");
}

$range_sql = '';
$params = [$user_id, $exercise_id];
if ($range === '30') {
    $range_sql = "AND ts.session_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
} elseif ($range === '90') {
    $range_sql = "AND ts.session_date >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)";
} elseif ($range === '365') {
    $range_sql = "AND ts.session_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
}

$query = "
    SELECT ts.session_date,
           SUM(te.reps * te.weight) AS volume,
           COUNT(*) AS sets,
           ts.id AS session_id
    FROM training_sessions ts
    JOIN training_entries te ON ts.id = te.session_id
    WHERE ts.user_id = ? AND te.exercise_id = ? $range_sql
    GROUP BY ts.id, ts.session_date
    ORDER BY ts.session_date ASC
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DEBUG: Zeige die Rohdaten
// echo "<pre>DEBUG DATA:\n";
// foreach ($rows as $row) {
//     echo "Session ID: {$row['session_id']}, Datum: {$row['session_date']}, Volume: {$row['volume']}, Sets: {$row['sets']}\n";
// }
// echo "</pre><hr>";

$labels = [];
$values = [];
foreach ($rows as $row) {
    // Nutze Session-ID + Datum als Label zur besseren Unterscheidung
    $labels[] = $row['session_id'] . ' - ' . date("d.m.Y", strtotime($row['session_date']));
    $index = $row['sets'] > 0 ? round($row['volume'] / $row['sets'], 2) : 0;
    $values[] = $index;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analyse: <?= htmlspecialchars($exercise['name']) ?></title>
  <link rel="stylesheet" href="/style/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <h1>📊 Analyse: <?= htmlspecialchars($exercise['name']) ?></h1>

  <div class="plan-card">
    <form class="range-selector" method="get">
      <input type="hidden" name="exercise_id" value="<?= $exercise_id ?>">
      <label for="range">Zeitraum:</label>
      <select name="range" id="range" onchange="this.form.submit()">
        <option value="all" <?= $range === 'all' ? 'selected' : '' ?>>Alle</option>
        <option value="30" <?= $range === '30' ? 'selected' : '' ?>>Letzte 30 Tage</option>
        <option value="90" <?= $range === '90' ? 'selected' : '' ?>>Letzte 90 Tage</option>
        <option value="365" <?= $range === '365' ? 'selected' : '' ?>>Letztes Jahr</option>
      </select>
    </form>

    <div style="background-color: #1f1f1f; border-radius: 10px; padding: 1rem;">
      <canvas id="performanceChart"></canvas>
    </div>
  </div>

  <div class="plan-card" style="padding: 1rem; margin-top: 2rem;">
    <a href="/analyze/index.php" class="fullwidth-button">Zurück zur Auswahl</a>
  </div>

  <script>
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
          label: 'Satz-Leistungsindex (kg·Wdh / Satz)',
          data: <?= json_encode($values) ?>,
          borderColor: '#bb87fd',
          backgroundColor: '#bb87fd',
          borderWidth: 2,
          tension: 0.3,
          fill: false
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Leistungsindex',
              color: '#e3e3e3'
            },
            ticks: {
              color: '#e3e3e3'
            },
            grid: {
              color: '#333'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Datum + Session-ID',
              color: '#e3e3e3'
            },
            ticks: {
              color: '#e3e3e3'
            },
            grid: {
              color: '#333'
            }
          }
        },
        plugins: {
          legend: {
            labels: {
              color: '#e3e3e3'
            }
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
