<?php
session_start();
header('Content-Type: text/plain');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit("Nicht eingeloggt.");
}

// Verbindung
$pdo = new PDO("mysql:host=localhost;dbname=gym;charset=utf8mb4", 'lukas', 'Mkpi44ja!');

// JSON-Daten einlesen
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    http_response_code(400);
    exit("Ungültige Daten.");
}

$user_id = $_SESSION['user_id'];
$plan_id = $data['plan_id'] ?? null;
$session_date = date('Y-m-d');

// 1️⃣ Neue Trainingseinheit anlegen
$stmt = $pdo->prepare("INSERT INTO training_sessions (user_id, plan_id, session_date) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $plan_id, $session_date]);
$session_id = $pdo->lastInsertId();

// 2️⃣ Trainingseinträge speichern
$reps_data = $data['reps'] ?? [];
$weight_data = $data['weight'] ?? [];
$notes_data = $data['notes'] ?? [];

foreach ($reps_data as $exercise_id => $reps_list) {
    $weights_list = $weight_data[$exercise_id] ?? [];

    for ($i = 0; $i < count($reps_list); $i++) {
        $reps = (int) $reps_list[$i];
        $weight = isset($weights_list[$i]) ? (float) $weights_list[$i] : 0;
        $notes = $notes_data[$exercise_id][$i] ?? '';
    
        $stmt = $pdo->prepare("INSERT INTO training_entries (session_id, exercise_id, sets, reps, weight, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$session_id, $exercise_id, $i + 1, $reps, $weight, $notes]);
    }
}

echo "✅ Trainingseinheit gespeichert!";
