<?php
class new_comment extends Widget {
    public function __construct($cfg, $mysql_connector) {
        parent::__construct($cfg, $mysql_connector);
    }
    
    public function _do_($action_name) {
        $admin = (!empty($_SESSION['ok']) && $_SESSION['ok'] == 1);
        $art = $this->_getParam('art', 0);
        $error = false;
        $this->data['err_msg'] = "";
        $com_repo = new CommentRepository();
        $this->data['closed_com'] = true;
        if ($art != 0) {
            $art_repo = new ArticleRepository();
            $article = $art_repo->get_by_id($admin, $art);
            $this->data['closed_com'] = $article->closed_com;
            if (!empty($_POST) && !empty($_POST['commentaire']) && !$article->closed_com) {
                $site = null;
                if (substr($_POST['site'], 0, 7) == 'http://') {
                    $site = $_POST['site'];
                }
                $black_ip = $com_repo->get_banned_ip();
                foreach ($black_ip as $ip) {
                    if ($ip['ip'] == $_SERVER['REMOTE_ADDR']) {
                        $error = true;
                        $this->data['err_msg'] = "Votre adresse IP n'est pas autorisée à poster des commentaires.";
                        break;
                    }
                }
                if (!$error) {
                    $banned_words = $com_repo->get_banned_words();
                    foreach ($banned_words as $r) {
                        if (strpos(strtolower($_POST['commentaire']), $r['word']) !== false) {
                            $error = true;
                            $this->data['err_msg'] = "Ce commentaire contient des mots interdits.";
                            break;
                        }
                    }
                }
                if (!$error && !$admin && strtolower($_POST['pseudo']) == "melmelboo") {
                    $error = true;
                    $this->data['err_msg'] = "Ce pseudo est réservé.";
                }
            } else {
                $error = true;
            }
        }
        if (!empty($_POST) && !empty($_POST['commentaire']) && !$error) {
            $com_repo->add($art, htmlentities($_POST['pseudo']),
                htmlentities($_POST['commentaire']), htmlentities($site), $_SERVER['REMOTE_ADDR']);
        }
    }
}
