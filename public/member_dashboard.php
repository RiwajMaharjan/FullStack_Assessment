<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT m.id AS member_id, u.name, u.email 
        FROM members m 
        JOIN users u ON m.user_id = u.id 
        WHERE u.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$member = $stmt->fetch();

if (!$member):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="../assets/css/member_dashboard.css">
</head>
<body>

<header>
    <h1>Welcome</h1>
    <a href="logout.php">Logout</a>
</header>

<section>
    <h2>Profile Pending</h2>
    <p>Your member profile has not been created yet.</p>
    <p>Please contact your trainer to activate your account.</p>
</section>

</body>
</html>
<?php
exit;
endif;

$member_id = $member['member_id'];

$sql = "SELECT plan_details, start_date, end_date 
        FROM workout_plans 
        WHERE member_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$member_id]);
$workouts = $stmt->fetchAll();

$sql = "SELECT ms.name AS membership_type, m.join_date AS start_date, m.expiry_date AS end_date
        FROM members m
        LEFT JOIN memberships ms ON m.membership_id = ms.id
        WHERE m.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$member_id]);
$membership = $stmt->fetch();

$sql = "SELECT date, status 
        FROM attendance 
        WHERE member_id = ? 
        ORDER BY date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$member_id]);
$attendance = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="../assets/css/member_dashboard.css">
</head>
<body>

<header>
    <h1>Welcome, <?= htmlspecialchars($member['name']) ?></h1>
    <a href="logout.php">Logout</a>
</header>

<section>
    <h2>Profile</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($member['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($member['email']) ?></p>
</section>

<section>
    <h2>Workout Plans</h2>
    <?php if ($workouts): ?>
        <?php foreach ($workouts as $workout): ?>
            <div>
                <p><?= htmlspecialchars($workout['plan_details']) ?></p>
                <small><?= $workout['start_date'] ?> to <?= $workout['end_date'] ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No workout plans assigned.</p>
    <?php endif; ?>
</section>

<section>
    <h2>Membership</h2>
    <?php if ($membership && $membership['membership_type']): ?>
        <p><strong>Type:</strong> <?= htmlspecialchars($membership['membership_type']) ?></p>
        <p><?= $membership['start_date'] ?> to <?= $membership['end_date'] ?></p>
    <?php else: ?>
        <p>No active membership.</p>
    <?php endif; ?>
</section>

<section>
    <h2>Attendance</h2>
    <?php if ($attendance): ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php foreach ($attendance as $row): ?>
                <tr>
                    <td><?= $row['date'] ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No attendance records.</p>
    <?php endif; ?>
</section>

</body>
</html>
