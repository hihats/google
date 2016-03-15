<?php
class Google_Service_Analytics_Multi_Service extends Google_Service_Analytics {
    public $option;
    public $metrics;
    public $period;
    public function __construct($client, $conf_file_location, $period=array()){
        parent::__construct($client);
        $this->loadParam($conf_file_location);
        $this->setPeriod($period);
    }
    public function loadParam($conf_file_location){
        $conf = require $conf_file_location;
        $this->name = basename($conf_file_location, ".php");
        $this->view_ids = $conf["view_ids"];
        $this->metrics = $conf["metrics"];
        $this->option = $conf["option"];
    }
    public function gaDataGet($view_id){
        $obj = $this->data_ga->get(
            "ga:{$view_id}",
            $this->period['from'],
            $this->period['to'],
            $this->metrics,
            $this->option
        );
        return $obj;
    }
    public function setPeriod($period){
      $this->period['from'] = $this->period['to'] = date("Y-m-d", strtotime('-1 day'));
      if(isset($period['from']) && $period['from']) $this->period['from'] = $period['from'];
      if(isset($period['to']) && $period['to']) $this->period['to'] = $period['to'];
    }
}
