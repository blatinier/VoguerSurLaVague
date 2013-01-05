<?php
class CategoryRepository extends Repository {
    public function __construct() {
        parent::__construct();
    }

    public function get_all($type=0) {
        $req = 'SELECT id, titre, slug, abstract, type
            FROM voguer_cat
            WHERE type = '.(int)$type;
        $cat_sql = $this->mysql_connector->fetchAll($req);
        $categories = array();
        foreach($cat_sql as $cat_data) {
            $categories[] = Category::load($cat_data);
        }
        return $categories;
    }

    public function get_by_id($id, $type=0) {
        $req = 'SELECT id, titre, slug, abstract, type
            FROM voguer_cat
            WHERE type = '.(int)$type.'
                AND id = '.(int)$id;
        $cat_sql = $this->mysql_connector->fetchOne($req);
        $category = Category::load($cat_sql);
        return $category;
    }
}
?>
