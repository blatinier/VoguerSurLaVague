<?php
class project_52 extends Widget {
    public function __construct($cfg, $mysql_connector) {
        parent::__construct($cfg, $mysql_connector);
    }
    
    public function _do_($action_name) {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $art_repo = new ArticleRepository();
        $this->data['articles_52'] = array();
        $current_year = intval(date("Y"));
        for ($y = $current_year; $y >= 2015; $y--) {
            $this->data['articles_52'][$y] = $this->project_52_arts($admin, $y);
        }
        $this->data['articles_months'] = $this->project_months($admin);
    }

    public function project_months($admin) {
        $tag = 47;
        $art_repo = new ArticleRepository();
        $arts = $art_repo->get_tag_articles($admin, 0, $tag, 6);
        $ret_arts = array();
        foreach ($arts as $art) {
            $art->texte = $art->get_top_image();
            $ret_arts[] = $art;
        }
        return $ret_arts;
    }

    public function project_52_arts($admin, $year) {
        $tag_52_project = 45;
        $art_repo = new ArticleRepository();
        $arts = $art_repo->get_tag_articles($admin, 0, $tag_52_project, 6, $year);
        $ret_arts = array();
        foreach ($arts as $art) {
            preg_match_all('/<img[^>]+>/i',$art->texte, $result); 
            $art->texte = $result[0][0];
            $ret_arts[] = $art;
        }
        return $ret_arts;
    }
}
