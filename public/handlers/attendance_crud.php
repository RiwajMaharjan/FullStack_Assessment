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
$date = $_POST['date'] ?? '';
$status = $_POST['status'] ?? 'Absent';

// Validation
if ($member_id === '' || $date === '') {
    echo json_encode(['success' => false, 'error' => 'Member and date are required']);
    exit;
}

// Ensure status is either Present or Absent
$status = in_array(strtolower($status), ['present', 'absent']) ? ucfirst(strtolower($status)) : 'Absent';

try {
    if ($id === '') {
        // Check for duplicate attendance
        $checkStmt = $pdo->prepare("SELECT id FROM attendance WHERE member_id = ? AND date = ?");
        $checkStmt->execute([$member_id, $date]);
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'Attendance already recorded for this date']);
            exit;
        }

        // ADD new attendance
        $stmt = $pdo->prepare("INSERT INTO attendance (member_id, date, status) VALUES (?, ?, ?)");
        $stmt->execute([$member_id, $date, $status]);
        echo json_encode(['success' => true]);
    } else {
        // Check for duplicate attendance
        $checkStmt = $pdo->prepare("SELECT id FROM attendance WHERE member_id = ? AND date = ? AND id != ?");
        $checkStmt->execute([$member_id, $date, $id]);
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'Attendance already recorded for this member on this date']);
            exit;
        }

        // UPDATE existing attendance
        $stmt = $pdo->prepare("UPDATE attendance SET member_id = ?, date = ?, status = ? WHERE id = ?");
        $stmt->execute([$member_id, $date, $status, $id]);
        echo json_encode(['success' => true]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
