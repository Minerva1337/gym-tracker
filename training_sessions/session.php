<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.html");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');

$user_id = $_SESSION['user_id'];
$plan_id = $_GET['plan_id'] ?? null;

if (!$plan_id) {
    die("Kein Trainingsplan ausgewählt.");
}

// Plan & Übungen laden
$stmt = $pdo->prepare("
    SELECT tp.name AS plan_name, e.id AS exercise_id, e.name, e.category, tpe.target_sets, tpe.target_reps
    FROM training_plan_exercises tpe
    JOIN exercises e ON tpe.exercise_id = e.id
    JOIN training_plans tp ON tpe.training_plan_id = tp.id
    WHERE tpe.training_plan_id = ?
    ORDER BY tpe.exercise_order
");
$stmt->execute([$plan_id]);
$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($exercises)) {
    die("Keine Übungen gefunden.");
}

$plan_name = $exercises[0]['plan_name'];

// Letzte Trainingseinträge je Übung laden
$last_values = [];
$last_dates = [];

foreach ($exercises as $ex) {
    $eid = $ex['exercise_id'];

    $stmt = $pdo->prepare("
        SELECT ts.id AS session_id, ts.session_date
        FROM training_sessions ts
        WHERE ts.user_id = ? AND ts.plan_id = ? AND EXISTS (
            SELECT 1 FROM training_entries te
            WHERE te.session_id = ts.id AND te.exercise_id = ?
        )
        ORDER BY ts.session_date DESC, ts.id DESC
        LIMIT 1
    ");
    $stmt->execute([$user_id, $plan_id, $eid]);
    $last_session = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($last_session) {
        $last_dates[$eid] = $last_session['session_date'];
        $stmt = $pdo->prepare("
            SELECT te.reps, te.weight, te.notes, ts.session_date
            FROM training_entries te
            JOIN training_sessions ts ON te.session_id = ts.id
            WHERE te.session_id = ? AND te.exercise_id = ?
            ORDER BY te.sets ASC
        ");
        $stmt->execute([$last_session['session_id'], $eid]);
        $last_values[$eid] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $last_dates[$eid] = null;
        $last_values[$eid] = [];
    }
}

// Letztes Datum für diesen Plan ermitteln
$stmt = $pdo->prepare("SELECT MAX(session_date) AS last_plan_date FROM training_sessions WHERE user_id = ? AND plan_id = ?");
$stmt->execute([$user_id, $plan_id]);
$last_plan_date = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($plan_name) ?></title>
  <link rel="stylesheet" href="/style/style.css">
</head>
<body>

  <h1><?= htmlspecialchars($plan_name) ?></h1>

    <?php if ($last_plan_date): ?>
      <p class="last-value">
        Letzte Trainingseinheit: <?= date("d.m.Y", strtotime($last_plan_date)) ?>
      </p>
    <?php endif; ?>

  <form id="trainingForm">
    <input type="hidden" name="plan_id" value="<?= $plan_id ?>">

    <?php foreach ($exercises as $ex):
      $eid = $ex['exercise_id'];
      $last_sätze = $last_values[$eid];
    ?>
      <div class="plan-card exercise" data-id="<?= $eid ?>">
        <h2><?= htmlspecialchars($ex['name']) ?> <span class="subtext">(<?= htmlspecialchars($ex['category']) ?>)</span></h2>
        <div class="satz-liste" id="sätze-<?= $eid ?>">
          <?php
          $anzahl_sätze = max($ex['target_sets'], count($last_sätze));
          for ($i = 1; $i <= $anzahl_sätze; $i++):
            $last_satz = $last_sätze[$i - 1] ?? ['reps' => 0, 'weight' => 0];
            $last_note = $last_satz['notes'] ?? '';
                    ?>
          <div class="satz">
            <p class="satz-label">Satz <?= $i ?></p>
            <div class="satz-inputs">
              <input type="number" name="weight[<?= $eid ?>][]" placeholder="<?= $last_satz['weight'] ?> kg" min="0" step="0.5">
              <input type="number" name="reps[<?= $eid ?>][]" placeholder="<?= $last_satz['reps'] ?> Wdh." min="0">
              <input type="text" name="notes[<?= $eid ?>][]" placeholder="<?= htmlspecialchars($last_note) ?>">
            </div>
          </div>
          <?php endfor; ?>
        </div>
        <?php
          $defaultReps = $last_sätze[0]['reps'] ?? 0;
          $defaultWeight = $last_sätze[0]['weight'] ?? 0;
        ?>
      <div class="button-grid">
      <button type="button" onclick="addSatz(<?= $eid ?>, <?= $defaultReps ?>, <?= $defaultWeight ?>)" class="btn-primary">+ Satz</button>
      <button type="button" onclick="removeSatz(<?= $eid ?>)" class="btn-primary">− Satz</button>

      </div>
      </div>
    <?php endforeach; ?>

    <div class="button-grid" style="margin-top: 2rem;">
      <button type="submit" class="btn-primary">Training speichern</button>
      <a href="/dashboard.php">Training abbrechen</a>
    </div>
  </form>

  <p id="status" style="color: #6f6;"></p>

  <script>
    function addSatz(eid, lastReps = 0, lastWeight = 0) {
      const container = document.getElementById("sätze-" + eid);
      const satzCount = container.querySelectorAll('.satz').length + 1;
      const div = document.createElement('div');
      div.classList.add('satz');
      div.innerHTML = `
        <p class="satz-label">Satz ${satzCount}</p>
        <div class="satz-inputs">
          <input type="number" name="weight[${eid}][]" placeholder="${lastWeight || ''} kg" min="0" step="0.5">
          <input type="number" name="reps[${eid}][]" placeholder="${lastReps} Wdh." min="0">
          <input type="text" name="notes[${eid}][]" placeholder="Notiz">
        </div>
      `;

      container.appendChild(div);
    }

    function removeSatz(eid) {
      const container = document.getElementById("sätze-" + eid);
      const sätze = container.querySelectorAll('.satz');
      if (sätze.length > 1) {
        container.removeChild(sätze[sätze.length - 1]);
      }
    }

    document.getElementById('trainingForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);
      const jsonData = {};
      for (const [key, value] of formData.entries()) {
        if (key.includes("[")) {
          const match = key.match(/^(\w+)\[(\d+)\]\[\]$/);
          if (match) {
            const field = match[1];
            const id = match[2];
            if (!jsonData[field]) jsonData[field] = {};
            if (!jsonData[field][id]) jsonData[field][id] = [];
            jsonData[field][id].push(value);
          }
        } else {
          jsonData[key] = value;
        }
      }

      fetch('save.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(jsonData)
      })
      .then(res => res.text())
      .then(txt => document.getElementById('status').textContent = txt);
    });
  </script>
</body>
</html>

