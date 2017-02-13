<?php
class new_comment extends Widget {
    public function __construct($cfg, $mysql_connector) {
        parent::__construct($cfg, $mysql_connector);
    }

    public function _do_($action_name) {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $art = $this->_getParam('art', 0);
        $this->data['closed_com'] = true;
        if ($art != 0) {
            $art_repo = new ArticleRepository();
            $article = $art_repo->get_by_id($admin, $art);
            $this->data['closed_com'] = $article->closed_com;
            $this->data['title'] = $article->titre;
            if (!empty($article->ghost_id)) {
                $this->data['isso_id'] = "voyage-".$article->ghost_id;
            } else {
                $this->data['isso_id'] = "melmelboo-".$article->id;
            }
        }
    }
}
