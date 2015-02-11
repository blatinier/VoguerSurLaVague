<?php
class ArticlePage extends Controller {
    public $view;

    public function __construct($cfg, $mysql_connector=null) {
        parent::__construct($cfg, $mysql_connector);
    }

    public function article () {
        $art = $this->_getParam('art', 0);
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $art_repo = new ArticleRepository();
        $cats_repo = new CategoryRepository();
        $com_repo = new CommentRepository();
        $article = $art_repo->get_by_id($admin, $art);
        $next_article = $art_repo->get_next_by_id($admin, $art);
        $prev_article = $art_repo->get_previous_by_id($admin, $art);
        $article->category = $cats_repo->get_by_id($article->cat);
        $article->nb_likes = $art_repo->get_likes($article->id);
        $this->view->article = $article;
        $this->view->next_article = $next_article;
        $this->view->prev_article = $prev_article;
        $this->layout->title = $article->titre;
        $this->layout->canonical = $this->config['root_url']."/art-".$article->url."-".$article->id;
    }

    public function new_art () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $art_repo = new ArticleRepository();
        $tag_repo = new TagRepository();
        if ($admin && !empty($_POST)) {
            $tags = array();
            foreach($_POST as $field_name => $field_value) {
                if (strpos($field_name, 'tag_') === 0) {
                    $tags[] = (int)substr($field_name, 4);
                }
            }
            $art_id = !empty($_POST['art_id']) ? $_POST['art_id'] : null;
            $auteur = utf8_decode($_POST['auteur']);
            $titre = utf8_decode($_POST['titre']);
            $texte = utf8_decode($_POST['texte']);
            $cat = $_POST['cat'];
            $pub = $_POST['pub'];
            $art_values = array('id' => $art_id,
                                'auteur' => $auteur,
                                'titre' => $titre,
                                'url' => LibTools::sanitize_string($titre),
                                'texte' => $texte,
                                'pubdate' => $pub,
                                'cat' => $cat,
                                'captcha_com' => 0,
                                'closed_com' => 0);
            $art = Article::load($art_values);
            $art = $art_repo->save($art);
            $tag_repo->link_article_to_tags($art->id, $tags);
            $_POST['art_id'] = $art->id;
            $this->view->art_id = $art->id;
            $this->view->sent = true;
        }
        if (!empty($_GET['art_id'])) {
            $this->view->art_id = (int)$_GET['art_id'];
        }
        if (!empty($this->view->art_id)) {
            $art = $art_repo->get_by_id($admin, $this->view->art_id);
            $this->view->auteur = $art->auteur;
            $this->view->url = $art->url;
            $this->view->titre = $art->titre;
            $this->view->art = $art->texte;
            $this->view->cat = $art->cat;
            $this->view->pubdate = $art->pubdate;
            $tags_id = array();
            foreach ($art->get_tags() as $t) {
                $tags_id[] = $t->id;
            }
            $this->view->art_tags = $tags_id;
        }
        $this->view->tags = $tag_repo->get_all_sorted();
        $cat_repo = new CategoryRepository();
        $this->view->list_cat = $cat_repo->get_all();
        $this->view->now = date("Y-m-d H:i:s",time()+3600*24);
    }

    public function delete_art () {
        $this->view->deleted = false;
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if ($admin && !empty($_GET['art_id'])) {
            $art_id = (int)$_GET['art_id'];
            $art_repo = new ArticleRepository();
            $article = $art_repo->get_by_id($admin, $art_id);
            $this->view->art = $article->titre;
            if (!empty($_POST) && strtolower($_POST['confirmation']) == "oui") {
                $art_repo->delete($art_id);
                $this->view->deleted = true;
            }
        }
    }

    public function like () {
        $ip = $_SERVER['REMOTE_ADDR'];
        $id_article = (int)$_GET['art'];
        $error = false;
        $com_repo = new CommentRepository();
        $black_ip = $com_repo->get_banned_ip();
        foreach ($black_ip as $bip) {
            if ($bip['ip'] == $ip) {
                $error = true;
                $this->view->msg = "Votre adresse IP n'est pas autorisée à poster des commentaires.";
                break;
            }
        }
        if (!$error) {
            $art_repo = new ArticleRepository();
            $art_repo->like($id_article, $ip);
            $this->view->nb_likes = $art_repo->get_likes($id_article);
        }
        $this->layout_tpl = "ajax_html.phtml";
    }

    public function project52 () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $art_repo = new ArticleRepository();
        $tag_52_project = 45;
        $arts = $art_repo->get_tag_articles($admin, 0, $tag_52_project, 52);
        $this->view->images = array();
        foreach ($arts as $art) {
            preg_match_all('/<img[^>]+>/i', $art->texte, $result); 
            $img = $result[0][0];
            $text = str_replace($img, '', $art->texte);
            $this->view->images[] = array('image' => $img,
                                          'url' => $art->url,
                                          'id' => $art->id,
                                          'titre' => $art->titre,
                                          'text' => $text);
        }
    }

}
?>
