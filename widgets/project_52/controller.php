<?php
class project_52 extends Widget {
    public function __construct($cfg, $mysql_connector) {
        parent::__construct($cfg, $mysql_connector);
    }
    
    public function _do_($action_name) {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $art_repo = new ArticleRepository();
        $tag_52_project = 45;
        $current_year = intval(date("Y"));
        $this->data['articles_52'] = array();
        for ($y = $current_year; $y >= 2015; $y--) {
            $arts = $art_repo->get_tag_articles($admin, 0, $tag_52_project, 6, $y);
            foreach ($arts as $art) {
                preg_match_all('/<img[^>]+>/i',$art->texte, $result); 
                $art->texte = $result[0][0];
                if (empty($this->data['articles_52'][$y])) {
                    $this->data['articles_52'][$y] = array();
                }
                $this->data['articles_52'][$y][] = $art;
            }
        }
    }
}
