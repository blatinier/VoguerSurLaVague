<?php
include("../../../tools/sql.php");
$auteur = $_GET['auteur'];
$titre = $_GET['titre'];
$cat = $_GET['cat']; 
$art = $_GET['art'];
$requete = "UPDATE cache SET auteur='".$auteur."', titre='".$titre."', art='".$art."', cat='".$cat."' WHERE id=1"; 
mysql_query($requete) or die(mysql_error());
?>
