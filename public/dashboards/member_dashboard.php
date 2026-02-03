<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch member record linked to this user
$sql = "SELECT m.id AS member_id, u.name, u.email 
        FROM members m 
        JOIN users u ON m.user_id = u.id 
        WHERE u.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$member = $stmt->fetch();

// If member record not found, show contact trainer
if (!$member) {
    $showPending = true;
} else {
    $showPending = false;
    $member_id = $member['member_id'];

    // Workout plans
    $sql = "SELECT plan_details, start_date, end_date 
            FROM workout_plans 
            WHERE member_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$member_id]);
    $workouts = $stmt->fetchAll();

    // Membership
    $sql = "SELECT ms.name AS membership_type, m.join_date AS start_date, m.expiry_date AS end_date
            FROM members m
            LEFT JOIN memberships ms ON m.membership_id = ms.id
            WHERE m.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$member_id]);
    $membership = $stmt->fetch();

    // Attendance
    $sql = "SELECT date, status 
            FROM attendance 
            WHERE member_id = ? 
            ORDER BY date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$member_id]);
    $attendance = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/member_dashboard.css">
    <link rel="stylesheet" href="../../assets/css/common.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header>
    <h1>
        Welcome<?= $showPending ? '' : ', ' . htmlspecialchars($member['name']) ?>
    </h1>
    <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</header>

<main>
<?php if ($showPending): ?>
    <section>
        <h2>Profile Pending</h2>
        <p>Your member profile has not been created yet.</p>
        <p>Please contact your trainer to activate your account.</p>
    </section>
<?php else: ?>
    <section>
        <h2><i class="fas fa-user-circle"></i> Profile</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($member['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($member['email']) ?></p>
    </section>

    <section id="workoutsList">
        <h2><i class="fas fa-dumbbell"></i> Workout Plans</h2>
        <?php if ($workouts): ?>
            <?php foreach ($workouts as $index => $workout): ?>
                <div class="<?= $index >= 5 ? 'hidden-item-toggle' : '' ?>">
                    <p><?= htmlspecialchars($workout['plan_details']) ?></p>
                    <small><?= $workout['start_date'] ?> to <?= $workout['end_date'] ?></small>
                </div>
            <?php endforeach; ?>
            <?php if (count($workouts) > 5): ?>
                <button class="view-more-btn" data-target="workoutsList">View More <i class="fas fa-chevron-down"></i></button>
            <?php endif; ?>
        <?php else: ?>
            <p>No workout plans assigned.</p>
        <?php endif; ?>
    </section>

    <section>
        <h2><i class="fas fa-id-card"></i> Membership</h2>
        <?php if ($membership && $membership['membership_type']): ?>
            <p><strong>Type:</strong> <?= htmlspecialchars($membership['membership_type']) ?></p>
            <p><?= $membership['start_date'] ?> to <?= $membership['end_date'] ?></p>
        <?php else: ?>
            <p>No active membership.</p>
        <?php endif; ?>
    </section>

    <section>
        <h2><i class="fas fa-calendar-check"></i> Attendance</h2>
        <?php if ($attendance): ?>
            <table id="attendanceTable">
                <tr><th>Date</th><th>Status</th></tr>
                <?php foreach ($attendance as $index => $row): ?>
                    <tr class="<?= $index >= 5 ? 'hidden-item-toggle' : '' ?>">
                        <td><?= $row['date'] ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php if (count($attendance) > 5): ?>
                <button class="view-more-btn" data-target="attendanceTable">View More <i class="fas fa-chevron-down"></i></button>
            <?php endif; ?>
        <?php else: ?>
            <p>No attendance records.</p>
        <?php endif; ?>
    </section>
<?php endif; ?>
</main>

</body>
<script src="../../assets/js/view_more.js"></script>
</html>
