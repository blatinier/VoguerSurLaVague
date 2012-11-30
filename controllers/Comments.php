<?php
class Comments extends Controller {
    public $view;

    public function __construct($cfg, $mysql_connector=null) {
        parent::__construct($cfg, $mysql_connector);
    }

    public function last_unread () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if (!$admin) {
            header('Location: http://www.melmelboo.fr');
            die();
        }
        $com_repo = new CommentRepository();
        $art_repo = new ArticleRepository();
        $comments = $com_repo->get_last_unread();
        foreach ($comments as &$com) {
            $com['article'] = $art_repo->get_by_id($admin, $com['idarticle']);
            $com['com'] = $com_repo->get_by_id($com['idcom']);
        }
        $this->view->comments = $comments;
    }

    public function mark_read () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if (!$admin) {
            header('Location: http://www.melmelboo.fr');
            die();
        }
        $com_id = $this->_getParam('com_id', 0);
        $com_repo = new CommentRepository();
        $com_repo->mark_read($com_id);
        header('Location: http://www.melmelboo.fr/admin');
        die();
    }

    public function mark_all_read () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if (!$admin) {
            header('Location: http://www.melmelboo.fr');
            die();
        }
        $com_repo = new CommentRepository();
        $com_repo->mark_all_read();
        header('Location: http://www.melmelboo.fr/admin');
        die();
    }
}
?>
