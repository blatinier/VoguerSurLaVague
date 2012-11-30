<?php

class CommentRepository extends Repository {
    public function __construct() {
        parent::__construct();
    }

    public function count($id_art) {
        $req = "SELECT COUNT(*) as cpt FROM
            mellismelau_com WHERE idarticle=%i";
        $sql = $this->mysql_connector->fetchOne($req, $id_art);
        return $sql['cpt'];
    }

    public function get_last_unread() {
        $req = "SELECT idcom, idarticle FROM voguer_newcom";
        return $this->mysql_connector->fetchAll($req);
    }

    public function get_by_id($id) {
        $req = "SELECT id, idarticle, moment, pseudo,
                    commentaire, site, ip 
                FROM mellismelau_com WHERE id=".(int)$id;
        return $this->mysql_connector->fetchOne($req);
    }

    public function mark_all_read () {
        $req = "TRUNCATE TABLE voguer_newcom";
        $this->mysql_connector->execute($req);
    }

    public function mark_read ($com_id) {
        $req = "DELETE FROM voguer_newcom WHERE idcom=%i";
        $this->mysql_connector->delete($req, $com_id);
    }
}
?>
