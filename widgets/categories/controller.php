<?php
class categories extends Widget {
    public function __construct($cfg, $mysql_connector) {
        parent::__construct($cfg, $mysql_connector);
    }
    
    public function _do_($action_name) {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $cat_repo = new CategoryRepository();
        $cats = $cat_repo->get_all();
        $this->data['categories'] = $cats;
    }
}
