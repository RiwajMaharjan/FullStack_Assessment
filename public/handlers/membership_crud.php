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
$name = trim($_POST['name'] ?? '');
$duration = $_POST['duration_months'] ?? '';
$price = $_POST['price'] ?? '';

// Validation
if ($name === '' || $duration === '' || $price === '') {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit;
}

if ($duration <= 0 || $price < 0) {
    echo json_encode(['success' => false, 'error' => 'Duration must be positive and price cannot be negative']);
    exit;
}

try {
    if ($id === '') {
        // Check for duplicate name
        $checkStmt = $pdo->prepare("SELECT id FROM memberships WHERE name = ?");
        $checkStmt->execute([$name]);
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'Membership name already exists']);
            exit;
        }

        // ADD new membership
        $stmt = $pdo->prepare("INSERT INTO memberships (name, duration_months, price) VALUES (?, ?, ?)");
        $stmt->execute([$name, $duration, $price]);
        echo json_encode(['success' => true]);
    } else {
        // Check for duplicate name
        $checkStmt = $pdo->prepare("SELECT id FROM memberships WHERE name = ? AND id != ?");
        $checkStmt->execute([$name, $id]);
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'Membership name already exists']);
            exit;
        }

        // UPDATE existing membership
        $stmt = $pdo->prepare("UPDATE memberships SET name = ?, duration_months = ?, price = ? WHERE id = ?");
        $stmt->execute([$name, $duration, $price, $id]);
        echo json_encode(['success' => true]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
