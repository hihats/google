<?php
/*
 * Automatic data acqusition from GA and output csv file
 * @param string $argv[1] [*required] file location path
 * @param date $argv[2] [Y-m-d] from date
 * @param date $argv[3] [Y-m-d] to date
 * @description must place configuration file at config/***.php
 */

ini_set('date.timezone', 'Asia/Tokyo');

define('ROOT_DIR', '/var/www/google/');
define('PJ_DIR', '/var/www/google/analytics/');
require_once ROOT_DIR.'google-api-php-client/vendor/autoload.php';
require_once PJ_DIR.'class/MultiService.php';
require_once PJ_DIR.'controller/Controller.php';

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
    echo '['.date('Y-m-d h:i:s').']' . $e->getMessage();
}catch(apiServiceException $e){
    echo '['.date('Y-m-d h:i:s').']' . $e->getMessage();
}
