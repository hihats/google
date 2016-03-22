<?php
ini_set('date.timezone', 'Asia/Tokyo');
define('ROOT_DIR', '/var/www/google/');
require_once ROOT_DIR.'google-api-php-client/vendor/autoload.php';
require_once ROOT_DIR.'analytics/controller/Controller.php';

$func = function($view_id, $conf_name, $period){
    $table_arr = [26175612 => 'fact_inbound_url', 107256671 => 'fact_labo_inbound_url'];
    $columns = [
        'desktop' => ['url','time_id','medium','device_category','pageviews','sessions','users','conversion','conversion_dl','bounce_rate'],
        'others_device' => ['url','time_id','medium','device_category','mobile_device_info','pageviews','sessions','users','conversion','conversion_dl','bounce_rate']
    ];
    $column = implode(',', $columns[$conf_name]);
    return "COPY it_trend.{$table_arr[$view_id]}
     ({$column})
      FROM 's3://it-trend.jp/analytics/daily/{$view_id}/{$conf_name}_{$period['from']}_{$period['from']}.csv'
     credentials 'aws_access_key_id=AKIAJY4XTHMXDAF7ZYAQ;aws_secret_access_key=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' csv;";
};

try{
    // argument validation
    $conf_file_location = $argv[1];
    if (!file_exists($conf_file_location)) throw new Exception("Error!! File does not exist.");
    $from = isset($argv[2])? $argv[2] : false;
    $to = isset($argv[3])? $argv[3] : false;

    // main process
    $controller = new Controller(new Google_Client(), $from, $to, $conf_file_location);
    $period = $controller->period;
    $rows = [];
    if(!$to) $to = $period['to'];
    while($period['from'] <= $to){
        foreach ($controller->conf["view_ids"] as $view_id) {
            $rows[] = $func($view_id, $controller->conf_name, $period);
        }
        $period['from'] = $period['to'] = date('Y-m-d', strtotime("{$period['from']} +1 day"));
    }
    echo implode(PHP_EOL, $rows).PHP_EOL;
}catch(Exception $e){
    echo $e->getMessage();
}
function make_dim_time_row($period){
    $f = $period['from'];
    $ts = strtotime($f);
    $holiday = (date('N',$ts) >=6)? 1:0;
    return [date('Ymd', $ts), $f, date('d', $ts), date('N', $ts), date('n',$ts),date('Y',$ts),$holiday];
}
