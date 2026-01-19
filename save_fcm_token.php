<?php
$db = new mysqli("localhost", "smart_smart", "%w5K6b*OE@Ea!GnG", "smart_smart");
$data = json_decode(file_get_contents("php://input"), true);

$user_id = (int)$data['user_id'];
$token   = trim($data['fcm_token']);

if ($user_id > 0 && $token !== '') {
    $stmt = $db->prepare("
        INSERT INTO user_fcm (user_id, fcm_token, created_at)
        VALUES (?, ?, NOW())
        ON DUPLICATE KEY UPDATE fcm_token=VALUES(fcm_token)
    ");
    $stmt->bind_param("is", $user_id, $token);
    $stmt->execute();
}
