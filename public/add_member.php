<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header('Location: login.php');
    exit;
}

$users = $pdo->query("
    SELECT id, email 
    FROM users 
    WHERE role = 'member' 
    AND id NOT IN (SELECT user_id FROM members)
")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $phone = $_POST['phone'];
    $membership_id = $_POST['membership_id'];
    $join_date = date('Y-m-d');
    $expiry_date = $_POST['expiry_date'];

    $stmt = $pdo->prepare("
        INSERT INTO members (user_id, phone, membership_id, join_date, expiry_date)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $phone, $membership_id, $join_date, $expiry_date]);

    header('Location: trainers_dashboard.php');
    exit;
}

$memberships = $pdo->query("SELECT * FROM memberships")->fetchAll(PDO::FETCH_ASSOC);
?>

<form method="POST">
    <select name="user_id" required>
        <option value="">Select User</option>
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>"><?= $u['email'] ?></option>
        <?php endforeach; ?>
    </select>

    <input type="text" name="phone" placeholder="Phone" required>

    <select name="membership_id" required>
        <option value="">Select Membership</option>
        <?php foreach ($memberships as $m): ?>
            <option value="<?= $m['id'] ?>"><?= $m['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <input type="date" name="expiry_date" required>

    <button type="submit">Add Member</button>
</form>
