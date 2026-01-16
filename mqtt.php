<?php
/**
 * MQTT STDIN → CI4 API
 * Project: M1
 * File: /home/smart.qithy.com/public_html/mqtt.php
 */

if (php_sapi_name() !== 'cli') {
    die("CLI only\n");
}

while (($line = fgets(STDIN)) !== false) {

    $line = trim($line);
    if ($line === '') continue;

    // format: topic payload
    $pos = strpos($line, ' ');
    if ($pos === false) continue;

    $topic   = substr($line, 0, $pos);
    $payload = substr($line, $pos + 1);

    $json = json_encode([
        'topic'   => $topic,
        'message' => $payload,
        'device'  => 'mqtt-broker'
    ]);

    $ctx = stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/json\r\n",
            'content' => $json,
            'timeout' => 5
        ]
    ]);

    @file_get_contents(
        'https://smart.qithy.com/api/save_mqtt',
        false,
        $ctx
    );
}
