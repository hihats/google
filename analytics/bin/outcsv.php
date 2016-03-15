<?php
ini_set('date.timezone', 'Asia/Tokyo');

define('ROOT_DIR', '/var/www/google/');
require_once ROOT_DIR.'google-api-php-client/vendor/autoload.php';
require_once ROOT_DIR.'analytics/class/MultiService.php';
// argument validation
$conf_file_location = $argv[1];
if (!file_exists($conf_file_location)) throw new Exception("Error!! File does not exist.");
$period = isset($argv[2])? ["from" => $argv[2], "to" => $argv[2]] : array();
if(isset($argv[3])) $period['to'] = $argv[3];

try{
    $client = new Google_Client();
    // Create and configure a new client object.
    $key_file_location = ROOT_DIR.'service-account.json';
    $client->setAuthConfig($key_file_location);
    $client->setApplicationName("GoogleAnalytics");
    $client->setScopes(array(Google_Service_Analytics::ANALYTICS_READONLY));
    $client->useApplicationDefaultCredentials();
    // authorization
    $httpClient = $client->authorize();
    if ($client->isAccessTokenExpired()) {
        $client->refreshTokenWithAssertion();
    }
    $accessToken = $client->getAccessToken();

    // Analytics process
    $analytics = new Google_Service_Analytics_Multi_Service($client, $conf_file_location, $period);
    $res = array_map( function($view_id) use(&$analytics) {
        $obj = $analytics->gaDataGet($view_id);
        $analytics->outCsv($obj, ROOT_DIR."analytics/files/{$view_id}/");
    }, $analytics->view_ids);

}catch(Exception $e){
    echo $e->getMessage();
}catch(apiServiceException $e){
    echo $e->getMessage();
}
