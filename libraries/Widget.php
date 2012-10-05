<?php
class Widget {
    protected $config;
    public $data;
    protected $mysql_connector;
    
    public function __construct($cfg, $mysql_connector=null) {
        $this->mysql_connector = $mysql_connector;
        // Instanciate database connection
        $this->config = $cfg;
        $this->data = null;
    }
    
    public function _do_($action_name) {
        return null;
    }
}
?>
