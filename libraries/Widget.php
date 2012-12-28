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
    
    public function _getPost($key) {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        return null;
    }
    
    public function _getGet($key) {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        return null;
    }
    
    public function _getParam($key, $default = null) {
        return isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $default);
    }
    
    public function _getParams($method = null) {
        $p = isset($_POST) ? $_POST : array();
        $g = isset($_GET) ? $_GET : array();
        if (!empty($method)) {
            if (strtolower($method) == 'post') {
                return $p;
            } elseif (strtolower($method) == 'get') {
                return $g;
            }
            return array();
        }
        return array_merge($g, $p);
    }
}
?>
