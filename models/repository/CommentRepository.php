<?php

class CommentRepository extends Repository {
    public function __construct() {
        parent::__construct();
    }

    public function count($id_art) {
        $req = "SELECT COUNT(*) as cpt FROM
            comments WHERE idarticle=%i";
        $sql = $this->mysql_connector->fetchOne($req, $id_art);
        return $sql['cpt'];
    }

    public function get_all() {
        $req = "SELECT id, idarticle, moment, pseudo,
                    commentaire, site, ip 
                FROM comments";
        return $this->mysql_connector->fetchAll($req);
    }

    public function get_last_unread() {
        $req = "SELECT idcom, idarticle FROM new_comments";
        return $this->mysql_connector->fetchAll($req);
    }

    public function get_by_id($id) {
        $req = "SELECT id, idarticle, moment, pseudo,
                    commentaire, site, ip 
                FROM comments WHERE id=".(int)$id;
        return $this->mysql_connector->fetchOne($req);
    }

    public function get_banned_ip () {
        $req = "SELECT ip FROM blacklist";
        return $this->mysql_connector->fetchAll($req);
    }

    public function get_banned_words () {
        $req = "SELECT word FROM banned_words";
        return $this->mysql_connector->fetchAll($req);
    }

    public function add($idarticle, $pseudo,
        $comment, $site, $ip) {
        $country = geoip_country_code_by_name($ip);
        if (!in_array($country, array('US', 'CN', 'CA'))) {
            $req = "INSERT INTO comments(idarticle, moment, pseudo,
                        commentaire, site, ip)
                    VALUES(%i, NOW(), %s, %s, %s, %s)";
            $com = $this->mysql_connector->insert($req, $idarticle, $pseudo,
                        $comment, $site, $ip);
            $req = "INSERT INTO new_comments(idcom, idarticle)
                    VALUES(%s, %s)";
            $mark = $this->mysql_connector->insert($req, $com['insert_id'], $idarticle);
            return $com;
        }
    }

    public function mark_all_read () {
        $req = "TRUNCATE TABLE new_comments";
        $this->mysql_connector->execute($req);
    }

    public function mark_read ($com_id) {
        $req = "DELETE FROM new_comments WHERE idcom=%i";
        $this->mysql_connector->delete($req, $com_id);
    }

    public function get_by_art_id($id) {
        $req = "SELECT id, idarticle, moment, pseudo,
                    commentaire, site, ip, DATE_FORMAT(moment,'à %H:%%i \l\e %%d/%c/%y') AS heure
                FROM comments WHERE idarticle=".(int)$id."
                ORDER BY moment";
        $coms = $this->mysql_connector->fetchAll($req);
        return $coms;
    }

    public function delete($id) {
        $req = "DELETE FROM comments WHERE id = %i";
        $this->mysql_connector->delete($req, $id);
    }

    public function ban_ip($ip) {
        $req = "INSERT INTO blacklist(ip) VALUES(%s)";
        $com = $this->mysql_connector->insert($req, $ip);
    }

    public function clean_banned() {
        $req = "DELETE FROM comments WHERE ip IN (SELECT ip FROM blacklist)";
        $this->mysql_connector->delete($req);
        $req = "DELETE FROM new_comments WHERE idcom NOT IN (SELECT id FROM comments)";
        $this->mysql_connector->delete($req);
    }
}
?>
