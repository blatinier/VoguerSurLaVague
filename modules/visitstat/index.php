<?php
	if(file_exists('config_allmystats.php')) {
		require "config_allmystats.php";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>AllMyStats - <? echo $site; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<meta name="Description" content="Statistiques site" />

</head>
<frameset rows="100%">
<frame src="index_frame.php" name="zone1" />

<noframes>
<body>
Nous sommes désolés, mais pour utiliser le script de statistiques AllMyStats, votre navigateur doit supporter 
l'affichage des frames. Merci de bien vouloir mettre à jour votre navigateur !
<br />
We are sorry about it, but in order to use AllMyStats statistics script, your browser must be able to browse
frames. Please update your browser.
</body>
</noframes>
</frameset>
</html>