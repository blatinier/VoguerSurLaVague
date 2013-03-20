<?php
class Staticpages extends Controller {
    public $view;

    public function __construct($cfg, $mysql_connector=null) {
        parent::__construct($cfg, $mysql_connector);
    }

    public function contact() {
        if(!empty($_POST['pseudo']) && !empty($_POST['mail']) && !empty($_POST['titre']) && !empty($_POST['msg'])){
        	$to      = 'melmelboo@hotmail.com, benoit.latinier@gmail.com';
            $subject = '[Voguer sur...] '.htmlspecialchars($_POST['pseudo']).' : '.htmlspecialchars($_POST['titre']);
            $message = stripslashes(htmlspecialchars($_POST['msg']));
            $headers = 'From: '.htmlspecialchars($_POST['mail']) . "\r\n" .
            'Reply-To: '.htmlspecialchars($_POST['mail']).  "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        
            mail($to, $subject, $message, $headers);
        	$_SESSION['pseudo']  = "";
            $_SESSION['mail']  = "";
            $_SESSION['titre']  = "";
            $_SESSION['msg']  = "";
        
            $this->view->msg = "Merci pour ce petit message !!";
        } elseif((empty($_POST['pseudo']) || empty($_POST['mail']) || empty($_POST['titre']) || empty($_POST['msg'])) && !empty($_POST)){
        	$_SESSION['pseudo']  = $_POST['pseudo'];
        	$_SESSION['mail']  = $_POST['mail'];
        	$_SESSION['titre']  = $_POST['titre'];
        	$_SESSION['msg']  = $_POST['msg'];
        	$this->view->msg = "Merci de bien vouloir remplir tous les champs :)";
        }

    }

    public function logout () {
        session_destroy();
        session_unset();
        header("Location: index.php");
    }

    public function helphtml () {
    }

    public function apropos () {
    }

    public function admin () {
        $this->view->connected = false;
        if (empty($_SESSION['ok'])) {
            $_SESSION['ok'] = 0;
        }
        if ($_SESSION['ok'] == 1) {
            $this->view->connected = true;
        }
        if (!empty($_POST) && $_SESSION['ok'] != 1) {
            if ($_POST['pass'] == $this->config['admin_passwd']) {
                $_SESSION['ok'] = 1;
                $this->view->connected = true;
            } else {
                $this->view->msg = "Failed";
            }
        }
    }
}
?>
