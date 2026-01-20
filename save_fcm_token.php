<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: text/plain");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = new mysqli("localhost", "smart_smart", "%w5K6b*OE@Ea!GnG", "smart_smart");
if ($db->connect_error) {
    echo "DB ERROR";
    exit;
}

$raw = file_get_contents("php://input");
file_put_contents('/tmp/fcm_debug.log', "RAW: $raw\n", FILE_APPEND);

$data = json_decode($raw, true);

$user_id = isset($data['user_id']) ? (int)$data['user_id'] : 0;
$token   = isset($data['fcm_token']) ? trim($data['fcm_token']) : '';
$platform= isset($data['platform']) ? $data['platform'] : 'android';

if ($user_id <= 0 || $token === '') {
    echo "INVALID DATA";
    exit;
}

$stmt = $db->prepare("
    INSERT INTO fcmtokens
    (user_id, fcmtokens_token, fcmtokens_platform, fcmtokens_updated_at)
    VALUES (?, ?, ?, NOW())
    ON DUPLICATE KEY UPDATE fcmtokens_updated_at = NOW()
");
$stmt->bind_param("iss", $user_id, $token, $platform);
$stmt->execute();
$stmt->close();

echo "OK";
