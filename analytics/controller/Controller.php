<?php
class Controller {
    public $client;
    public $period;
    public function __construct(Google_Client $client, $from, $to, $conf_file_location){
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
        $this->setPeriod($from, $to, strtotime('-1 day')); // yesterday
        $this->loadConfig($conf_file_location);
    }
    public function loadConfig($conf_file_location){
        $this->conf = require $conf_file_location;
        $this->conf_name = basename($conf_file_location, ".php");
    }
    public function setPeriod($from, $to, $default=false){
        if(!$default) $default = time();
        $this->period['from'] = $this->period['to'] = date('Y-m-d', $default);
        if($from) $this->period['from'] = $from;
        if($to) $this->period['to'] = $to;
    }
    /* Analytics process */
    public function anallytics(Google_Service_Analytics_Multi_Service $analytics){
        $res = array_map( function($view_id) use(&$analytics) {
            $obj = $analytics->gaDataGet($view_id);
            $filename = "{$this->conf_name}_{$this->period['from']}_{$this->period['to']}.csv";
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
