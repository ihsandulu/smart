<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ob_implicit_flush(true);

/* ================== TAMBAHAN FCM ================== */
require __DIR__ . '/fcm.php';

/* ============================================== */
$db = new mysqli("localhost", "smart_smart", "%w5K6b*OE@Ea!GnG", "smart_smart");
if ($db->connect_error) {
    file_put_contents('/tmp/mqtt_debug.log', "DB ERROR\n", FILE_APPEND);
    exit;
}

while (($line = fgets(STDIN)) !== false) {
    $line = trim($line);
    if ($line === '') continue;

    file_put_contents('/tmp/mqtt_debug.log', "DAPAT: $line\n", FILE_APPEND);
    echo "DAPAT: $line\n";

    if (strpos($line, ' ') === false) continue;

    [$topic, $payload] = explode(' ', $line, 2);
    $parts = explode('/', $payload);
    if (count($parts) !== 5) continue;

    if (
        !ctype_digit($parts[0]) ||
        !ctype_digit($parts[1]) ||
        !ctype_digit($parts[2]) ||
        !ctype_digit($parts[4])
    ) continue;

    if ($parts[3] === '' || strlen($parts[3]) > 100) continue;

    $user_id            = (int)$parts[0];
    $smartcategory_id   = (int)$parts[1];
    $mqtt_number        = (int)$parts[2];
    $mqtt_username      = $parts[3];
    $mqtt_tipe          = (int)$parts[4];

    $stmt = $db->prepare("
        INSERT INTO mqtt (
            mqtt_topic,
            mqtt_payload,
            user_id,
            smartcategory_id,
            mqtt_number,
            mqtt_created_at,
            mqtt_username,
            mqtt_tipe
        ) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)
    ");
    $stmt->bind_param(
        "ssiiisi",
        $topic,
        $payload,
        $user_id,
        $smartcategory_id,
        $mqtt_number,
        $mqtt_username,
        $mqtt_tipe
    );
    $stmt->execute();
    $stmt->close();

    /* ================== AMBIL TOKEN DARI DB ================== */
    $tokens = [];
    $stmt = $db->prepare("SELECT fcmtokens_token FROM fcmtokens WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $tokens[] = $row['fcmtokens_token'];
    }
    $stmt->close();

    if (empty($tokens)) {
        file_put_contents('/tmp/mqtt_debug.log', "NO TOKEN FOR USER $user_id\n", FILE_APPEND);
        echo "NO TOKEN FOR USER $user_id\n";
        continue;
    }

    /* ================== TAMBAHAN FCM ================== */
    if ($mqtt_tipe === 0) {
        $sound = "alarm.wav";
        $title = "🚨 ALERT SENSOR";
        $body  = "Sensor aktif oleh $mqtt_username (Device #$mqtt_number)";
    } else {
        $sound = "biasa.wav";
        $title = "ℹ️ EVENT SISTEM";
        $body  = "Aktivitas non-sensor oleh $mqtt_username";
    }

    foreach ($tokens as $token) {
        echo "KIRIM FCM KE: $token\n";

        $fcm_res = fcm_send($sound, $token, $title, $body, [
            'user_id'           => (string)$user_id,
            'smartcategory_id'  => (string)$smartcategory_id,
            'mqtt_number'       => (string)$mqtt_number,
            'mqtt_username'     => $mqtt_username,
            'mqtt_tipe'         => (string)$mqtt_tipe
        ]);

        // log ke tmp/fcm_payload.log
        file_put_contents('/tmp/fcm_payload.log', "FCM SENT TO $token :\n$fcm_res\n\n", FILE_APPEND);

        // log ke console
        echo "RESPONSE FCM: $fcm_res\n";
    }
}
