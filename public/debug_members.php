<?php
require_once __DIR__ . '/../config/db.php';

try {
    $members = $pdo->query("
        SELECT m.id
        FROM members m
        JOIN users u ON m.user_id = u.id
        LEFT JOIN memberships ms ON m.membership_id = ms.id
    ")->fetchAll(PDO::FETCH_ASSOC);

    echo "Total Members Found: " . count($members) . "\n";
    foreach ($members as $m) {
        echo "Member ID: " . $m['id'] . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
