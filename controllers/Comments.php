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

    public function ban_ip () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if (!$admin) {
            header('Location: http://www.melmelboo.fr');
            die();
        }
        $com_repo = new CommentRepository();
        $com_repo->ban_ip($this->_getParam('ip'));
        $com_repo->clean_banned();
        header('Location: http://www.melmelboo.fr/last_comments');
        die();
    }

    public function delete () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if (!$admin) {
            header('Location: http://www.melmelboo.fr');
            die();
        }
        $com_id = $this->_getParam('com_id', 0);
        $com_repo = new CommentRepository();
        $art_repo = new ArticleRepository();
        $com = $com_repo->get_by_id($com_id);
        $article = $art_repo->get_by_id($admin, $com['idarticle']);
        $com_repo->mark_read($com_id);
        $com_repo->delete($com_id);
        header('Location: http://www.melmelboo.fr/art-'.$article->url.'-'.$article->id);
        die();
    }

    public function mark_read () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if (!$admin) {
            header('Location: http://www.melmelboo.fr');
            die();
        }
        $com_id = $this->_getParam('com_id', 0);
        $art_id = $this->_getParam('art_id', 0);
        $com_repo = new CommentRepository();
        $com_repo->mark_read($com_id);
        $art_repo = new ArticleRepository();
        $article = $art_repo->get_by_id($admin, $art_id);
        header('Location: http://www.melmelboo.fr/art-'.$article->url.'-'.$art_id);
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
        header('Location: http://www.melmelboo.fr/last_comments');
        die();
    }
}
?>
