<?php
header('Content-Type: application/json');
if (file_exists('../cogs/db.php')) include_once '../cogs/db.php';
if (file_exists('app/system/cogs/db.php')) include_once 'app/system/cogs/db.php';
// --- Handle AJAX request for search suggestions ---
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['table'] ?? '');
    $column = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['column'] ?? '');
    $search = $_GET['q'] ?? '';
    $results = [];

    if ($table && $column && $search) {
        $sql = "SELECT * FROM `$table` WHERE `$column` LIKE :s ORDER BY created_at DESC LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['s' => "%$search%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
}
?>

