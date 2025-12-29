<?php
header('Content-Type: application/json');
if (file_exists('../cogs/db.php')) include_once '../cogs/db.php';
if (file_exists('app/system/cogs/db.php')) include_once 'app/system/cogs/db.php';
// Optional filter by status
$status = $_GET['status'] ?? null;

$sql = "SELECT * FROM feedback";
$params = [];

if ($status) {
    $sql .= " WHERE status = :status";
    $params[':status'] = $status;
}

$sql .= " ORDER BY created_at DESC limit 50";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$feedback = $stmt->fetchAll();

echo json_encode(['success' => true, 'feedback' => $feedback]);
