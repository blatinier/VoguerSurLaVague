<?php
class archives extends Widget {
    public function __construct($cfg, $mysql_connector) {
        parent::__construct($cfg, $mysql_connector);
    }
    
    public function _do_($action_name) {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $art_repo = new ArticleRepository();
        $years = $art_repo->get_years($admin);
        $this->data['archives'] = $years;
    }
}
