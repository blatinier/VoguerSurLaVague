<?php
class view_comments extends Widget {
    public function __construct($cfg, $mysql_connector) {
        parent::__construct($cfg, $mysql_connector);
    }
    
    public function _do_($action_name) {
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
        $art = $this->_getParam('art', 0);
        $com_repo = new CommentRepository();
        $comments = $com_repo->get_by_art_id($art);
        foreach($comments as $i => $c) {
            $date = explode('/', $c['heure']);
            $c['day'] = $date[0];
            $c['month'] = $MONTH[(int)$date[1]];
            $c['year'] = $date[2];
            $comments[$i] = $c;
        }
        $this->data['comments'] = $comments;
        $this->data['admin'] = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
    }
}
