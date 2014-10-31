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
                $this->data['post'] = $_POST;
                require_once('libraries/recaptchalib.php');
                $privatekey = "6LcHPNISAAAAAJEmnitqm99TVUtoH9CWCGVIM_VZ";
                $resp = recaptcha_check_answer ($privatekey,
                                              $_SERVER["REMOTE_ADDR"],
                                              $_POST["recaptcha_challenge_field"],
                                              $_POST["recaptcha_response_field"]);

                if (!$resp->is_valid) {
                    $error = true;
                    $this->data['err_msg'] = "Captcha mal renseigné.";
                }

                $site = null;
                if (substr($_POST['site'], 0, 7) == 'http://') {
                    $site = $_POST['site'];
                }
                if (!empty($_POST['follow']) && $_POST['follow'] == "on" && empty($_POST['mail'])) {
                    $error = true;
                    $this->data['err_msg'] = "Il faut remplir l'email pour être averti";
                }
                if (!$error) {
                    $banned_words = $com_repo->get_banned_words();
                    foreach ($banned_words as $r) {
                        if (strpos(strtolower($_POST['commentaire']), $r['word']) !== false || strpos(strtolower($_POST['pseudo']), $r['word']) !== false || strpos(strtolower($_POST['site']), $r['word']) !== false) {
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
            $follow = $_POST['follow'] == "on";
            $com_repo->add($art, utf8_decode($_POST['pseudo']),
                           utf8_decode($_POST['commentaire']),
                           htmlentities($site), $_SERVER['REMOTE_ADDR'],
                           $_POST['mail'], $follow);
            // Warn followers that a new comments have been posted
            $followers = $com_repo->get_article_followers($art);
            $bcc = array();
            foreach ($followers as $v) {
                if ($_POST['mail'] != $v['mail'] and !in_array($v['mail'], $bcc)) {
                    $bcc[] = $v['mail'];
                }
            }
            $to      = 'followers@melmelboo.fr';
            $subject = '[melmelboo.fr] Nouveau commentaire sur '.htmlspecialchars($article->titre);
            $message = "Un nouveau commentaire a été posté sur l'article <a href='http://www.melmelboo.fr/art-".$article->url."-".$article->id."'>".$article->titre."</a>.<br /><br />Commentaire de <strong>".$_POST['pseudo']."</strong><br /><br />".stripslashes(htmlspecialchars($_POST['commentaire']));
            $headers  = 'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=utf-8' . "\r\n" .
            "From: no-reply@melmelboo.fr\r\n" .
            "Reply-To: no-reply@melmelboo.fr\r\n" .
            "Bcc: ".implode(",", $bcc)."\r\n" .
            'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);
        }
    }
}
