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
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$membership_id = $_POST['membership_id'] ?? null;
$join_date = $_POST['join_date'] ?? null;
$expiry_date = $_POST['expiry_date'] ?? null;

// Basic validation
if ($name === '' || $email === '') {
    echo json_encode(['success' => false, 'error' => 'Name and email are required']);
    exit;
}

try {
    if ($id === '') {
        // APPROVE new member
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            echo json_encode(['success' => false, 'error' => 'User must register first']);
            exit;
        }

        $user_id = $user['id'];

        // Check if member already approved
        $stmt = $pdo->prepare("SELECT id FROM members WHERE user_id = ?");
        $stmt->execute([$user_id]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'Member already approved']);
            exit;
        }

        // Add to members table
        $stmt = $pdo->prepare("INSERT INTO members (user_id, full_name, email, phone, membership_id, join_date, expiry_date)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $email, $phone, $membership_id ?: null, $join_date ?: null, $expiry_date ?: null]);

        echo json_encode(['success' => true, 'message' => 'Member approved successfully']);
    } else {
        // UPDATE existing member
        $stmt = $pdo->prepare("SELECT user_id FROM members WHERE id = ?");
        $stmt->execute([$id]);
        $member = $stmt->fetch();
        if (!$member) throw new Exception('Member not found');

        $user_id = $member['user_id'];

        // Update members table only
        $stmt = $pdo->prepare("UPDATE members 
                               SET full_name = ?, email = ?, phone = ?, membership_id = ?, join_date = ?, expiry_date = ?
                               WHERE id = ?");
        $stmt->execute([$name, $email, $phone, $membership_id ?: null, $join_date ?: null, $expiry_date ?: null, $id]);

        echo json_encode(['success' => true, 'message' => 'Member updated successfully']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
