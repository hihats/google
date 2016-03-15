<?php
require_once ROOT_DIR.'analytics/class/MultiService.php';
class Controller {
    public $client;
    public function __construct(Google_Client $client){
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
        $this->client = $client;
    }
    /* Analytics process */
    public function anallytics(Google_Service_Analytics_Multi_Service $analytics){
        $res = array_map( function($view_id) use(&$analytics) {
            $obj = $analytics->gaDataGet($view_id);
            $filename = "{$analytics->name}_{$analytics->period['from']}_{$analytics->period['to']}.csv";
            $this->output_csv($obj->rows, ROOT_DIR."analytics/files/{$view_id}/{$filename}");
        }, $analytics->view_ids);
    }
    public function output_csv($rows, $filepath){
        $fh = fopen($filepath, "w");
        foreach ($rows as $value) {
            fputcsv($fh, $value);
        }
        fclose($fh);
    }
}
