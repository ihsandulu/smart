<?php
$db = new mysqli("localhost", "smart_smart", "%w5K6b*OE@Ea!GnG", "smart_smart");

$data = json_decode(file_get_contents("php://input"), true);

$user_id = isset($data['user_id']) ? (int)$data['user_id'] : 0;
$token   = isset($data['fcm_token']) ? trim($data['fcm_token']) : '';
$platform= isset($data['platform']) ? trim($data['platform']) : 'android';

if ($user_id > 0 && $token !== '') {
    $stmt = $db->prepare("
        INSERT INTO fcmtokens (user_id, fcmtokens_token, fcmtokens_platform, fcmtokens_updated_at)
        VALUES (?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE fcmtokens_updated_at = NOW()
    ");
    $stmt->bind_param("iss", $user_id, $token, $platform);
    $stmt->execute();
    $stmt->close();
    echo "OK";
} else {
    http_response_code(400);
    echo "INVALID DATA";
}

