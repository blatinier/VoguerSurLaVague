<?php
session_start();
require_once("tools/function.php");
require_once("tools/list_pages.php");

if(!empty($_GET['p'])){
    $p = (in_array($_GET['p'],array_flip($fm_liste_pages)))?$_GET['p']:'accueil';
} else {
    $p = 'accueil';
}
$inc_page = $fm_liste_pages[$p];

if(file_exists($inc_page.'query.php'))
	require_once($inc_page.'query.php');
if(file_exists($inc_page.'action.php'))
	require_once($inc_page.'action.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Voguer sur la vague et divaguer<?php echo (!empty($append_title)) ? " - ".$append_title : ""; ?></title>
<meta name="keywords" content="blog, melmelboo, frippes, ecolo, ecologie, recyclage, naturel, astuces, bricolage, truc, bloubiboulga" />
<meta name="description" content="Le blog de Melmelboo !" />
<link href="CSS/default.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.js"></script>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="tools/shadowbox/shadowbox.css">
<script type="text/javascript" src="tools/shadowbox/shadowbox.js"></script>
<script type="text/javascript">
    Shadowbox.init();
    $(function(){
        $(".postcontent img").each(function(index){
            $(this).wrap('<a href="'+$(this).attr("src")+'" title="'+$(this).attr("alt")+'" rel="shadowbox[Gallerie]" />');
        });
    });
</script>
<script type="text/javascript">
	<?php echo (!empty($fm_javascript)) ? $fm_javascript : ""; ?>
</script>
<link href='http://fonts.googleapis.com/css?family=Anton' rel='stylesheet' type='text/css'>
</head>

<body>
<div id="conteneur">
    <div id="menuH">
        <a href="/">HOME</a> / 
        <a href="/about">ABOUT</a> / 
        <a href="/contact">CONTACT</a>
    </div>
    <div id="header" onclick="location.href='http://www.melmelboo.fr'">Melmelboo</div>
    <div id="main">
        <div id="contenu">
            <?php require_once($inc_page.'content.php'); ?>
        </div>
        <div id="menuV">
            <?php require_once("modules/menuVertical/content.php"); ?>
        </div>
    </div>
	<div id="footer">
		<?php require_once("modules/footer/content.php"); ?>
	</div>
</div>
</body>
</html>
