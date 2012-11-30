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
    public $is_diy;

    public function __construct($id, $auteur, $titre, $url, $texte, $pubdate, $cat, $captcha_com, $closed_com, $is_diy) {
        $this->id = $id;
        $this->auteur = $auteur;
        $this->titre = $titre;
        $this->url = $url;
        $this->texte = $texte;
        $this->pubdate = $pubdate;
        $this->cat = $cat;
        $this->captcha_com = $captcha_com;
        $this->closed_com = $closed_com;
        $this->is_diy = $is_diy;

        $pub_time = strtotime($pubdate);
        $this->post_date = date("d/m/Y", $pub_time);
        $this->ecart = time() - $pub_time;
    }

    public static function load($dict) {
        return new Article($dict['id'],
            $dict['auteur'],
            $dict['titre'],
            $dict['url'],
            $dict['texte'],
            $dict['pubdate'],
            $dict['cat'],
            $dict['captcha_com'],
            $dict['closed_com'],
            $dict['is_diy']);
    }
}
