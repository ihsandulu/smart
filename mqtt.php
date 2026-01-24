<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/fcm.php';

/* ============================================
   Paths log
============================================ */
$workerLog = __DIR__ . '/writable/logs/mqtt_worker.log';
$fcmLog    = __DIR__ . '/writable/logs/fcm_payload.log';

/* ============================================
   Koneksi DB
============================================ */
$db = new mysqli("localhost", "smart_smart", "%w5K6b*OE@Ea!GnG", "smart_smart");
if ($db->connect_error) {
    file_put_contents($workerLog, date('Y-m-d H:i:s') . " - DB ERROR\n", FILE_APPEND);
    exit;
}

/* ============================================
   Loop baca stdin MQTT
============================================ */
while (($line = fgets(STDIN)) !== false) {
    $line = trim($line);
    if ($line === '') continue;

    file_put_contents($workerLog, date('Y-m-d H:i:s') . " - DAPAT: $line\n", FILE_APPEND);

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

    $user_id          = (int)$parts[0];
    $smartcategory_id = (int)$parts[1];
    $mqtt_number      = (int)$parts[2];
    $mqtt_username    = $parts[3];
    $mqtt_tipe        = (int)$parts[4];

    /* ============================================
       Insert ke DB
    =========================================== */
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

    /* ============================================
       Ambil token FCM dari DB
    =========================================== */
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
        file_put_contents($workerLog, date('Y-m-d H:i:s') . " - NO TOKEN FOR USER $user_id\n", FILE_APPEND);
        continue;
    }

    /* ============================================
       Siapkan payload FCM
    =========================================== */
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
        // Log sebelum kirim
        file_put_contents($fcmLog, date('Y-m-d H:i:s') . " - KIRIM FCM ke $token\n", FILE_APPEND);

        $fcm_res = fcm_send(
            $sound,
            $token,
            $title,
            $body,
            [
                'user_id'          => (string)$user_id,
                'smartcategory_id' => (string)$smartcategory_id,
                'mqtt_number'      => (string)$mqtt_number,
                'mqtt_username'    => $mqtt_username,
                'mqtt_tipe'        => (string)$mqtt_tipe
            ]
        );

        // Log response
        // file_put_contents($fcmLog, date('Y-m-d H:i:s') . " - RESPONSE: " . $fcm_res . "\n\n", FILE_APPEND);
        file_put_contents('/tmp/fcm_payload.log', json_encode($payload, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);
        file_put_contents('/tmp/fcm_payload.log', "RESPONSE:\n" . $fcm_res . "\n\n", FILE_APPEND);


        // Log worker biasa
        file_put_contents($workerLog, date('Y-m-d H:i:s') . " - FCM SENT TO $token\n", FILE_APPEND);
    }
}
