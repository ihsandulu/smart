<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = new mysqli("localhost", "smart_smart", "%w5K6b*OE@Ea!GnG", "smart_smart");
if ($db->connect_error) {
    http_response_code(500);
    exit("DB ERROR");
}

$user_id   = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$fcm_token = isset($_POST['fcm_token']) ? trim($_POST['fcm_token']) : '';

if ($user_id <= 0 || $fcm_token === '') {
    http_response_code(400);
    exit("INVALID DATA");
}

$stmt = $db->prepare("
    INSERT INTO user_fcm_tokens (user_id, fcm_token, updated_at)
    VALUES (?, ?, NOW())
    ON DUPLICATE KEY UPDATE updated_at = NOW()
");
$stmt->bind_param("is", $user_id, $fcm_token);
$stmt->execute();
$stmt->close();

echo "OK";
