<?php
ini_set('date.timezone', 'Asia/Tokyo');

define('ROOT_DIR', '/var/www/google/');
require_once ROOT_DIR.'google-api-php-client/vendor/autoload.php';
require_once ROOT_DIR.'analytics/class/MultiService.php';
require_once ROOT_DIR.'analytics/controller/Controller.php';

try{
    // argument validation
    $conf_file_location = $argv[1];
    if (!file_exists($conf_file_location)) throw new Exception("Error!! File does not exist.");
    $from = isset($argv[2])? $argv[2] : false;
    $to = isset($argv[3])? $argv[3] : false;

    // main process
    $controller = new Controller(new Google_Client(), $from, $to, $conf_file_location);
    $controller->anallytics(new Google_Service_Analytics_Multi_Service(
        $controller->client,
        $controller->conf,
        $controller->period
    ));
}catch(Exception $e){
    echo $e->getMessage();
}catch(apiServiceException $e){
    echo $e->getMessage();
}
