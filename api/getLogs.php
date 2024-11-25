<?php
require_once '../config.php';

session_start();

check_authorization();

$pdo = connect_db();

try {
    $stmt = $pdo->query("SELECT * FROM logs");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $logsCount = count($logs);

    echo json_encode([
        'logs' => $logs,
        'logsCount' => $logsCount
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to fetch logs: ' . $e->getMessage()]);
}
?>
