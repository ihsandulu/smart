<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Client;

function fcm_send($deviceToken, $title, $body, $data = [])
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

            // NOTIF YANG DITAMPILKAN ANDROID
            "notification" => [
                "title" => $title,
                "body"  => $body
            ],

            // DATA TAMBAHAN (AMAN)
            "data" => $data,

            // 🔥 INI KUNCI SUPAYA BUNYI & REALTIME
            "android" => [
                "priority" => "HIGH",
                "notification" => [
                    "sound" => "default",
                    "channel_id" => "default"
                ]
            ]
        ]
    ];


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

    return $response;
}
