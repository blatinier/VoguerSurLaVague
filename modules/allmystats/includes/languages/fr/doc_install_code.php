<?php
/*
  -------------------------------------------------------------------------
 AllMyStats V1.80 - Statistiques site web - Web traffic analysis
 -------------------------------------------------------------------------
 Copyright (C) 2008 - 2013 - Herve Seywert
 copyright-GNU-xx.txt
 -------------------------------------------------------------------------
 Web:    http://allmystats.wertronic.com - http://www.wertronic.com
 -------------------------------------------------------------------------
*/
$pathrel = dirname($_SERVER["PHP_SELF"]);

echo '
<table width="90%"  border="0" align="center" cellpadding="5">
        <tr>
          <td>
                Le fait d\'apporter une aide financi&egrave;re sera la bienvenue, mais sera aussi une reconnaissance du travail accomplie et un encouragement pour continuer le d&eacute;veloppement d\'AllMyStats.
              <table  border="0" align="center" cellpadding="0">
                <tr>
                  <td align="center"><form action="https://www.paypal.com/cgi-bin/webscr" target="_blank" method="post">
                      <input type="hidden" name="cmd" value="_xclick" />
                      <input type="hidden" name="business" value="pcb@wertronic.com" />
                      <input type="hidden" name="item_name" value="AllMySats" />
                      <input type="hidden" name="item_number" value="AllMyStats - Script statistiques site Internet" />
                      <input type="hidden" name="amount" value="5.00" />
                      <input type="hidden" name="no_shipping" value="2" />
                      <input type="hidden" name="no_note" value="1" />
                      <input type="hidden" name="currency_code" value="EUR" />
                      <input type="hidden" name="tax" value="0" />
                      <input type="hidden" name="bn" value="IC_Sample" />
                      <input type="image" src="https://www.sandbox.paypal.com/fr_FR/i/btn/x-click-but04.gif" border="0" name="submit" />
                      <img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1" /><br />
                      <strong>5 Euros</strong>
                  </form></td>
                  <td align="center" valign="middle">OU&nbsp;</td>
                  <td align="center"><form action="https://www.paypal.com/cgi-bin/webscr" target="_blank" method="post">
                      <input type="hidden" name="cmd" value="_xclick" />
                      <input type="hidden" name="business" value="pcb@wertronic.com" />
                      <input type="hidden" name="item_name" value="AllMySats" />
                      <input type="hidden" name="item_number" value="AllMyStats - Script statistiques site Internet" />
                      <input type="hidden" name="amount" value="10.00" />
                      <input type="hidden" name="no_shipping" value="2" />
                      <input type="hidden" name="no_note" value="1" />
                      <input type="hidden" name="currency_code" value="EUR" />
                      <input type="hidden" name="tax" value="0" />
                      <input type="hidden" name="bn" value="IC_Sample" />
                      <input type="image" src="https://www.sandbox.paypal.com/fr_FR/i/btn/x-click-but04.gif" border="0" name="submit" />
                      <img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1" /><br />
                      <strong>10 Euros</strong>
                  </form></td>
                  <td valign="top">Nous pouvons vous<br />
				  fournir une facture<br />
				  sur <a href="http://allmystats.wertronic.com/demande/renseignement.php" target="_blank">demande</a>.
				  </td>
                </tr>
              </table>
		   </td>
          </tr>
      </table>';


echo "
<br>
&nbsp;&nbsp;<strong>Annuaire :</strong> Inscription r&eacute;serv&eacute; aux sites sur lesquels AllMyStats installé.
<br>
&nbsp;&nbsp;<a href=\"http://allmystats.wertronic.com/web_annuaire/info/useCondition\" target=\"_blank\">Inscription</a>
<br><br>

<center><strong><big>Insertion du code dans les pages</big></strong></center><br><br>
Pour que les visites soient comptabilisées vous devez dans chacune des pages
 de votre site mettre un code.
 <br><br>

<table border=\"0\" align=\"center\" cellpadding=\"7\" cellspacing=\"0\">
  <tr>
    <td>
		<a href=\"#codephp\">Code pour pages en PHP</a><br>
		<a href=\"#codehtml\">Code pour pages HTML</a>
	</td>
    <td>
		<a href=\"#codeosc\">Code pour Oscommerce</a><br>
		<a href=\"http://allmystats.wertronic.com/fr_audience_website_baker.php\" target=\"_blank\">Code pour WebsiteBaker</a><br>
		<a href=\"http://allmystats.wertronic.com/fr_audience_arfooo_annuaire.php\" target=\"_blank\">Code pour Arfooo annuaire</a><br>				
	</td>
    <td>&nbsp;</td>
  </tr>
</table>

 <br><br>
<hr align=\"center\" width=\"50%\" noshade><br>
<strong><big><center>Si les pages de votre site sont en PHP</center></big></strong><br><br>

<a name=\"codephp\"></a>
<strong>Code PHP &agrave; ins&eacute;rer dans chaque page:</strong><br>
-------------------------------------<br>
Le code suivant convient o&ugrave; que soient vos pages par rapport &agrave; la racine de votre site.
<br><br>
".htmlentities('<?php')."<br>
\$nom_page='nom de la page';<br>
\$Racine_abs = str_replace(\$_SERVER['PHP_SELF'],\"\",\$_SERVER['SCRIPT_FILENAME']);<br>
require \$Racine_abs.'".$pathrel."/visiteur.php';<br>
".htmlentities('?>')."
<br><br>
-------------------------------------<br>
Le code suivant convient aussi, mais le chemin (path) est &agrave; adapter en fonction de la position du r&eacute;pertoire o&ugrave; se trouve la page.
<br><br>
".htmlentities('<?php')."<br>
\$nom_page='nom de la page';<br>
require 'path/allmystats/visiteur.php';<br>
".htmlentities('?>')."
<br><br>
Remplacer <strong>path</strong> par le chemin relatif pour acc&eacute;der au r&eacute;pertoire allmystats
<br><br>
-------------------------------------<br>
<br>
\$nom_page est le nom de la page, vous pouvez mettre le nom que vous voulez pour chaque page.<br>
Exemple: <br>
\$nom_page='Page d'accueil du site';<br><br>
<br>
Pour &ecirc;tre s&ucirc;r que les pages comptabilis&eacute;es ont &eacute;t&eacute; charg&eacute;es enti&egrave;rement, il est pr&eacute;f&eacute;rable de mettre le code en bas de page.
<br><br>

<hr align=\"center\" width=\"50%\" noshade><br>
<a name=\"codehtml\"></a>
<strong><big><center>Si les pages de votre site sont en html ou htm</center></big></strong><br><br>

<strong>1ere</strong> solution (la meilleure):<br>
Si votre la prestation de votre h&eacute;bergeur le permet, ajoutez ces deux lignes au fichier .htaccess si il existe d&eacute;j&agrave; sinon le cr&eacute;er.<br>
AddType application/x-httpd-php .html<br>
AddType application/x-httpd-php .htm<br><br>
Les pages html et htm seront alors trait&eacute;es comme si elles &eacute;taient en PHP.<br><br>
Puis mettez le code PHP dans chaque page de votre site avec les balises ".htmlentities('<?php')." au d&eacute;but et ".htmlentities('?>')." &agrave; la fin.<br>

<hr align=\"center\" width=\"50%\" noshade><br>

<strong>2eme</strong> solution:<br>
Note: les r&eacute;f&eacute;rants et les mots cl&eacute;s ne seront pas comptabilis&eacute;s.<br>
Code simple:<br>
".htmlentities('<img src="path/allmystats/visiteur.php?nom_page=nom_de_la_page" width=0 height=0>')."<br><br>
Remplacer <strong>path</strong> par le chemin relatif pour acc&eacute;der au r&eacute;pertoire allmystats<br> 
<hr align=\"center\" width=\"50%\" noshade><br>

<a name=\"codeosc\"></a>
<table border=\"0\">
  <tr>
    <td nowrap>

<strong><big><center>Code pour les boutiques OsCommerce</center></big></strong>
<br><br>

<strong>1 - Editer index.php</strong><br>
Chercher la ligne suivante (en bas de page) dans le code source<br><br>

".htmlentities('<?php'). " require(DIR_WS_INCLUDES . 'application_bottom.php'); ".htmlentities('?>')." <br> <br>
<strong>Remplacer par: </strong>(avec ".htmlentities('<?php'). " et ".htmlentities('?>').") <br><br>

".htmlentities('<?php')."<br>
require(DIR_WS_INCLUDES . 'application_bottom.php'); <br><br>
//----- Code AllMystats pour Oscommerce ---------------<br>
//Nom dynamique de la page (rubrique)<br />
\$separateur = ' ".htmlentities('&')."raquo; ';<br />
\$pos = strpos(\$breadcrumb-&gt;trail(\$separateur), \$separateur);<br />
\$nom_page = strip_tags(substr(\$breadcrumb-&gt;trail(\$separateur), \$pos + strlen(\$separateur))); <br />

\$Racine_abs = str_replace(\$_SERVER['PHP_SELF'],\"\",\$_SERVER['SCRIPT_FILENAME']);<br>

	if (file_exists(\$Racine_abs.'".$pathrel."/visiteur.php')) {<br>
		require \$Racine_abs.'".$pathrel."/visiteur.php';<br>	
	}<br>
//----- End Code AllMystats pour Oscommerce ----------<br>
".htmlentities('?>')."<br><br>

<center>---------------------------------------------</center>
<strong>2 - Editer product_info.php</strong><br>
Chercher la ligne suivante (en bas de page) dans le code source<br><br>

".htmlentities('<?php'). " require(DIR_WS_INCLUDES . 'application_bottom.php'); ".htmlentities('?>')." <br> <br>
<strong>Remplacer par: </strong>(avec ".htmlentities('<?php'). " et ".htmlentities('?>').") <br><br>

".htmlentities('<?php')."<br>
require(DIR_WS_INCLUDES . 'application_bottom.php'); <br><br>

//----- Code AllMystats pour Oscommerce ---------------<br>
//Nom dynamique de la page (nom produit, mod&egrave;le)<br />

	if (tep_not_null(\$product_info['products_model'])) {<br>
		\$nom_page = \"Prod: \".substr(\$product_info['products_name'], 0, 80). ' [' . \$product_info['products_model'] . ']';<br>
	} else {<br>
		\$nom_page = \"Prod: \".substr(\$product_info['products_name'], 0, 100);<br>
	}<br>

\$Racine_abs = str_replace(\$_SERVER['PHP_SELF'],\"\",\$_SERVER['SCRIPT_FILENAME']);<br>

	if (file_exists(\$Racine_abs.'".$pathrel."/visiteur.php')) {<br>
		require \$Racine_abs.'".$pathrel."/visiteur.php';<br>	
	}<br>
//----- End Code AllMystats pour Oscommerce ----------<br>
".htmlentities('?>')."<br><br>
<center>---------------------------------------------</center><br><br>
<strong><center>C'est tout.</center></strong><br><br>
<hr align=\"center\" width=\"50%\" noshade><br>
	&nbsp;</td>
  </tr>
</table>
";
?>
