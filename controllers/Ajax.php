<?php
class Ajax extends Controller {
    public $view;

    public function __construct($cfg, $mysql_connector=null) {
        parent::__construct($cfg, $mysql_connector);
        $this->layout_tpl = "ajax_html.phtml";
    }

    public function get_months () {
        if (!empty($_POST['year'])) {
            $year = (int)$_POST['year'];
            $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
            $art_repo = new ArticleRepository();
            $months = $art_repo->get_months($admin, $year);
            $this->view->months = $months;
            $this->view->year = $year;
        } else {
            $this->view->months = "";
        }
    }
}
?>
