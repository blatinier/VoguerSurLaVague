<?php
class last_art extends Widget {
    public function __construct($cfg, $mysql_connector) {
        parent::__construct($cfg, $mysql_connector);
    }
    
    public function _do_($action_name) {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $art_repo = new ArticleRepository();
        $articles = $art_repo->get_articles($admin, 1);
        $this->data['last_art'] = $articles;
    }
}
