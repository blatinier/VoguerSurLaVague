<?php
class CategoryPage extends Controller {
    public $view;

    public function __construct($cfg, $mysql_connector=null) {
        parent::__construct($cfg, $mysql_connector);
    }

    public function del_cat () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if (!$admin) {
            header('Location: https://www.melmelboo.fr');
            die();
        }
        $cat_id = $this->_getParam('cat_id', 0);
        if (!empty($cat_id)) {
            $cat_repo = new CategoryRepository();
            $cat_repo->delete($cat_id);
        }
        header('Location: https://www.melmelboo.fr/list_cat');
        die();
    }

    public function new_cat () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if (!$admin) {
            header('Location: https://www.melmelboo.fr');
            die();
        }
        $cat_repo = new CategoryRepository();
        $this->view->cat = null;
        if (!empty($_GET['cat_id'])) {
            $cat = $cat_repo->get_by_id($_GET['cat_id']);
            $this->view->cat_titre = $cat->titre;
            $this->view->cat_type = $cat->type;
            $this->view->cat_abstract = $cat->abstract;
        }
        if (!empty($_POST)) {
            if (!empty($cat)) {
                $cat->titre = $_POST['titre'];
                $cat->slug = LibTools::sanitize_string($_POST['titre']);
                $cat->abstract = $_POST['abstract'];
                $cat->type = $_POST['type'];
                $this->view->msg = "La catégorie ".$_POST['titre']." a bien été mise à jour.";
            } else {
                $cat_values = array(
                    'titre' => $_POST['titre'],
                    'slug' => LibTools::sanitize_string($_POST['titre']),
                    'abstract' => $_POST['abstract'],
                    'type' => $_POST['type']);
                $cat = Category::load($cat_values);
                $this->view->msg = "La catégorie ".$_POST['titre']." a bien été créée.";
            }
            $cat = $cat_repo->save($cat);
        }
        $this->view->list_cat = $cat_repo->get_list_type_cat();
    }

    public function list_cat () {
        $cat_repo = new CategoryRepository();
        $this->view->list_cat = $cat_repo->get_all();
        $this->view->cat_type = $cat_repo->get_list_type_cat();
    }
}
?>
