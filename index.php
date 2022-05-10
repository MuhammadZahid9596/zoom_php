<?php
require_once 'vendor/autoload.php';
 
use \Firebase\JWT\JWT;
use GuzzleHttp\Client;
 
define('ZOOM_API_KEY', '68IADA_XS5WAM6fWRXSGbw');
define('ZOOM_SECRET_KEY', 'CkjBlHdPQBb1YB0bMOuGk8ls8GUw7RFRBRFq');

/**
 * Get Access token 
 */
function getZoomAccessToken() {
    $key = ZOOM_SECRET_KEY;
    $payload = array(
        "iss" => ZOOM_API_KEY,
        'exp' => time() + 3600,
    );
    return JWT::encode($payload, $key, 'HS256');    
}

/**
 * function to create zoom meeting 
 */
function createZoomMeeting() {
    $client = new Client([
        // Base URI is used with relative requests
        'base_uri' => 'https://api.zoom.us',
    ]);
 
    $response = $client->request('POST', '/v2/users/me/meetings', [
        "headers" => [
            "Authorization" => "Bearer " . getZoomAccessToken()
        ],
        'json' => [
            "topic" => "Let's Learn WordPress",
            "type" => 2,
            "start_time" => "2021-01-30T20:30:00",
            "duration" => "30", // 30 mins
            "password" => "123456"
        ],
    ]);
 
    $data = json_decode($response->getBody());
    echo "Join URL: ". $data->join_url;
    echo "<br>";
    echo "Meeting Password: ". $data->password;
    echo "Token".getZoomAccessToken();
}
 
createZoomMeeting();