<?php
class Controller {
    protected $config;
    public $view;
    public $layout;
    public $layout_tpl;
    protected $mysql_connector;
    
    public function __construct($cfg, $mysql_connector=null) {
        $this->mysql_connector = $mysql_connector;
        // Instanciate database connection
        $this->config = $cfg;
        $this->view = null;
        $this->speedmsg = array();
        $this->layout = null;
        $this->layout->keywords = array();
        $this->layout_tpl = null;
    }
    
    public function _do_($actionName) {
        return call_user_func(array($this, $actionName));
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
