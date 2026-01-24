<?php
require __DIR__ . '/vendor/autoload.php';
use Google\Client;

function fcm_send($sound, $deviceToken, $title, $body, $data = [])
{
    $projectId = 'mqtt-89ea3';
    $serviceAccount = __DIR__ . '/mqtt-89ea3-firebase-adminsdk-fbsvc-ce15f1d356.json';
    // $logFile = __DIR__ . '/writable/logs/fcm_payload.log';
    $logFile = '/tmp/fcm_payload.log';


    try {
        $client = new Client();
        $client->setAuthConfig($serviceAccount);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $token = $client->fetchAccessTokenWithAssertion();
        $accessToken = $token['access_token'] ?? null;

        if (!$accessToken) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - ERROR: no access token\n", FILE_APPEND);
            return false;
        }

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

        $payloadJson = json_encode($payload, JSON_PRETTY_PRINT);
        if ($payloadJson === false) {
            $errMsg = json_last_error_msg();
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - JSON ENCODE ERROR: $errMsg\n", FILE_APPEND);
            return false;
        }

        file_put_contents($logFile, date('Y-m-d H:i:s') . " - PAYLOAD:\n" . $payloadJson . "\n", FILE_APPEND);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $accessToken",
                "Content-Type: application/json"
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $payloadJson
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $curlErr = curl_error($ch);
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - CURL ERROR: $curlErr\n\n", FILE_APPEND);
        } else {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - RESPONSE:\n$response\n\n", FILE_APPEND);
        }

        curl_close($ch);
        return $response;

    } catch (\Exception $e) {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - EXCEPTION: " . $e->getMessage() . "\n\n", FILE_APPEND);
        return false;
    }
}
