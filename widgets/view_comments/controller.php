<?php
class view_comments extends Widget {
    public function __construct($cfg, $mysql_connector) {
        parent::__construct($cfg, $mysql_connector);
    }
    
    public function _do_($action_name) {
        $art = $this->_getParam('art', 0);
        $com_repo = new CommentRepository();
        $comments = $com_repo->get_by_art_id($art);
        $this->data['comments'] = $comments;
        $this->data['admin'] = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
    }
}
