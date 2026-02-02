<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

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

try {
    if ($id === '') {
        // ADD new membership
        $stmt = $pdo->prepare("INSERT INTO memberships (name, duration_months, price) VALUES (?, ?, ?)");
        $stmt->execute([$name, $duration, $price]);
        echo json_encode(['success' => true]);
    } else {
        // UPDATE existing membership
        $stmt = $pdo->prepare("UPDATE memberships SET name = ?, duration_months = ?, price = ? WHERE id = ?");
        $stmt->execute([$name, $duration, $price, $id]);
        echo json_encode(['success' => true]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
