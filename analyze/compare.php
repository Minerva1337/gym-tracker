<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT id, name FROM exercises WHERE user_id = ? ORDER BY name ASC");
$stmt->execute([$user_id]);
$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

$selected_ids = $_GET['exercise_ids'] ?? [];
if (!is_array($selected_ids)) $selected_ids = [$selected_ids];

$data = [];
$labels = [];

if ($selected_ids) {
    foreach ($selected_ids as $eid) {
        $stmt = $pdo->prepare("SELECT name FROM exercises WHERE id = ? AND user_id = ?");
        $stmt->execute([$eid, $user_id]);
        $exercise = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$exercise) continue;

        $query = "
            SELECT ts.session_date, AVG(te.weight) AS avg_weight
            FROM training_sessions ts
            JOIN training_entries te ON ts.id = te.session_id
            WHERE ts.user_id = ? AND te.exercise_id = ?
            GROUP BY ts.session_date
            ORDER BY ts.session_date ASC
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id, $eid]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $dataset = [
            'label' => $exercise['name'],
            'data' => [],
            'borderColor' => 'hsl(' . rand(0, 360) . ', 70%, 50%)',
            'fill' => false,
            'tension' => 0.3
        ];

        foreach ($rows as $row) {
            $date = date("d.m.Y", strtotime($row['session_date']));
            $dataset['data'][] = ['x' => $date, 'y' => round((float)$row['avg_weight'], 2)];
            if (!in_array($date, $labels)) $labels[] = $date;
        }

        $data[] = $dataset;
    }
    sort($labels);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Übungen vergleichen</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: sans-serif;
            max-width: 900px;
            margin: 40px auto;
        }
        .form-section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<h2>Vergleich: Ø Gewicht mehrerer Übungen</h2>

<form method="get" class="form-section">
    <label for="exercise_ids">Übungen auswählen:</label><br>
    <select name="exercise_ids[]" id="exercise_ids" multiple size="5">
        <?php foreach ($exercises as $ex): ?>
            <option value="<?= $ex['id'] ?>" <?= in_array($ex['id'], $selected_ids) ? 'selected' : '' ?>>
                <?= htmlspecialchars($ex['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Vergleichen</button>
</form>

<canvas id="compareChart" height="100"></canvas>

<script>
const ctx = document.getElementById('compareChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        datasets: <?= json_encode($data) ?>
    },
    options: {
        parsing: false,
        scales: {
            x: {
                type: 'category',
                labels: <?= json_encode($labels) ?>,
                title: {
                    display: true,
                    text: 'Datum'
                }
            },
            y: {
                beginAtZero: false,
                title: {
                    display: true,
                    text: 'Ø Gewicht (kg)'
                }
            }
        },
        plugins: {
            tooltip: { mode: 'index', intersect: false },
            legend: { display: true }
        }
    }
});
</script>

<div class="back-button">
    <a href="/analyze/index.php" class="button">⬅️ Zurück zur Analyse-Übersicht</a>
</div>
</body>
</html>
