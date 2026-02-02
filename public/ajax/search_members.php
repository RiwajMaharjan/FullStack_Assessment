<?php
require_once __DIR__ . '/../../config/db.php';

$query = trim($_GET['q'] ?? '');

$sql = "SELECT m.id, u.name, u.email, m.phone, ms.name AS membership, m.join_date, m.expiry_date
        FROM members m
        JOIN users u ON m.user_id = u.id
        LEFT JOIN memberships ms ON m.membership_id = ms.id";

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
    echo '<td>'.$m['id'].'</td>';
    echo '<td>'.htmlspecialchars($m['name']).'</td>';
    echo '<td>'.htmlspecialchars($m['email']).'</td>';
    echo '<td>'.htmlspecialchars($m['phone']).'</td>';
    echo '<td>'.htmlspecialchars($m['membership']).'</td>';
    echo '<td>'.$m['join_date'].'</td>';
    echo '<td>'.$m['expiry_date'].'</td>';
    echo '<td>Edit | Delete</td>'; // or your buttons here
    echo '</tr>';
}
