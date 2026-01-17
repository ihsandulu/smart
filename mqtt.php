<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = new mysqli("localhost", "DB_USER", "DB_PASS", "DB_NAME");
if ($db->connect_error) {
    file_put_contents('/tmp/mqtt_debug.log', "DB ERROR\n", FILE_APPEND);
    exit;
}

while (($line = fgets(STDIN)) !== false) {
    $line = trim($line);
    if ($line === '') continue;

    file_put_contents('/tmp/mqtt_debug.log', "DAPAT: $line\n", FILE_APPEND);

    // parsing topic dan payload dari mosquitto_sub
    [$topic, $payload] = explode(' ', trim($line), 2);

    // parsing payload: user_id/smartcategory_id/mqtt_number
    $parts = explode('/', $payload);

    // validasi jumlah elemen
    if (count($parts) !== 3) {
        // payload rusak → jangan simpan
        return;
    }

    $user_id          = (int) $parts[0];
    $smartcategory_id = (int) $parts[1];
    $mqtt_number      = (int) $parts[2];


    $stmt = $db->prepare("
        INSERT INTO mqtt (
            mqtt_topic,
            mqtt_payload,
            user_id,
            smartcategory_id,
            mqtt_number,
            mqtt_created_at
        ) VALUES (?, ?, ?, ?, ?, NOW())
        ");

    $stmt->bind_param(
        "ssiii",
        $topic,
        $payload,
        $user_id,
        $smartcategory_id,
        $mqtt_number
    );

    $stmt->execute();
}
