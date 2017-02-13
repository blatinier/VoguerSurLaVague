<?php

class ArticleRepository extends Repository {
    public function __construct() {
        parent::__construct();
        $this->nb_articles_per_page = 6;
    }

    public function get_by_id($is_admin, $id) {
        $req = 'SELECT id, auteur, titre, url, texte,
            pubdate, cat, captcha_com, closed_com,
            ghost_id, canonical_url
            FROM articles
            WHERE id = '.(int)$id;
        if (!$is_admin) {
            $req .= ' AND pubdate < NOW() ';
        }
        $art_sql = $this->mysql_connector->fetchOne($req);
        $article = Article::load($art_sql);
        return $article;
    }

    public function get_by_ids($is_admin, $ids) {
        $req = 'SELECT id, auteur, titre, url, texte,
            pubdate, cat, captcha_com, closed_com,
            ghost_id, canonical_url
            FROM articles
            WHERE id IN ('.implode(",", $ids).')';
        if (!$is_admin) {
            $req .= ' AND pubdate < NOW() ';
        }
        $req .= ' ORDER BY pubdate DESC';
        $art_sql = $this->mysql_connector->fetchAll($req);
        $articles = array();
        foreach($art_sql as $article_data) {
            $articles[] = Article::load($article_data);
        }
        return $articles;
    }

    public function get_all($is_admin) {
        $req = 'SELECT id, auteur, titre, url, texte,
            pubdate, cat, captcha_com, closed_com,
            ghost_id, canonical_url
            FROM articles';
        $where = array();
        if (!$is_admin) {
            $where[] = 'pubdate < NOW()';
        }
        if (!empty($where)) {
            $req .= " WHERE ".implode(" AND ", $where);
        }
        $art_sql = $this->mysql_connector->fetchAll($req);
        $articles = array();
        foreach($art_sql as $article_data) {
            $articles[] = Article::load($article_data);
        }
        return $articles;
    }

    public function get_next($is_admin, $art) {
        $req = 'SELECT id, auteur, titre, url, texte,
            pubdate, cat, captcha_com, closed_com,
            ghost_id, canonical_url
            FROM articles
            WHERE pubdate > "'.$art->pubdate.'"';
        if (!$is_admin) {
            $req .= ' AND pubdate < NOW() ';
        }
        $req .= ' ORDER BY pubdate ';
        $art_sql = $this->mysql_connector->fetchOne($req);
        $article = Article::load($art_sql);
        return $article;
    }

    public function get_previous($is_admin, $art) {
        $req = 'SELECT id, auteur, titre, url, texte,
            pubdate, cat, captcha_com, closed_com,
            ghost_id, canonical_url
            FROM articles
            WHERE pubdate < "'.$art->pubdate.'"';
        if (!$is_admin) {
            $req .= ' AND pubdate < NOW() ';
        }
        $req .= ' ORDER BY pubdate DESC ';
        $art_sql = $this->mysql_connector->fetchOne($req);
        $article = Article::load($art_sql);
        return $article;
    }

    public function get_articles($is_admin, $page) {
        $offset = $page * $this->nb_articles_per_page;
        $req = 'SELECT id, auteur, titre, url, texte,
            pubdate, cat, captcha_com, closed_com,
            ghost_id, canonical_url
            FROM articles';
        $where = array();
        if (!$is_admin) {
            $where[] = 'pubdate < NOW()';
        }
        if (!empty($where)) {
            $req .= " WHERE ".implode(" AND ", $where);
        }
        $req .= ' ORDER BY pubdate DESC LIMIT '.$offset.', '.$this->nb_articles_per_page;
        $art_sql = $this->mysql_connector->fetchAll($req);
        $articles = array();
        foreach($art_sql as $article_data) {
            $articles[] = Article::load($article_data);
        }
        return $articles;
    }

    public function count($is_admin, $year=false, $month=false, $category_id=false, $tag_id=false) {
        $req = 'SELECT COUNT(*) as cpt
            FROM articles a ';
        $where = array();
        if (!$is_admin) {
            $where[] = 'pubdate < NOW()';
        }
        if ($year) {
            $where[] = 'YEAR(pubdate) = '.(int)$year;
        }
        if ($month) {
            $where[] = 'MONTH(pubdate) = '.(int)$month;
        }
        if ($tag_id) {
            $req .= " LEFT JOIN article_tags at
            ON a.id = at.article_id";
            $where[] = "at.tag_id = ". (int)$tag_id;
        }
        if ($category_id) {
            $where[] = 'cat = '.(int)$category_id;
        }
        if (!empty($where)) {
            $req .= " WHERE ".implode(" AND ", $where);
        }
        $sql = $this->mysql_connector->fetchOne($req);
        return $sql['cpt'];
    }

    public function page_count($is_admin, $year=false, $month=false) {
        $nb_art = $this->count($is_admin, $year, $month);
        return ceil($nb_art / $this->nb_articles_per_page);
    }

    public function page_count_category($is_admin, $category_id) {
        $nb_art = $this->count($is_admin, false, false, $category_id);
        return ceil($nb_art / $this->nb_articles_per_page);
    }

    public function page_count_tag($is_admin, $tag_id) {
        $nb_art = $this->count($is_admin, false, false, false, $tag_id);
        return ceil($nb_art / $this->nb_articles_per_page);
    }

    public function get_years($is_admin) {
        $req = 'SELECT DISTINCT YEAR(pubdate) AS name
            FROM articles
            WHERE pubdate > "2000-01-01 00:00:00"';
        // this condition is to exclude 0 dates from
        // cached articles
        if (!$is_admin) {
            $req .= ' AND pubdate < NOW() ';
        }
        $req .= ' ORDER BY name DESC';
        return $this->mysql_connector->fetchAll($req);
    }

    public function get_months($is_admin, $year) {
        $req = 'SELECT DISTINCT MONTH(pubdate) AS month_int
            FROM articles
            WHERE pubdate > "2000-01-01 00:00:00"
                AND YEAR(pubdate) = '.(int)$year;
        // this condition is to exclude 0 dates from
        // cached articles
        if (!$is_admin) {
            $req .= ' AND pubdate < NOW() ';
        }
        $req .= ' ORDER BY month_int DESC';
        return $this->mysql_connector->fetchAll($req);
    }

    public function get_months_articles($is_admin, $year, $month, $page) {
        $offset = $page * $this->nb_articles_per_page;
        $year = (int)$year;
        $month = (int)$month;
        $page = (int)$page;
        $req = 'SELECT id, auteur, titre, url, texte,
            pubdate, cat, captcha_com, closed_com,
            ghost_id, canonical_url
            FROM articles
            WHERE YEAR(pubdate) = '.$year.'
                AND MONTH(pubdate) = '.$month;
        if (!$is_admin) {
            $req .= ' AND pubdate < NOW() ';
        }
        $req .= ' ORDER BY pubdate DESC LIMIT '.$offset.', '.$this->nb_articles_per_page;
        $art_sql = $this->mysql_connector->fetchAll($req);
        $articles = array();
        foreach($art_sql as $article_data) {
            $articles[] = Article::load($article_data);
        }
        return $articles;
    }

    public function get_tag_articles($is_admin, $page, $tag_id, $limit=0, $year=null) {
        if (empty($limit)) {
            $limit = $this->nb_articles_per_page;
        }
        $offset = $page * $limit;
        $page = (int)$page;
        $tag_id = (int)$tag_id;
        $req = 'SELECT id, auteur, titre, url, texte,
            pubdate, cat, captcha_com, closed_com,
            ghost_id, canonical_url
            FROM articles a
            LEFT JOIN article_tags at
                ON a.id = at.article_id
            WHERE tag_id = '.$tag_id;
        if (!$is_admin) {
            $req .= ' AND pubdate < NOW() ';
        }
        if ($year) {
            $req .= ' AND YEAR(pubdate) = '.(int)$year;
        }
        $req .= ' ORDER BY pubdate DESC LIMIT '.$offset.', '.$limit;
        $art_sql = $this->mysql_connector->fetchAll($req);
        $articles = array();
        foreach($art_sql as $article_data) {
            $articles[] = Article::load($article_data);
        }
        return $articles;
    }

    public function get_category_articles($is_admin, $page, $category_id) {
        $offset = $page * $this->nb_articles_per_page;
        $page = (int)$page;
        $category_id = (int)$category_id;
        $req = 'SELECT id, auteur, titre, url, texte,
            pubdate, cat, captcha_com, closed_com,
            ghost_id, canonical_url
            FROM articles
            WHERE cat = '.$category_id;
        if (!$is_admin) {
            $req .= ' AND pubdate < NOW() ';
        }
        $req .= ' ORDER BY pubdate DESC LIMIT '.$offset.', '.$this->nb_articles_per_page;
        $art_sql = $this->mysql_connector->fetchAll($req);
        $articles = array();
        foreach($art_sql as $article_data) {
            $articles[] = Article::load($article_data);
        }
        return $articles;
    }

    public function save($article) {
        if (!empty($article->id)) {
            $req = "UPDATE articles SET
                        auteur = %s,
                        titre = %s,
                        url = %s,
                        texte = %s,
                        pubdate = %s,
                        cat = %d,
                        captcha_com = %d,
                        closed_com = %d
                WHERE id = %d";
            $this->mysql_connector->update($req, $article->auteur,
                $article->titre, $article->url, $article->texte,
                $article->pubdate, $article->cat,
                $article->captcha_com, $article->closed_com, $article->id);
        } else {
            $req = "INSERT INTO articles
                        (id, auteur, titre, url,
                         texte, pubdate, cat,
                         captcha_com,
                         closed_com)
                    VALUES('', %s, %s, %s,
                           %s, %s, %d,
                           %d,
                           %d)";
            $res = $this->mysql_connector->insert($req,
                $article->auteur, $article->titre, $article->url,
                $article->texte, $article->pubdate, $article->cat,
                $article->captcha_com,
                $article->closed_com);
            $article->id = $res['insert_id'];
        }
        return $article;
    }

    public function delete($article_id) {
        $req = "DELETE FROM article_tags WHERE article_id = %d";
        $this->mysql_connector->delete($req, $article_id);
        $req = "DELETE FROM articles WHERE id = %d";
        $this->mysql_connector->delete($req, $article_id);
    }

    public function like($article_id, $ip) {
        $existing_like = 'SELECT article_id, ip FROM `like` WHERE article_id = %d AND ip = %s';
        $existing_like = $this->mysql_connector->fetchOne($existing_like, $article_id, $ip);
        if (empty($existing_like)) {
            $req = "INSERT INTO `like`(article_id, ip) VALUES(%d, %s)";
            $this->mysql_connector->insert($req, $article_id, $ip);
        }
    }

    public function get_likes($article_id) {
        $req = "SELECT COUNT(*) AS nb_like FROM `like` WHERE article_id = %d";
        $nb = $this->mysql_connector->fetchOne($req, $article_id);
        if (!empty($nb)) {
            return (int)$nb['nb_like'];
        } else {
            return 0;
        }
    }
}
?>
