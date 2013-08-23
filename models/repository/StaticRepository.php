<?php

class StaticRepository extends Repository {
    public function __construct() {
        parent::__construct();
    }

    public function get_about() {
        return $this->get_page("about");
    }

    public function get_links() {
        return $this->get_page("links");
    }

    public function get_page($page) {
        $req = 'SELECT content
            FROM static_pages
            WHERE name = "'.$page.'"';
        $content = $this->mysql_connector->fetchOne($req);
        return $content['content'];
    }

    public function save($page, $content) {
        if (!empty($page) && !empty($content)) {
            $req = "UPDATE static_pages SET
                        content = %s
                        WHERE name = %s";
            $this->mysql_connector->update($req, utf8_decode($content), $page);
        }
    }
}
?>
