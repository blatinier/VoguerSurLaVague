<?php
$err = false;

if($_SESSION['ok']){
	$pseudo = "Melmelboo";
}
elseif(!$_SESSION['ok'] && (strtolower($_POST['pseudo']) == "melmelboo")){
	$pseudo = "";
	$err = true;
}
else{
	$pseudo = $_POST['pseudo'];
}

$commentaire = $_POST['commentaire'];
$site = $_POST['site'];
if(!empty($_POST['ida']))
	$ida = (int)$_POST['ida'];
elseif(!empty($_GET['art']))
	$ida = (int)$_GET['art'];

if(!empty($pseudo)&&!empty($commentaire) && !$err){

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
    	mysql_query("INSERT INTO mellismelau_com(id,idarticle,moment,pseudo,commentaire,site,ip) 
                     VALUES('',".$ida.",NOW(),'".$pseudo."','".$commentaire."','".$site."','".$_SERVER['REMOTE_ADDR']."')")or die(mysql_error());
    	$idc = mysql_insert_id();
    	mysql_query("INSERT INTO voguer_newcom(id,idcom,idarticle) VALUES('','".$idc."','".$ida."')")or die(mysql_error());
    	$sent = true;
    }
}

if($_COOKIE['ComVoguer']['P']){
	$pseudo = $_COOKIE['ComVoguer']['P'];
}
if($_COOKIE['ComVoguer']['S']){
	$site = $_COOKIE['ComVoguer']['S'];
}
?>
