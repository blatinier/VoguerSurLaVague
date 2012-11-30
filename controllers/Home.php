<?php
class Home extends Controller {
    public $view;

    public function __construct($cfg, $mysql_connector=null) {
        parent::__construct($cfg, $mysql_connector);
    }

    public function diy () {
        $cat_name = $this->mysql_connector->fetchOne("
            SELECT
                c.name
                FROM category c
            WHERE c.id = %d
            ", (int)$_GET['cid']);
        $this->view->cat_name = $cat_name['name'];
        $this->layout->title = $cat_name['name'];
        $actors = $this->mysql_connector->fetchAll("
            SELECT
                a.id, a.name, a.slug, a.address, a.postal_code, a.town, a.email, a.phone, 
                a.description, a.location_id, a.latitude, a.longitude, a.timetable, a.valid, 
                a.open_com, a.prefered_category_id, a.data,

                l.name AS city_name,
                l_dep.name AS dept_name,
                l_reg.name AS region_name
            FROM actor a
            LEFT JOIN actor_category ac ON ac.actor_id = a.id
            LEFT JOIN locations l ON l.id = a.location_id
            LEFT JOIN locations l_dep ON l_dep.id = l.parent_id
            LEFT JOIN locations l_reg ON l_reg.id = l_dep.parent_id
            WHERE ac.category_id = %d
            ", (int)$_GET['cid']);
        $ordered_actors = array();
        foreach ($actors as $a){
            if (array_key_exists($a['region_name'], $ordered_actors)) {
                $ordered_actors[$a['region_name']][] = $a;
            } else {
                $ordered_actors[$a['region_name']] = array($a);
            }
        }
        $this->view->actors = $ordered_actors;
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
        }
        $this->view->nb_pages = $art_repo->page_count($admin);
        $this->view->articles = $articles;
    }
}
?>
