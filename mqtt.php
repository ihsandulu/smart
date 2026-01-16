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

    // parsing
    [$topic, $payload] = explode(' ', $line, 2);

    $stmt = $db->prepare("INSERT INTO mqtt_messages (topic, payload, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $topic, $payload);
    $stmt->execute();
}
