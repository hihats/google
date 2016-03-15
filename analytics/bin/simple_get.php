<?php
/*
 * [*required] place configuration file at config/***.php
 */
ini_set('date.timezone', 'Asia/Tokyo');

define('ROOT_DIR', '/var/www/google/');
require_once ROOT_DIR.'google-api-php-client/vendor/autoload.php';

try{
    $client = new Google_Client();
    // Create and configure a new client object.
    $key_file_location = ROOT_DIR.'service-account.json';
    $client->setAuthConfig($key_file_location);
    $client->setApplicationName("GoogleAnalytics");
    $client->setScopes(array(Google_Service_Analytics::ANALYTICS_READONLY));

    $client->useApplicationDefaultCredentials();
    $httpClient = $client->authorize();
    if ($client->isAccessTokenExpired()) {
        $client->refreshTokenWithAssertion();
    }
    $accessToken = $client->getAccessToken();

    // Analytics process
    $analytics = new Google_Service_Analytics($client);
    $conf_file_location = ROOT_DIR.'analytics/config/simple.php';
    $config = require_once $conf_file_location;
    $obj = $analytics->data_ga->get(
        "ga:{$conf['view_id']}",
        $from,
        $to,
        $config['metrics'],
        $config['option']
    );

    // csv output
    $fh = fopen(ROOT_DIR."analytics/files/{$from}_{$to}.csv", "w");
    foreach ($obj->rows as $value) {
        fputcsv($fh, $value);
    }
    fclose($fh);
}catch(apiServiceException $e){
    echo $e->getMessage();
}
