<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM workout_plans WHERE id = ?");
$stmt->execute([$id]);
$workout = $stmt->fetch();

if (!$workout) {
    die('Workout not found');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $details = $_POST['plan_details'];
    $start   = $_POST['start_date'];
    $end     = $_POST['end_date'];

    $stmt = $pdo->prepare("
        UPDATE workout_plans 
        SET plan_details = ?, start_date = ?, end_date = ?
        WHERE id = ?
    ");
    $stmt->execute([$details, $start, $end, $id]);

    header('Location: trainers_dashboard.php');
    exit;
}
?>

<form method="POST">
    <textarea name="plan_details" required><?= htmlspecialchars($workout['plan_details']) ?></textarea>
    <input type="date" name="start_date" value="<?= $workout['start_date'] ?>" required>
    <input type="date" name="end_date" value="<?= $workout['end_date'] ?>" required>
    <button type="submit">Update Workout</button>
</form>
