<?php

class TagRepository extends Repository {
    public function __construct() {
        parent::__construct();
    }

    public function get_by_id($id) {
        $req = 'SELECT id, name, slug
            FROM tags
            WHERE id = '.(int)$id;
        $sql = $this->mysql_connector->fetchOne($req);
        return Tag::load($sql);
    }

    public function get_tags_by_art($art_id) {
        $req = 'SELECT id, name, slug FROM tags t
                INNER JOIN article_tags at ON t.id = at.tag_id
                WHERE at.article_id = '.(int)$art_id;
        $art_sql = $this->mysql_connector->fetchAll($req);
        $tags = array();
        foreach($art_sql as $tag_data) {
            $tags[] = Tag::load($tag_data);
        }
        return $tags;
    }

    public function get_all_with_count() {
        $req = 'SELECT id, name, slug, COUNT(*) AS nb
        FROM tags t
        LEFT JOIN article_tags at
        ON t.id = at.tag_id
        GROUP BY t.id';
        $art_sql = $this->mysql_connector->fetchAll($req);
        $tags = array();
        foreach($art_sql as $tag_data) {
            $tags[] = Tag::load($tag_data);
        }
        return $tags;
    }

    public function get_all() {
        $req = 'SELECT id, name, slug FROM tags';
        $art_sql = $this->mysql_connector->fetchAll($req);
        $tags = array();
        foreach($art_sql as $tag_data) {
            $tags[] = Tag::load($tag_data);
        }
        return $tags;
    }

    public function link_article_to_tags($art_id, $tags_id){
        $req = "INSERT INTO article_tags(tag_id, article_id) VALUES";
        $values = array();
        foreach ($tags_id as $tid) {
            $values[] = "(" . (int)$tid . ", " . (int)$art_id . ")";
        }
        $req .= implode(', ', $values) . " ON DUPLICATE KEY UPDATE tag_id=tag_id";
        $this->mysql_connector->insert($req);
    }

    public function save($tag) {
        if (!empty($tag->id)) {
            $req = "UPDATE tags SET
                        name = %s,
                        slug = %s
                WHERE id = %d";
            $this->mysql_connector->update($req, $tag->name, $tag->slug, $tag->id);
        } else {
            $req = "INSERT INTO tags
                        (id, name, slug)
                    VALUES('', %s, %s)";
            $res = $this->mysql_connector->insert($req, $tag->name, $tag->slug);
            $tag->id = $res['insert_id'];
        }
        return $tag;
    }

    public function delete($tag_id) {
        $req = "DELETE FROM article_tags WHERE tag_id = %d";
        $this->mysql_connector->delete($req, $tag_id);
        $req = "DELETE FROM tags WHERE id = %d";
        $this->mysql_connector->delete($req, $tag_id);
    }
}
?>
