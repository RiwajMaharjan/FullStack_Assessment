<?php
require_once __DIR__ . '/../config/db.php';

$name = 'Riwaj Maharjan';
$email = 'riwajmaharjan0808@gmail.com';
$password = password_hash('riwaj123', PASSWORD_BCRYPT);
$role = 'trainer';

$stmt = $pdo->prepare("
    INSERT INTO users (name, email, password, role)
    VALUES (?, ?, ?, ?)
");

$stmt->execute([$name, $email, $password, $role]);

echo "Trainer added successfully";
