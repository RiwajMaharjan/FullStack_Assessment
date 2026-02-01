<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header('Location: login.php');
    exit;
}

$members = $pdo->query("
    SELECT m.id, u.name 
    FROM members m 
    JOIN users u ON m.user_id = u.id
")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $goal      = $_POST['goal'];
    $level     = $_POST['level'];
    $days      = (int)$_POST['days'];
    $start     = $_POST['start_date'];
    $end       = $_POST['end_date'];

    $plan = "$goal Program ($level)\n\n";

    if ($days >= 3) {
        $plan .= "Day 1: Chest & Triceps\n";
        $plan .= "Day 2: Back & Biceps\n";
        $plan .= "Day 3: Legs & Core\n";
    }

    if ($days >= 4) {
        $plan .= "Day 4: Shoulders & Abs\n";
    }

    if ($days == 5) {
        $plan .= "Day 5: Cardio & Conditioning\n";
    }

    $stmt = $pdo->prepare("
        INSERT INTO workout_plans (member_id, plan_details, start_date, end_date)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$member_id, $plan, $start, $end]);

    header('Location: trainers_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Workout</title>
</head>
<body>

<form method="POST">
    <select name="member_id" required>
        <option value="">Select Member</option>
        <?php foreach ($members as $m): ?>
            <option value="<?= $m['id'] ?>">
                <?= htmlspecialchars($m['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="goal" required>
        <option value="">Goal</option>
        <option value="Weight Loss">Weight Loss</option>
        <option value="Muscle Gain">Muscle Gain</option>
        <option value="Endurance">Endurance</option>
    </select>

    <select name="level" required>
        <option value="">Level</option>
        <option value="Beginner">Beginner</option>
        <option value="Intermediate">Intermediate</option>
        <option value="Advanced">Advanced</option>
    </select>

    <select name="days" required>
        <option value="">Days / Week</option>
        <option value="3">3 Days</option>
        <option value="4">4 Days</option>
        <option value="5">5 Days</option>
    </select>

    <input type="date" name="start_date" required>
    <input type="date" name="end_date" required>

    <button type="submit">Add Workout</button>
</form>

</body>
</html>
