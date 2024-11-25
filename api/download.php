<?php
require_once '../config.php';
session_start();

check_authorization();

if (!isset($_GET['hwid'])) {
    http_response_code(400);
    echo json_encode(['error' => 'HWID is required']);
    exit();
}

$hwid = $_GET['hwid'];

$pdo = connect_db();

$stmt = $pdo->prepare("SELECT downloadLink FROM logs WHERE hwid = :hwid");
$stmt->execute(['hwid' => $hwid]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result && isset($result['downloadLink'])) {
    $downloadLink = $result['downloadLink'];

    if (file_exists($downloadLink)) {

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($downloadLink) . '"');
        header('Content-Length: ' . filesize($downloadLink));
        readfile($downloadLink);
        exit();
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'File not found']);
        exit();
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'HWID not found']);
}
