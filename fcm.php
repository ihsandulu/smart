<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Client;

function fcm_send($sound, $deviceToken, $title, $body, $data = [])
{
    echo "FCM SEND FUNCTION CALLED\n";

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
                    "channel_id" => "alarm_channel",
                    "sound" => "default"  // pakai default supaya bunyi walau app belum jalan
                ]
            ],
            // data tetap disertakan untuk JS
            "data" => array_merge($data, [
                "title" => $title,
                "body" => $body,
                "sound" => $sound
            ])
        ]
    ];


    // LOG ke /tmp
    file_put_contents('/tmp/fcm_payload.log', json_encode($payload, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

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
    curl_close($ch);

    // LOG response juga
    file_put_contents('/tmp/fcm_payload.log', "RESPONSE:\n$response\n\n", FILE_APPEND);

    echo "FCM RESPONSE: $response\n";

    return $response;
}
