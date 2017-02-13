<?php
class TagManager extends Controller {
    public $view;

    public function __construct($cfg, $mysql_connector=null) {
        parent::__construct($cfg, $mysql_connector);
    }
    public function del_tag () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if (!$admin) {
            header('Location: https://www.melmelboo.fr');
            die();
        }
        $tag_id = $this->_getParam('tag_id', 0);
        if (!empty($tag_id)) {
            $tag_repo = new TagRepository();
            $tag_repo->delete($tag_id);
        }
        header('Location: https://www.melmelboo.fr/tag_management');
        die();
    }

    public function edit_tag () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if (!$admin) {
            header('Location: https://www.melmelboo.fr');
            die();
        }
        $tag_id = $this->_getParam('tag_id', 0);
        if (!empty($tag_id)) {
            $tag_repo = new TagRepository();
            $tag = $tag_repo->get_by_id($tag_id);
            if (!empty($_POST)) {
                $tag->name = $_POST['name'];
                $tag->slug = LibTools::sanitize_string(utf8_decode($_POST['name']));
                $tag_repo->save($tag);
                header('Location: https://www.melmelboo.fr/tag_management');
                die();
            } else {
                $this->view->tag = $tag;
            }
        } else {
            header('Location: https://www.melmelboo.fr/tag_management');
            die();
        }
    }

    public function new_tag () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if (!$admin) {
            header('Location: https://www.melmelboo.fr');
            die();
        }
        if (!empty($_POST)) {
            $tag_values = array(
                'name' => $_POST['name'],
                'slug' => LibTools::sanitize_string(utf8_decode($_POST['name'])));
            $tag = Tag::load($tag_values);
            $tag_repo = new TagRepository();
            $tag_repo->save($tag);
        }
        header('Location: https://www.melmelboo.fr/tag_management');
        die();
    }

    public function management () {
        $tag_repo = new TagRepository();
        $this->view->list_tag = $tag_repo->get_all();
    }
}
?>
