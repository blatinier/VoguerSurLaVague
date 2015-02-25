<?php

class Article extends Model {

    public $id;
    public $auteur;
    public $titre;
    public $url;
    public $texte;
    public $pubdate;
    public $cat;
    public $captcha_com;
    public $closed_com;

    public function __construct($id, $auteur, $titre, $url, $texte, $pubdate, $cat, $captcha_com, $closed_com) {
        $MONTH = array(
            1 => "Janv",
            2 => "Fév",
            3 => "Mars",
            4 => "Avril",
            5 => "Mai",
            6 => "Juin",
            7 => "Juil",
            8 => "Août",
            9 => "Sept",
            10 => "Oct",
            11 => "Nov",
            12 => "Déc");
        $this->id = $id;
        $this->auteur = $auteur;
        $this->titre = $titre;
        $this->url = $url;
        $this->texte = $texte;
        $this->pubdate = $pubdate;
        $this->cat = $cat;
        $this->captcha_com = $captcha_com;
        $this->closed_com = $closed_com;

        $pub_time = strtotime($pubdate);
        $this->post_date = date("d/m/Y", $pub_time);
        $this->ecart = time() - $pub_time;
        $this->day = date("j", $pub_time);
        $this->month = $MONTH[intval(date("n", $pub_time))];
        $this->year = date("Y", $pub_time);
    }

    public static function load($dict) {
        if (empty($dict)) {
            return null;
        }
        return new Article($dict['id'],
            $dict['auteur'],
            $dict['titre'],
            $dict['url'],
            $dict['texte'],
            $dict['pubdate'],
            $dict['cat'],
            $dict['captcha_com'],
            $dict['closed_com']);
    }

    public function get_tags() {
        $tr = new TagRepository();
        return $tr->get_tags_by_art($this->id);
    }
}
