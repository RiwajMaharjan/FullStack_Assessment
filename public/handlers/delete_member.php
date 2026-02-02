<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header('Location: unauthorized.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../dashboards/trainers_dashboard.php');
    exit;
}

if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    die('Invalid CSRF token');
}

$member_id = $_POST['id'] ?? '';

if ($member_id) {
    // This will also delete workouts & attendance due to FK cascade
    $stmt = $pdo->prepare("DELETE FROM members WHERE id = ?");
    $stmt->execute([$member_id]);
}

header('Location: ../dashboards/trainers_dashboard.php');
exit;
