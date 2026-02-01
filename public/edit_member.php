<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM members WHERE id = ?");
$stmt->execute([$id]);
$member = $stmt->fetch();

$memberships = $pdo->query("SELECT * FROM memberships")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'];
    $membership_id = $_POST['membership_id'];
    $expiry_date = $_POST['expiry_date'];

    $stmt = $pdo->prepare("
        UPDATE members 
        SET phone = ?, membership_id = ?, expiry_date = ?
        WHERE id = ?
    ");
    $stmt->execute([$phone, $membership_id, $expiry_date, $id]);

    header('Location: trainers_dashboard.php');
    exit;
}
?>

<form method="POST">
    <input type="text" name="phone" value="<?= $member['phone'] ?>" required>

    <select name="membership_id" required>
        <?php foreach ($memberships as $m): ?>
            <option value="<?= $m['id'] ?>" <?= $m['id'] == $member['membership_id'] ? 'selected' : '' ?>>
                <?= $m['name'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="date" name="expiry_date" value="<?= $member['expiry_date'] ?>" required>

    <button type="submit">Update Member</button>
</form>
