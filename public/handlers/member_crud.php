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
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$membership_id = $_POST['membership_id'] ?? null;
$join_date = $_POST['join_date'] ?? null;
$expiry_date = $_POST['expiry_date'] ?? null;

// Basic validation
if ($name === '' || $email === '' || empty($membership_id)) {
    echo json_encode(['success' => false, 'error' => 'Name, email, and membership are required']);
    exit;
}

if (empty($join_date)) {
    echo json_encode(['success' => false, 'error' => 'Join date is required']);
    exit;
}

// Calculate expiry date based on membership duration
try {
    $stmt = $pdo->prepare("SELECT duration_months FROM memberships WHERE id = ?");
    $stmt->execute([$membership_id]);
    $ms = $stmt->fetch();
    
    if (!$ms) {
        throw new Exception('Invalid membership selected');
    }

    $duration = (int)$ms['duration_months'];
    
    // Calculate expiry
    $date = new DateTime($join_date);
    $date->modify("+$duration months");
    $expiry_date = $date->format('Y-m-d');

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error calculating expiry: ' . $e->getMessage()]);
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
        $stmt->execute([$user_id, $name, $email, $phone, $membership_id, $join_date, $expiry_date]);

        echo json_encode(['success' => true, 'message' => 'Member approved successfully']);
    } else {
        // UPDATE existing member
        $stmt = $pdo->prepare("SELECT user_id FROM members WHERE id = ?");
        $stmt->execute([$id]);
        $member = $stmt->fetch();
        if (!$member) throw new Exception('Member not found');

        $user_id = $member['user_id'];

        // Check for duplicate email (excluding current member)
        $checkStmt = $pdo->prepare("SELECT id FROM members WHERE email = ? AND id != ?");
        $checkStmt->execute([$email, $id]);
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'Email is already used by another member']);
            exit;
        }

        // Update members table only
        $stmt = $pdo->prepare("UPDATE members 
                               SET full_name = ?, email = ?, phone = ?, membership_id = ?, join_date = ?, expiry_date = ?
                               WHERE id = ?");
        $stmt->execute([$name, $email, $phone, $membership_id, $join_date, $expiry_date, $id]);

        echo json_encode(['success' => true, 'message' => 'Member updated successfully']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
