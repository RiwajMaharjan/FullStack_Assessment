<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Access control: only trainers
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header('Location: unauthorized.php');
    exit;
}

// Fetch data for display
$members = $pdo->query("SELECT m.id, u.name, u.email, m.phone, ms.name AS membership, m.join_date, m.expiry_date
                        FROM members m
                        JOIN users u ON m.user_id = u.id
                        LEFT JOIN memberships ms ON m.membership_id = ms.id
                        ORDER BY m.id DESC")->fetchAll(PDO::FETCH_ASSOC);

$memberships = $pdo->query("SELECT * FROM memberships ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$workout_plans = $pdo->query("SELECT w.id, u.name AS member_name, w.plan_details, w.start_date, w.end_date
                              FROM workout_plans w
                              JOIN members m ON w.member_id = m.id
                              JOIN users u ON m.user_id = u.id
                              ORDER BY w.id DESC")->fetchAll(PDO::FETCH_ASSOC);

$attendance = $pdo->query("SELECT a.id, u.name AS member_name, a.date, a.status
                           FROM attendance a
                           JOIN members m ON a.member_id = m.id
                           JOIN users u ON m.user_id = u.id
                           ORDER BY a.date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Dashboard</title>
    <link rel="stylesheet" href="../assets/css/trainers_dashboard.css">
</head>
<body>
    <header>
        <h1>Trainer Dashboard</h1>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <section>
            <h2>Members</h2>
            <a href="add_member.php" class="btn">Add Member</a>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Membership</th>
                    <th>Join Date</th>
                    <th>Expiry</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($members as $m): ?>
                <tr>
                    <td><?= $m['id'] ?></td>
                    <td><?= htmlspecialchars($m['name']) ?></td>
                    <td><?= htmlspecialchars($m['email']) ?></td>
                    <td><?= htmlspecialchars($m['phone']) ?></td>
                    <td><?= htmlspecialchars($m['membership']) ?></td>
                    <td><?= $m['join_date'] ?></td>
                    <td><?= $m['expiry_date'] ?></td>
                    <td>
                        <a href="edit_member.php?id=<?= $m['id'] ?>">Edit</a> | 
                        <a href="delete_member.php?id=<?= $m['id'] ?>" class="delete-link" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>

        <section>
            <h2>Memberships</h2>
            <a href="add_membership.php">Add Membership</a>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Duration (months)</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($memberships as $ms): ?>
                <tr>
                    <td><?= $ms['id'] ?></td>
                    <td><?= htmlspecialchars($ms['name']) ?></td>
                    <td><?= $ms['duration_months'] ?></td>
                    <td><?= $ms['price'] ?></td>
                    <td>
                        <a href="edit_membership.php?id=<?= $ms['id'] ?>">Edit</a> |
                        <a href="delete_membership.php?id=<?= $ms['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>

        <section>
            <h2>Workout Plans</h2>
            <a href="add_workout.php">Add Workout</a>
            <table border="1">
                <tr><th>ID</th><th>Member</th><th>Details</th><th>Start</th><th>End</th><th>Actions</th></tr>
                <?php foreach ($workout_plans as $w): ?>
                <tr>
                    <td><?= $w['id'] ?></td>
                    <td><?= htmlspecialchars($w['member_name']) ?></td>
                    <td><?= htmlspecialchars($w['plan_details']) ?></td>
                    <td><?= $w['start_date'] ?></td>
                    <td><?= $w['end_date'] ?></td>
                    <td>
                        <a href="edit_workout.php?id=<?= $w['id'] ?>">Edit</a> |
                        <a href="delete_workout.php?id=<?= $w['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>

        <section>
            <h2>Attendance</h2>
            <a href="add_attendance.php">Mark Attendance</a>
            <table border="1">
                <tr><th>ID</th><th>Member</th><th>Date</th><th>Status</th><th>Actions</th></tr>
                <?php foreach ($attendance as $a): ?>
                <tr>
                    <td><?= $a['id'] ?></td>
                    <td><?= htmlspecialchars($a['member_name']) ?></td>
                    <td><?= $a['date'] ?></td>
                    <td><?= $a['status'] ?></td>
                    <td>
                        <a href="edit_attendance.php?id=<?= $a['id'] ?>">Edit</a>
                        
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </main>
</body>
</html>
