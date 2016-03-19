<?php
class Google_Service_Analytics_Multi_Service extends Google_Service_Analytics {
    public $option;
    public $metrics;
    public $period;
    public $conf_name;
    public function __construct($client, $conf_file_location, $period=array()){
        parent::__construct($client);
        $this->setParam($conf_file_location);
        $this->setPeriod($period);
    }
    public function setParam($conf){
        $this->view_ids = $conf["view_ids"];
        $this->metrics = $conf["metrics"];
        $this->option = $conf["option"];
    }
    public function setPeriod($period){
      if(isset($period['from']) && $period['from']) $this->period['from'] = $period['from'];
      if(isset($period['to']) && $period['to']) $this->period['to'] = $period['to'];
    }
    public function gaDataGet($view_id){
        return $this->data_ga->get(
            "ga:{$view_id}",
            $this->period['from'],
            $this->period['to'],
            $this->metrics,
            $this->option
        );
    }
}
