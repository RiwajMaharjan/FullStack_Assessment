<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header('Location: unauthorized.php');
    exit;
}

if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    die('Invalid CSRF token');
}

$id = $_POST['id'] ?? '';

if ($id) {
    // Check if any members are using this membership
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM members WHERE membership_id = ?");
    $checkStmt->execute([$id]);
    if ($checkStmt->fetchColumn() > 0) {
        $_SESSION['error'] = 'Cannot delete membership. There are members assigned to it.';
        header('Location: ../dashboards/trainer_dashboard.php');
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM memberships WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['msg'] = 'Membership deleted';
}

header('Location: ../dashboards/trainer_dashboard.php');
exit;
