<?php
require 'vendor/autoload.php';

function compare_scored_articles($a, $b) {
    if ($a->score == $b->score) {
        return 0;
    }
    return ($a->score < $b->score) ? -1 : 1;
}

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
        $this->layout->aside_class= "hidden";
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
            $this->update_index_article($art);
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
                $this->delete_index_article($art_id);
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

    public function search () {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        if ($admin && $_POST['search_query'] == "REINDEX") {
            $this->re_index_all();
        }
        $this->view_tpl = "Home/main.phtml";
        $es_client = new Elasticsearch\Client();
        $search = str_replace('}', '',
                              str_replace('{', '',
                                          $_POST['search_query']));
        $get = array("index" => $this->config['es_index'],
                     "type" => "article");
        $get['body']['query']['multi_match']['query'] = $search;
        $get['body']['query']['multi_match']['fields'] = array("title", "body");
        $arts = $es_client->search($get);
        $ids = array();
        foreach ($arts['hits']['hits'] as $a) {
            $ids[] = (int)$a['_id'];
            $scores[(int)$a['_id']] = $a['_score'];
        }
        if (empty($ids)) {
            $this->view->articles = array();
            return;
        }
        $art_repo = new ArticleRepository();
        $articles = $art_repo->get_by_ids($admin, $ids);
        $com_repo = new CommentRepository();
        $cats_repo = new CategoryRepository();
        foreach ($articles as $art) {
            $art->category = $cats_repo->get_by_id($art->cat);
            $art->nb_comment = $com_repo->count($art->id);
            $art->nb_likes = $art_repo->get_likes($art->id);
            $art->score = $scores[$art->id];
        }
        $this->view->articles = $articles;
    }

    public function re_index_all() {
        $es_client = new Elasticsearch\Client();
        $indexParams['index'] = $this->config['es_index'];
        $es_client->indices()->delete($indexParams);
        $es_client->indices()->create($indexParams);

        $art_repo = new ArticleRepository();
        $articles = $art_repo->get_all(true);
        foreach ($articles as $a) {
            $this->index_article($a);
        }
    }
    
    public function update_index_article($art) {
        try {
            $this->delete_index_article($art->id);
        } catch( Exception $e) {
            error_log("Article ". $art->id . " not indexed yet");
        }
        $this->index_article($art);
    }
    
    public function index_article($art) {
        $es_client = new Elasticsearch\Client();
        $idx = array("index" => $this->config['es_index'],
                     "type" => "article",
                     "id" => $art->id,
                     "body" => array("title" => $art->titre,
                                     "body" => $art->texte));
        error_log('Indexing article '.$art->id);
        $es_client->index($idx);
    }
    
    public function delete_index_article($art_id) {
        $es_client = new Elasticsearch\Client();
        $idx = array("index" => $this->config['es_index'],
                     "type" => "article",
                     "id" => $art_id);
        $es_client->delete($idx);
    }
}
?>
