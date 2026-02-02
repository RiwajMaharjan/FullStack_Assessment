<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// Only trainers can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Check CSRF
$csrf = $_POST['csrf_token'] ?? '';
if (!verifyCSRFToken($csrf)) {
    echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
    exit;
}

// Get POST data
$id = $_POST['id'] ?? '';
$member_id = $_POST['member_id'] ?? '';
$plan_details = trim($_POST['plan_details'] ?? '');
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$trainer_id = $_SESSION['user_id'] ?? null;

// Validation
if ($member_id === '' || $plan_details === '') {
    echo json_encode(['success' => false, 'error' => 'Member and plan details are required']);
    exit;
}

if ($start_date && $end_date && $start_date > $end_date) {
    echo json_encode(['success' => false, 'error' => 'Start date cannot be after end date']);
    exit;
}

try {
    if ($id === '') {
        // ADD new workout plan
        $stmt = $pdo->prepare("INSERT INTO workout_plans (member_id, trainer_id, plan_details, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$member_id, $trainer_id, $plan_details, $start_date ?: null, $end_date ?: null]);
        echo json_encode(['success' => true]);
    } else {
        // UPDATE existing workout plan
        $stmt = $pdo->prepare("UPDATE workout_plans SET member_id = ?, plan_details = ?, start_date = ?, end_date = ? WHERE id = ?");
        $stmt->execute([$member_id, $plan_details, $start_date ?: null, $end_date ?: null, $id]);
        echo json_encode(['success' => true]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
