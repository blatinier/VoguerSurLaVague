<?php
class CategoryRepository extends Repository {
    public function __construct() {
        parent::__construct();
    }

    public function delete($cat_id) {
        $req = "DELETE FROM category WHERE id=%i";
        $this->mysql_connector->delete($req, $cat_id);
    }

    public function get_all($type=0) {
        $req = 'SELECT id, titre, slug, abstract, type
            FROM category
            WHERE type = '.(int)$type;
        $cat_sql = $this->mysql_connector->fetchAll($req);
        $categories = array();
        foreach($cat_sql as $cat_data) {
            $categories[] = Category::load($cat_data);
        }
        return $categories;
    }

    public function get_list_type_cat () {
        return array("Article", "Astuces", "Galerie");
    }

    public function get_by_id($id, $type=0) {
        $req = 'SELECT id, titre, slug, abstract, type
            FROM category
            WHERE type = '.(int)$type.'
                AND id = '.(int)$id;
        $cat_sql = $this->mysql_connector->fetchOne($req);
        $category = Category::load($cat_sql);
        return $category;
    }

    public function save($category) {
        if (!empty($category->id)) {
            $req = "UPDATE category SET
                    titre = %s,
                    slug = %s,
                    abstract = %s,
                    type = %d
                WHERE id = %d";
            $this->mysql_connector->update($req, $category->titre,
                $category->slug, $category->abstract, $category->type,
                $category->id);
        } else {
            $req = "INSERT INTO category(id, titre, slug, abstract, type)
                VALUES('', %s, %s, %s, %d)";
            $res = $this->mysql_connector->insert($req, $category->titre,
                $category->slug, $category->abstract, $category->type);
            $category->id = $res['insert_id'];
        }
        return $category;
    }
}
?>
