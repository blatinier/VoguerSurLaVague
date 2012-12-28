<?php
class new_comment extends Widget {
    public function __construct($cfg, $mysql_connector) {
        parent::__construct($cfg, $mysql_connector);
    }
    
    public function _do_($action_name) {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $art = $this->_getParam('art', 0);
        if ($art != 0) {
            if (!empty($_POST)) {
                $com_repo = new CommentRepository();
                $site = null;
                if (substr($_POST['site'], 0, 7) == 'http://') {
                    $site = $_POST['site'];
                }
                $com_repo->add($art, $_POST['pseudo'],
                    $_POST['commentaire'], $site, $_SERVER['REMOTE_ADDR']);
            }
            $art_repo = new ArticleRepository();
            $article = $art_repo->get_by_id($admin, $art);
            $this->data['closed_com'] = $article->closed_com;
            $this->data['captcha_com'] = $article->captcha_com;
        }
    }
}
