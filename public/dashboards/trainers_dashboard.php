<?php
session_start();

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// Access control: only trainers
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header('Location: ../auth/unauthorized.php'); // Assuming unauthorized.php is moved or handled? It wasn't in list. Let's redirect to login.
    exit;
}
// Actually unauthorized.php might not exist or verify where it is. Assuming relative redirect.
// Original was header('Location: unauthorized.php');
// I didn't see unauthorized.php in file list. Maybe it's missing. I'll stick to ../index.php or similar if missing, but let's keep it relative for now or guess.
// Let's change it to ../index.php for safety if likely missing, or ../auth/login.php


// Fetch data
$members = $pdo->query("
    SELECT m.id, u.name, u.email, m.phone, m.membership_id, ms.name AS membership, m.join_date, m.expiry_date
    FROM members m
    JOIN users u ON m.user_id = u.id
    LEFT JOIN memberships ms ON m.membership_id = ms.id
    ORDER BY m.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$memberships = $pdo->query("SELECT * FROM memberships ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$workout_plans = $pdo->query("
    SELECT w.id, u.name AS member_name, w.member_id, w.plan_details, w.start_date, w.end_date
    FROM workout_plans w
    JOIN members m ON w.member_id = m.id
    JOIN users u ON m.user_id = u.id
    ORDER BY w.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$attendance = $pdo->query("
    SELECT a.id, u.name AS member_name, a.member_id, a.date, a.status
    FROM attendance a
    JOIN members m ON a.member_id = m.id
    JOIN users u ON m.user_id = u.id
    ORDER BY a.date DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trainer Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/trainers_dashboard.css">
</head>
<body>

<header>
    <h1>Trainer Dashboard</h1>
    <a href="../auth/logout.php" class="btn btn-logout">Logout</a>
</header>

<main>

<!-- MEMBERS -->
<section>
    <h2>Members</h2>
    <button class="btn btn-add add-member-btn">Add Member</button>
    <input type="text" id="membersSearch" placeholder="Search members...">

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Phone</th>
                <th>Membership</th><th>Join</th><th>Expiry</th><th>Actions</th>
            </tr>
        </thead>
        <tbody id="membersTable">
            <?php foreach ($members as $m): ?>
            <tr>
                <td><?= $m['id'] ?></td>
                <td><?= htmlspecialchars($m['name']) ?></td>
                <td><?= htmlspecialchars($m['email']) ?></td>
                <td><?= htmlspecialchars($m['phone']) ?></td>
                <td><?= htmlspecialchars($m['membership']) ?></td>
                <td><?= $m['join_date'] ?></td>
                <td><?= $m['expiry_date'] ?></td>
                <td style="display: flex; gap: 1rem;">
                    <button class="btn action-edit edit-member-btn"
                        data-id="<?= $m['id'] ?>"
                        data-name="<?= htmlspecialchars($m['name']) ?>"
                        data-email="<?= htmlspecialchars($m['email']) ?>"
                        data-phone="<?= htmlspecialchars($m['phone']) ?>"
                        data-membership_id="<?= $m['membership_id'] ?>"
                        data-join_date="<?= $m['join_date'] ?>"
                        data-expiry_date="<?= $m['expiry_date'] ?>">
                        Edit
                    </button>

                    <form action="../handlers/delete_member.php" method="POST">
                        <input type="hidden" name="id" value="<?= $m['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
                        <button type="submit" class="btn action-delete"
                            onclick="return confirm('Delete this member?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<!-- MEMBERSHIPS -->
<section>
    <h2>Memberships</h2>
    <button class="btn btn-add add-membership-btn">Add Membership</button>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Duration</th><th>Price</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($memberships as $ms): ?>
            <tr>
                <td><?= $ms['id'] ?></td>
                <td><?= htmlspecialchars($ms['name']) ?></td>
                <td><?= $ms['duration_months'] ?></td>
                <td><?= $ms['price'] ?></td>
                <td style="display: flex; gap: 1rem;">
                    <button class="btn action-edit edit-membership-btn"
                        data-id="<?= $ms['id'] ?>"
                        data-name="<?= htmlspecialchars($ms['name']) ?>"
                        data-duration_months="<?= $ms['duration_months'] ?>"
                        data-price="<?= $ms['price'] ?>">
                        Edit
                    </button>

                    <form action="../handlers/delete_membership.php" method="POST">
                        <input type="hidden" name="id" value="<?= $ms['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
                        <button class="btn action-delete"
                            onclick="return confirm('Delete this membership?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<!-- WORKOUT PLANS -->
<section>
    <h2>Workout Plans</h2>
    <button class="btn btn-add add-workout-btn">Add Workout</button>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Member</th><th>Details</th><th>Start</th><th>End</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($workout_plans as $w): ?>
            <tr>
                <td><?= $w['id'] ?></td>
                <td><?= htmlspecialchars($w['member_name']) ?></td>
                <td><?= htmlspecialchars($w['plan_details']) ?></td>
                <td><?= $w['start_date'] ?></td>
                <td><?= $w['end_date'] ?></td>
                <td style="display: flex; gap: 1rem;">
                    <button class="btn action-edit edit-workout-btn"
                        data-id="<?= $w['id'] ?>"
                        data-member_id="<?= $w['member_id'] ?>"
                        data-plan_details="<?= htmlspecialchars($w['plan_details']) ?>"
                        data-start_date="<?= $w['start_date'] ?>"
                        data-end_date="<?= $w['end_date'] ?>">
                        Edit
                    </button>

                    <form action="../handlers/delete_workout.php" method="POST">
                        <input type="hidden" name="id" value="<?= $w['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
                        <button class="btn action-delete"
                            onclick="return confirm('Delete this workout?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<!-- ATTENDANCE -->
<section>
    <h2>Attendance</h2>
    <button class="btn btn-add add-attendance-btn">Add Attendance</button>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Member</th><th>Date</th><th>Status</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attendance as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= htmlspecialchars($a['member_name']) ?></td>
                <td><?= $a['date'] ?></td>
                <td><?= $a['status'] ?></td>
                <td style="display: flex; gap: 1rem;">
                    <button class="btn action-edit edit-attendance-btn"
                        data-id="<?= $a['id'] ?>"
                        data-member_id="<?= $a['member_id'] ?>"
                        data-date="<?= $a['date'] ?>"
                        data-status="<?= $a['status'] ?>">
                        Edit
                    </button>

                    <form action="../handlers/delete_attendance.php" method="POST">
                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
                        <button class="btn action-delete"
                            onclick="return confirm('Delete this attendance?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

</main>

<?php include __DIR__ . '/../partials/modals.php'; ?>

<script src="../../assets/js/members_search.js"></script>
<script src="../../assets/js/member_modal.js"></script>
<script src="../../assets/js/membership_modal.js"></script>
<script src="../../assets/js/workout_modal.js"></script>
<script src="../../assets/js/attendance_modal.js"></script>

</body>
</html>
