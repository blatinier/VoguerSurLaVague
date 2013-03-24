<?php
class Home extends Controller {
    public $view;

    public function __construct($cfg, $mysql_connector=null) {
        parent::__construct($cfg, $mysql_connector);
    }

    public function diy () {
        $this->layout->title = "DIY";
        $this->layout->keywords[] = "diy";
    }

    public function main () {
        if (!empty($_GET['page'])) {
            $page = (int)$_GET['page'];
        } else {
            $page = 0;
        }
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $com_repo = new CommentRepository();
        $cats_repo = new CategoryRepository();
        $art_repo = new ArticleRepository();
        $articles = $art_repo->get_articles($admin, $page);
        foreach ($articles as $art) {
            $art->category = $cats_repo->get_by_id($art->cat);
            $art->nb_comment = $com_repo->count($art->id);
            $art->nb_likes = $art_repo->get_likes($art->id);
        }
        $this->view->nb_pages = $art_repo->page_count($admin);
        $this->view->articles = $articles;
    }
}
?>
