<?
/*
 -------------------------------------------------------------------------
 AllMyStats V1.59 - Statistiques, audience de site web
 -------------------------------------------------------------------------
 Copyright (C) 2008 - Herve Seywert
 -------------------------------------------------------------------------
 Web:    http://allmystats.wertronic.com - http://www.wertronic.com
 -------------------------------------------------------------------------
 copyright-GNU-xx.txt
 -------------------------------------------------------------------------
*/
$pathrel = dirname($_SERVER["PHP_SELF"]);

echo '
<table width="90%"  border="0" align="center" cellpadding="5">
            <tr>
              <td>
                  Providing financial assistance will be welcome, but will also be a recognition of the work accomplished and an encouragement to continue the development of AllMyStats.
				  <table  border="0" align="center" cellpadding="0">
                    <tr>
                      <td align="center"><form action="https://www.paypal.com/cgi-bin/webscr" target="_blank" method="post">
                          <input type="hidden" name="cmd" value="_xclick" />
                          <input type="hidden" name="business" value="pcb@wertronic.com" />
                          <input type="hidden" name="item_name" value="AllMySats" />
                          <input type="hidden" name="item_number" value="AllMyStats - Script website stats" />
                          <input type="hidden" name="amount" value="5.00" />
                          <input type="hidden" name="no_shipping" value="2" />
                          <input type="hidden" name="no_note" value="1" />
                          <input type="hidden" name="currency_code" value="EUR" />
                          <input type="hidden" name="tax" value="0" />
                          <input type="hidden" name="bn" value="IC_Sample" />
                          <input type="hidden" name="lc" value="US"> 
                          <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" />
                          <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" /><br />
                          <strong>5 Euros</strong>
                      </form></td>
                      <td align="center" valign="middle">OR&nbsp;</td>
                      <td align="center"><form action="https://www.paypal.com/cgi-bin/webscr" target="_blank" method="post">
                          <input type="hidden" name="cmd" value="_xclick" />
                          <input type="hidden" name="business" value="pcb@wertronic.com" />
                          <input type="hidden" name="item_name" value="AllMySats" />
                          <input type="hidden" name="item_number" value="AllMyStats - Script website stats" />
                          <input type="hidden" name="amount" value="10.00" />
                          <input type="hidden" name="no_shipping" value="2" />
                          <input type="hidden" name="no_note" value="1" />
                          <input type="hidden" name="currency_code" value="EUR" />
                          <input type="hidden" name="tax" value="0" />
                          <input type="hidden" name="bn" value="IC_Sample" />
                          <input type="hidden" name="lc" value="US"> 
						  <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" />
                          <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" /><br />
                          <strong>10 Euros</strong>
                      </form></td>
                      <td valign="top">We can provide<br />
                       an invoice<br />
                       on <a href="http://allmystats.wertronic.com/demande/renseignement.php" target="_blank">request</a>.</td>
                    </tr>
                  </table>
              </td>
            </tr>
          </table>';

echo "
<br>
&nbsp;&nbsp;<strong>Web directory :</strong> Registration limited to sites where AllMyStats installed.
<br>
&nbsp;&nbsp;<a href=\"http://allmystats.wertronic.com/web_annuaire/info/useCondition\" target=\"_blank\">Inscription</a>
<br><br>

<center><strong><big>Insertion code in the pages</big></strong></center><br /><br />
For the visits are counted you in every page your site put a code. <br />

 <br /><br />
<table border=\"0\" align=\"center\" cellpadding=\"7\" cellspacing=\"0\">
  <tr>
    <td>
		<a href=\"#codephp\">Code for PHP pages</a><br>
		<a href=\"#codehtml\">Code for HTML pages</a>
	</td>
    <td>
		<a href=\"#codeosc\">Code for Oscommerce</a><br>
		<a href=\"http://allmystats.wertronic.com/en_audience_website_baker.php\" target=\"_blank\">Code for WebsiteBaker</a><br>
		<a href=\"http://allmystats.wertronic.com/en_audience_arfooo_annuaire.php\" target=\"_blank\">Code for Arfooo directory</a><br>				
	</td>
    <td>&nbsp;</td>
  </tr>
</table>
 <br /><br />

<hr align=\"center\" width=\"50%\" noshade><br /><br />
<a name=\"codephp\"></a>
<strong><big><center>If the pages on your site are in PHP</center></big></strong>
<br /><br />
PHP Code to be inserted in each page: <br />
-------------------------------------<br />
This code should be wherever your pages compared to the root of your site.
<br /><br />
".htmlentities('<?php')."<br />
\$nom_page='page name';<br />
\$Racine_abs = str_replace(\$_SERVER['PHP_SELF'],\"\",\$_SERVER['SCRIPT_FILENAME']);<br />
require \$Racine_abs.'".$pathrel."/visiteur.php';<br />
".htmlentities('?>')."
<br /><br />
-------------------------------------<br />
This code should also, but the path (path) is to adapt depending on the position of the directory where you can find the page.
<br /><br />
".htmlentities('<?php')."<br />
\$nom_page='page name';<br />
require 'path/allmystats/visiteur.php';<br />
".htmlentities('?>')."
<br /><br />
Replace <strong> path </strong> by the relative path to access the directory allmystats
<br /><br />
-------------------------------------<br />
<br />
\$nom_page is the name of the page, you can put the name you want for each page.<br />
Example: <br />
\$nom_page='Home Page';<br /><br />
<br />
To be sure that pages have been accounted for fully loaded, it is preferable to put the code at the bottom of page.

<br /><br />
<hr align=\"center\" width=\"50%\" noshade>
<br />
<a name=\"codehtml\"></a>
<strong><big><center>If the pages on your site are in html or htm</center></big></strong><br /><br />

<strong>1st</strong> solution (best): <br />
If you provide your host allows, add these two lines to the file .htaccess if it already exists if not create it.<br />
AddType application / x-httpd-php. Html <br />
AddType application / x-httpd-php. Htm <br /> <br />
The HTML pages and htm will be treated as if they were PHP.<br /><br />
Then put the PHP code in each page of your site with tags ".htmlentities ('<? Php')." at the beginning and ". htmlentities ('?>')." at the end. <br />
<hr align=\"center\" width=\"50%\" noshade><br />
<strong>2nd</strong> solution: <br />
Note: referring and keywords will not be counted. <br />
Code simple: <br />
".htmlentities ('<img src="path/allmystats/visiteur.php?nom_page=nom_de_la_page" width=0 height=0>')."< br><br />
Replace <strong> path </strong> by the relative path to access the directory allmystats <br />
<hr align=\"center\" width=\"50%\" noshade><br />

<a name=\"codeosc\"></a>
<table border=\"0\">
  <tr>
    <td nowrap>

<strong><big><center>Code for OSCommerce</center></big></strong>
<br /><br />

<strong>1 - Edit index.php</strong><br />
Find the following line (bottom of page) in the source code<br /><br />

".htmlentities('<?php'). " require(DIR_WS_INCLUDES . 'application_bottom.php'); ".htmlentities('?>')." <br /> <br />
<strong>Replace with: </strong>(with ".htmlentities('<?php'). " et ".htmlentities('?>').") <br /><br />

".htmlentities('<?php')."<br />
require(DIR_WS_INCLUDES . 'application_bottom.php'); <br /><br />
//----- AllMystats code for pour Oscommerce ---------------<br />
//Nom dynamique de la page (rubrique)<br />
\$separateur = ' ".htmlentities('&')."raquo; ';<br />
\$pos = strpos(\$breadcrumb-&gt;trail(\$separateur), \$separateur);<br />
\$nom_page = strip_tags(substr(\$breadcrumb-&gt;trail(\$separateur), \$pos + strlen(\$separateur))); <br />

\$Racine_abs = str_replace(\$_SERVER['PHP_SELF'],\"\",\$_SERVER['SCRIPT_FILENAME']);<br />

	if (file_exists(\$Racine_abs.'".$pathrel."/visiteur.php')) {<br />
		require \$Racine_abs.'".$pathrel."/visiteur.php';<br />	
	}<br />
//----- End AllMystats code for Oscommerce ----------<br />
".htmlentities('?>')."<br /><br />

<center>---------------------------------------------</center>
<strong>2 - Edit product_info.php</strong><br />
Find the following line (bottom of page) in the source code<br /><br />

".htmlentities('<?php'). " require(DIR_WS_INCLUDES . 'application_bottom.php'); ".htmlentities('?>')." <br /> <br />
<strong>Replace with: </strong>(with ".htmlentities('<?php'). " et ".htmlentities('?>').") <br /><br />

".htmlentities('<?php')."<br />
require(DIR_WS_INCLUDES . 'application_bottom.php'); <br /><br />

//----- AllMystats code for Oscommerce ---------------<br />
//Nom dynamique de la page (nom produit, modèle)<br />

	if (tep_not_null(\$product_info['products_model'])) {<br />
		\$nom_page = \"Prod: \".substr(\$product_info['products_name'], 0, 80). ' [' . \$product_info['products_model'] . ']';<br />
	} else {<br />
		\$nom_page = \"Prod: \".substr(\$product_info['products_name'], 0, 100);<br />
	}<br />

\$Racine_abs = str_replace(\$_SERVER['PHP_SELF'],\"\",\$_SERVER['SCRIPT_FILENAME']);<br />

	if (file_exists(\$Racine_abs.'".$pathrel."/visiteur.php')) {<br />
		require \$Racine_abs.'".$pathrel."/visiteur.php';<br />	
	}<br />
//----- End AllMystats code for Oscommerce ----------<br />
".htmlentities('?>')."<br /><br />
<center>---------------------------------------------</center><br /><br />
<strong><center>That's all.</center></strong><br /><br />
<hr align=\"center\" width=\"50%\" noshade><br />
	&nbsp;</td>
  </tr>
</table>
";
?>

