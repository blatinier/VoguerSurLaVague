<?php
class Listing extends Controller {
    public $view;

    public function __construct($cfg, $mysql_connector=null) {
        parent::__construct($cfg, $mysql_connector);
    }

    public function month () {
        $this->view_tpl = 'Home/main.phtml';
        $month = $this->_getParam('month', 0);
        $year = $this->_getParam('year', 0);
        $page = $this->_getParam('page', 0);
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $art_repo = new ArticleRepository();
        $com_repo = new CommentRepository();
        $cats_repo = new CategoryRepository();
        $articles = $art_repo->get_months_articles($admin, $year, $month, $page);
        foreach ($articles as $art) {
            $art->category = $cats_repo->get_by_id($art->cat);
            $art->nb_comment = $com_repo->count($art->id);
        }
        $this->view->nb_pages = $art_repo->page_count($admin, $year, $month);
        $this->view->articles = $articles;
    }

    public function category () {
        $this->view_tpl = 'Home/main.phtml';
        $page = $this->_getParam('page', 0);
        $category_id = $this->_getParam('category_id', 0);
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $art_repo = new ArticleRepository();
        $com_repo = new CommentRepository();
        $cats_repo = new CategoryRepository();
        $category = $cats_repo->get_by_id($category_id);
        $articles = $art_repo->get_category_articles($admin, $page, $category_id);
        foreach ($articles as $art) {
            $art->category = $category;
            $art->nb_comment = $com_repo->count($art->id);
        }
        $this->view->nb_pages = $art_repo->page_count_category($admin, $category_id);
        $this->view->articles = $articles;
        $this->view->std_page_link = "cat-".$category->slug."-".$category_id;
    }
}
?>
