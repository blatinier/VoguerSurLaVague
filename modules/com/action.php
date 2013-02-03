<?php
require_once(dirname(__FILE__).'/../../tools/recaptcha/recaptchalib.php');
$err = false;
if(!empty($_POST['ida']))
	$ida = (int)$_POST['ida'];
elseif(!empty($_GET['art']))
	$ida = (int)$_GET['art'];

$commentaire = $_POST['commentaire'];
$site = $_POST['site'];
if (strpos("http://") === false) {
    $site = "";
}

if (!empty($_POST) && !empty($_POST['captcha_com'])) {
    $resp = recaptcha_check_answer ($recaptcha_priv,
                              $_SERVER["REMOTE_ADDR"],
                              $_POST["recaptcha_challenge_field"],
                              $_POST["recaptcha_response_field"]);
    if (!$resp->is_valid) {
        $err = true;
        $err_msg = "Erreur dans le captcha";
    }
}
if (!empty($_POST) && !empty($ida)) {
    $open_com_query = "SELECT closed_com FROM comments WHERE id=".$ida;
    $q = mysql_query($open_com_query);
    $open_line = mysql_fetch_assoc($q);
    if (!empty($open_line) && $open_com_query['closed_com'] == 1) {
        $err = true;
        $err_msg = "Commentaires fermés sur cet article.";
    } 
    if (!$err) {
        $banned_words_query = "SELECT word FROM banned_words";
        $banned_words_qr = mysql_query($banned_words_query);
        while ($r = mysql_fetch_assoc($banned_words_qr)) {
            if (strpos(strtolower($commentaire), $r['word']) !== false){
                $err = true;
                $err_msg = "Ce commentaire contient des mots interdits.";
                break;
            }
        }
    }
}

if (!empty($_SESSION['ok']) && $_SESSION['ok']) {
	$pseudo = "Melmelboo";
    $site = "http://www.melmelboo.fr";
} elseif ((empty($_SESSION['ok']) || !$_SESSION['ok']) && (strtolower($_POST['pseudo']) == "melmelboo")) {
    if (strpos($site, "melmelboo.fr")) {
        $site = "";
    }
    if (strpos($site, "http://") === false) {
        $site = "http://" . $site;
    }
	$pseudo = "";
	$err = true;
    $err_msg = "Désolé mais ce pseudo est réservé.";
} else {
    if (strpos($site, "melmelboo.fr")) {
        $site = "";
    }
    if (strpos($site, "http://") === false) {
        $site = "http://" . $site;
    }
	$pseudo = $_POST['pseudo'];
}
if(!empty($pseudo) && !empty($commentaire) && !$err){

	if($_POST['savedata']=="on"){
		setcookie('ComVoguer[P]',$pseudo,(time()+3600*24*365));
		setcookie('ComVoguer[S]',$site,(time()+3600*24*365));
	}

	$pseudo = mysql_real_escape_string($pseudo);
	$commentaire = mysql_real_escape_string($commentaire);
	$site = mysql_real_escape_string($site);
    $ips = mysql_query("SELECT ip FROM blacklist");
    $blacklist = array();
    while ($r = mysql_fetch_assoc($ips)) {
        $blacklist[] = $r['ip'];
    }

    if (!in_array($_SERVER['REMOTE_ADDR'], $blacklist)) {
    	mysql_query("INSERT INTO comments(id,idarticle,moment,pseudo,commentaire,site,ip) 
                     VALUES('',".$ida.",NOW(),'".$pseudo."','".$commentaire."','".$site."','".$_SERVER['REMOTE_ADDR']."')")or die(mysql_error());
    	$idc = mysql_insert_id();
    	mysql_query("INSERT INTO new_comments(id,idcom,idarticle) VALUES('','".$idc."','".$ida."')")or die(mysql_error());
    	$sent = true;
    }
}

if (!empty($_COOKIER['ComVoguer'])){
    if($_COOKIE['ComVoguer']['P']){
    	$pseudo = $_COOKIE['ComVoguer']['P'];
    }
    if($_COOKIE['ComVoguer']['S']){
    	$site = $_COOKIE['ComVoguer']['S'];
    }
}
?>
