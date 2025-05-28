<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');
$user_id = $_SESSION['user_id'];

// Anzahl Trainingseinheiten pro Woche abrufen
$stmt = $pdo->prepare("SELECT YEARWEEK(session_date, 1) AS week, COUNT(*) AS count FROM training_sessions WHERE user_id = ? GROUP BY week ORDER BY week ASC");
$stmt->execute([$user_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$data = [];
$weeks = [];
foreach ($rows as $row) {
    $yearweek = $row['week'];
    $year = substr($yearweek, 0, 4);
    $week = substr($yearweek, 4);
    $label = "KW $week/$year";
    $labels[] = $label;
    $data[] = (int)$row['count'];
    $weeks[] = $yearweek;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trainingsfrequenz</title>
  <link rel="stylesheet" href="/style/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <h1>Trainingsfrequenz nach Woche</h1>

  <div class="plan-card">
    <div style="background-color: #1f1f1f; border-radius: 10px; padding: 1rem;">
      <canvas id="freqChart"></canvas>
    </div>
  </div>

  <div class="plan-card" style="padding: 1rem; margin-top: 2rem;">
    <a href="/analyze/index.php" class="fullwidth-button">Zurück zur Analyse-Übersicht</a>
  </div>

  <script>
    const labels = <?= json_encode($labels) ?>;
    const data = <?= json_encode($data) ?>;
    const weeks = <?= json_encode($weeks) ?>;

    const ctx = document.getElementById('freqChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Trainings pro Woche',
          data: data,
          borderColor: '#bb87fd',
          borderWidth: 2,
          tension: 0.3,
          fill: false
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        onClick: (e, elements) => {
          if (elements.length > 0) {
            const index = elements[0].index;
            const week = weeks[index];
            window.location.href = `/analyze/week_detail.php?week=${week}`;
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Einheiten',
              color: '#e3e3e3'
            },
            ticks: { color: '#e3e3e3' },
            grid: { color: '#333' }
          },
          x: {
            title: {
              display: true,
              text: 'Kalenderwoche',
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
