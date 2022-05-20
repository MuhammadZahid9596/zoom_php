<?php
require_once 'vendor/autoload.php';
require 'conn.php';
 
use \Firebase\JWT\JWT;
use GuzzleHttp\Client;


/**
 * Get Credentials From db 
 * iska dekhelena constant define krny pe chal rha ha ese error arha ha , abhi kam start kr rha hn office ka 
 * moqa mila to dekhlunga baqi tmhara sara kam hogya h jo clal pe discuss hua tha 
 */

$zoom_account = "SELECT * FROM zoom_accounts where id = ".$_POST['selected_zoom_account'];
$result_zoom_account = $conn->query($zoom_account);
$row =  $result_zoom_account->fetch_assoc();
$api_key = $row['api_key'];
$secret_key = $row['api_secret'];

define('ZOOM_API_KEY', "$api_key");
define('ZOOM_SECRET_KEY', "$secret_key");


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
function createZoomMeeting($conn) {
    $client = new Client([
        // Base URI is used with relative requests
        'base_uri' => 'https://api.zoom.us',
    ]);
 
    $response = $client->request('POST', '/v2/users/me/meetings', [
        "headers" => [
            "Authorization" => "Bearer " . getZoomAccessToken()
        ],
        'json' => [
            "topic" => $_POST['topic'],
            "type" => 2,
            "start_time" => $_POST['datetime'],
            "duration" => $_POST['duration'], // 30 mins
            "password" => "123456"
        ],
    ]);
 
    $data = json_decode($response->getBody());
    echo "Join URL: ". $data->join_url;
    echo "<br>";
    echo "Meeting Password: ". $data->password;
    echo "Token".getZoomAccessToken();

    /**
     * Inserting meeting details and client details in Data base 
     */
    $sql_meeting = "INSERT INTO meetings (topic, duration, meeting_link, start_time)
    VALUES ('".$_POST['topic']."', '".$_POST['duration']."', '".$data->join_url."', '".$_POST['datetime']."');";
    
    mysqli_query($conn, $sql_meeting) or die (mysqli_connect_error());

    $latest_meeting = $conn->insert_id;

    $sql_client_meeting = "INSERT INTO client_meeting (client_id, meeting_id)
    VALUES ('".$_POST['selected_client']."', '".$latest_meeting."');";
    
    mysqli_query($conn, $sql_client_meeting) or die (mysqli_connect_error());

    foreach($_POST['selected_participants'] as $participant){
        $sql_participant_meeting = "INSERT INTO participant_meeting (participant_id, meeting_id)
        VALUES ('".$participant."', '".$latest_meeting."');";

        mysqli_query($conn, $sql_participant_meeting) or die (mysqli_connect_error());
    }

}
 
createZoomMeeting($conn);