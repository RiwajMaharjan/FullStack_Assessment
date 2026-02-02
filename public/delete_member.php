<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM members WHERE id = ?");
$stmt->execute([$id]);

header('Location: trainers_dashboard.php');
exit;
