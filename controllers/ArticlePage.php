<?php
class ArticlePage extends Controller {
    public $view;

    public function __construct($cfg, $mysql_connector=null) {
        parent::__construct($cfg, $mysql_connector);
    }

    public function article () {
        $art = $this->_getParam('art', 0);
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $art_repo = new ArticleRepository();
        $cats_repo = new CategoryRepository();
        $com_repo = new CommentRepository();
        $article = $art_repo->get_by_id($admin, $art);
        $article->category = $cats_repo->get_by_id($article->cat);
        $this->view->article = $article;
    }

    public function new_art () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if ($admin && !empty($_POST)) {
            $art_id = $_POST['art_id'];
            $auteur = utf8_decode($_POST['auteur']);
            $titre = utf8_decode($_POST['titre']);
            $texte = utf8_decode($_POST['texte']);
            $cat = $_POST['cat'];
            $pub = $_POST['pub'];
            $is_diy = ($_POST['is_diy'] == 'on') ? 1 : 0;
            $art_values = array('id' => $art_id,
                                'auteur' => $auteur,
                                'titre' => $titre,
                                'url' => LibTools::sanitize_string($titre),
                                'texte' => $texte,
                                'pubdate' => $pub,
                                'cat' => $cat,
                                'captcha_com' => 0,
                                'closed_com' => 0,
                                'is_diy' => $is_diy);
            $art = Article::load($art_values);
            $art_repo = new ArticleRepository();
            $art = $art_repo->save($art);
            $_POST['art_id'] = $art->id;
            $_SESSION['auteur'] = utf8_encode($auteur);
            $_SESSION['titre'] = utf8_encode($titre);
            $_SESSION['art'] = utf8_encode($texte);
            $_SESSION['cat'] = $cat;
            $_SESSION['pub'] = $pub;
            $_SESSION['is_diy'] = $is_diy;
        }
        $cat_repo = new CategoryRepository();
        $this->view->list_cat = $cat_repo->get_all();
        $this->view->now = date("Y-m-d H:i:s",time()+3600*24);
        if (empty($this->view->art_id) && !empty($_POST['art_id'])) {
            $this->view->art_id = (int)$_POST['art_id'];
        } elseif (empty($this->view->art_id) && !empty($_GET['art_id'])) {
            $this->view->art_id = (int)$_GET['art_id'];
        }
        if (empty($this->view->auteur) && !empty($_SESSION['auteur'])) {
            $this->view->auteur = $_SESSION['auteur'];
        }
        if (empty($this->view->titre) && !empty($_SESSION['titre'])) {
            $this->view->titre = $_SESSION['titre'];
        }
        if (empty($this->view->art) && !empty($_SESSION['art'])) {
            $this->view->art = $_SESSION['art'];
        }
        if (empty($this->view->cat) && !empty($_SESSION['cat'])) {
            $this->view->cat = $_SESSION['cat'];
        }
        if (empty($this->view->is_diy) && !empty($_SESSION['is_diy'])) {
            $this->view->is_diy = $_SESSION['is_diy'];
        }
    }

    public function delete_art () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if ($admin && !empty($_GET['art_id'])) {
            $art_id = (int)$_GET['art_id'];
            $art_repo = new ArticleRepository();
            $article = $art_repo->get_by_id($admin, $art_id);
            $this->view->art = $article->titre;
            $art_repo->delete($art_id);
        }
    }
}
?>