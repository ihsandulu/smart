<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ================== TAMBAHAN ================== */
require __DIR__ . '/fcm.php';  // versi debug-ready
/* ============================================== */

// ======== Koneksi DB ========
$db = new mysqli("localhost", "smart_smart", "%w5K6b*OE@Ea!GnG", "smart_smart");
if ($db->connect_error) {
    file_put_contents('/tmp/mqtt_debug.log', "DB ERROR\n", FILE_APPEND);
    exit;
}

// ======== Worker loop ========
while (($line = fgets(STDIN)) !== false) {
    $line = trim($line);
    if ($line === '') continue;

    file_put_contents('/tmp/mqtt_debug.log', "DAPAT: $line\n", FILE_APPEND);

    if (strpos($line, ' ') === false) continue;
    [$topic, $payload] = explode(' ', $line, 2);

    $parts = explode('/', $payload);
    if (count($parts) !== 5) continue;

    if (!ctype_digit($parts[0]) || !ctype_digit($parts[1]) || !ctype_digit($parts[2]) || !ctype_digit($parts[4])) {
        continue;
    }

    // Validasi string username
    if ($parts[3] === '' || strlen($parts[3]) > 100) continue;

    $user_id          = (int)$parts[0];
    $smartcategory_id = (int)$parts[1];
    $mqtt_number      = (int)$parts[2];
    $mqtt_username    = $parts[3];
    $mqtt_tipe        = (int)$parts[4];

    // ======== Simpan ke DB ========
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

    // ======== Ambil token user ========
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
        continue;
    }

    // ======== FCM Notification ========
    foreach ($tokens as $token) {
        if ($mqtt_tipe === 0) {
            $sound = "alarm.wav"; // pastikan file ada di res/raw di Android
            $title = "🚨 ALERT SENSOR";
            $body  = "Sensor aktif oleh $mqtt_username (Device #$mqtt_number)";
        } else {
            $sound = "biasa.wav";
            $title = "ℹ️ EVENT SISTEM";
            $body  = "Aktivitas non-sensor oleh $mqtt_username";
        }

        $fcm_res = fcm_send(
            $sound,
            $token,
            $title,
            $body,
            [
                'user_id' => (string)$user_id,
                'smartcategory_id' => (string)$smartcategory_id,
                'mqtt_number' => (string)$mqtt_number,
                'mqtt_username' => $mqtt_username,
                'mqtt_tipe' => (string)$mqtt_tipe
            ]
        );

        file_put_contents(
            '/tmp/mqtt_debug.log',
            date('Y-m-d H:i:s') . " - FCM SENT TO $token : $fcm_res\n",
            FILE_APPEND
        );
    }
}
