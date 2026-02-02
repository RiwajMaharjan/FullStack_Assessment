<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

$query = trim($_GET['q'] ?? '');

$sql = "
    SELECT 
        m.id,
        u.name,
        u.email,
        m.phone,
        m.membership_id,
        ms.name AS membership,
        m.join_date,
        m.expiry_date
    FROM members m
    JOIN users u ON m.user_id = u.id
    LEFT JOIN memberships ms ON m.membership_id = ms.id
";

if ($query !== '') {
    $sql .= " WHERE u.name LIKE :q OR u.email LIKE :q";
}

$sql .= " ORDER BY m.id DESC";

$stmt = $pdo->prepare($sql);

if ($query !== '') {
    $stmt->execute([':q' => "%$query%"]);
} else {
    $stmt->execute();
}

$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$members) {
    echo '<tr><td colspan="8">No members found</td></tr>';
    exit;
}

foreach ($members as $m) {
    echo '<tr>';

    echo '<td>' . $m['id'] . '</td>';
    echo '<td>' . htmlspecialchars($m['name']) . '</td>';
    echo '<td>' . htmlspecialchars($m['email']) . '</td>';
    echo '<td>' . htmlspecialchars($m['phone']) . '</td>';
    echo '<td>' . htmlspecialchars($m['membership']) . '</td>';
    echo '<td>' . $m['join_date'] . '</td>';
    echo '<td>' . $m['expiry_date'] . '</td>';

    echo '<td style="display: flex; gap: 1rem;">
        <button class="btn action-edit edit-member-btn"
            data-id="' . $m['id'] . '"
            data-name="' . htmlspecialchars($m['name']) . '"
            data-email="' . htmlspecialchars($m['email']) . '"
            data-phone="' . htmlspecialchars($m['phone']) . '"
            data-membership_id="' . $m['membership_id'] . '"
            data-join_date="' . $m['join_date'] . '"
            data-expiry_date="' . $m['expiry_date'] . '"
        >Edit</button>

        <form action="../handlers/delete_member.php" method="POST">
            <input type="hidden" name="id" value="' . $m['id'] . '">
            <input type="hidden" name="csrf_token" value="' . generateCSRFToken() . '">
            <button type="submit" class="btn action-delete" onclick="return confirm(\'Delete this member?\')">Delete</button>
        </form>
    </td>';

    echo '</tr>';
}
