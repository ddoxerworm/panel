<?php
// DATABASE
define('DB_HOST', '89.46.111.168');
define('DB_NAME', 'Sql1773898_1');
define('DB_USER', 'Sql1773898');
define('DB_PASS', 'Giuseppe89!');

// PANEL
define('panel_username', 'admin');
define('panel_password', '12345');

// TELEGRAM
$telegram_bot_token = '7275925899:AAEZWpsKa02qk8jUhBOceksReqY5viA1EvQ';
$telegram_chat_id = '5489289614';
$send_to_telegram = true;




// other methods

function connect_db() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }
}

function check_authorization() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(['error' => 'Access denied']);
        exit();
    }
}
?>
