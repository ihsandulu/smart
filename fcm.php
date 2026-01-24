<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Client;

function fcm_send($sound, $deviceToken, $title, $body, $data = [])
{
    $projectId = 'mqtt-89ea3';
    $serviceAccount = __DIR__ . '/mqtt-89ea3-firebase-adminsdk-fbsvc-ce15f1d356.json';

    $client = new Client();
    $client->setAuthConfig($serviceAccount);
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

    $token = $client->fetchAccessTokenWithAssertion();
    $accessToken = $token['access_token'];

    $url = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";

    $payload = [
        "message" => [
            "token" => $deviceToken,
            "android" => [
                "priority" => "HIGH",
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                    "channel_id" => "alert_channel",
                    "sound" => $sound
                ]
            ],
            "data" => $data
        ]
    ];

    // ===========================
    // LOG PAYLOAD KE FILE
    // ===========================
    file_put_contents('/tmp/fcm_payload.log', json_encode($payload, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    $response = curl_exec($ch);

    // ===========================
    // LOG RESPONSE JUGA
    // ===========================
    file_put_contents('/tmp/fcm_payload.log', "RESPONSE:\n" . $response . "\n\n", FILE_APPEND);

    curl_close($ch);

    return $response;
}
