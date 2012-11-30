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
        if (!empty($_POST)) {
            $auteur = mysql_real_escape_string($_POST['auteur']);
            $titre = mysql_real_escape_string($_POST['titre']);
            $texte = mysql_real_escape_string($_POST['texte']);
            $cat = mysql_real_escape_string($_POST['cat']);
            $pub = mysql_real_escape_string($_POST['pub']);
            $is_diy = ($_POST['is_diy'] == 'on') ? 1 : 0;
            $art_values = array('id' => null,
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
            $art_repo->save($art);
        }
        $cat_repo = new CategoryRepository();
        $this->view->list_cat = $cat_repo->get_all();
        $this->view->now = date("Y-m-d H:i:s",time()+3600*24);
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
}
?>
