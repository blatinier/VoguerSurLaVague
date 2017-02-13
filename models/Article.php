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

    public function __construct($id, $auteur, $titre, $url, $texte, $pubdate, $cat, $captcha_com, $closed_com, $ghost_id, $canonical_url) {
        $MONTH = array(
            1 => "Janvier",
            2 => "Février",
            3 => "Mars",
            4 => "Avril",
            5 => "Mai",
            6 => "Juin",
            7 => "Juillet",
            8 => "Août",
            9 => "Septembre",
            10 => "Octobre",
            11 => "Novembre",
            12 => "Décembre");
        $this->id = $id;
        $this->auteur = $auteur;
        $this->titre = $titre;
        $this->url = $url;
        $this->texte = $texte;
        $this->pubdate = $pubdate;
        $this->cat = $cat;
        $this->captcha_com = $captcha_com;
        $this->closed_com = $closed_com;
        $this->ghost_id = $ghost_id;
        $this->canonical_url = $canonical_url;

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
            $dict['closed_com'],
            $dict['ghost_id'],
            $dict['canonical_url']);
    }

    public function get_tags() {
        $tr = new TagRepository();
        return $tr->get_tags_by_art($this->id);
    }

    public function get_top_image() {
        preg_match_all('/<img class="top_image"[^>]+>/i',$this->texte, $result);
        if (!empty($result[0])) {
            return $result[0][0];
        }
        preg_match_all('/<img [^>]+>/i',$this->texte, $result);
        return $result[0][0];
    }

    public function art_abstract() {
        $numb = 180;
        $abstract = strip_tags($this->texte);
        if (strlen($abstract) > $numb) {
            $abstract = substr($abstract, 0, $numb);
            $abstract = substr($abstract, 0, strrpos($abstract, " "));
            $etc = " ...";
            $abstract = $abstract . $etc;
        }
        return $abstract;
    }
}
