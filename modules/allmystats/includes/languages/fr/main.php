<?php
/*
  -------------------------------------------------------------------------
 AllMyStats V1.75 - Statistiques site web - Web traffic analysis
 -------------------------------------------------------------------------
 Copyright (C) 2008-2010 - Herve Seywert
 copyright-GNU-xx.txt
 -------------------------------------------------------------------------
 Web:    http://allmystats.wertronic.com - http://www.wertronic.com
 -------------------------------------------------------------------------
//Add 23-09-2011
define('MSG_NOTE_ADD_BOT' TODO traduct
*/
// Fichier de langue : Français
define('MSG_TOP','TOP');
define('MSG_DEVELOPED_BY', 'Développé par');
define('MSG_UNKNOWN_OR_DIRECT', 'Inconnu ou directe');

define('MSG_HELP', 'Aide');
define('MSG_ACCUEIL', 'Accueil');
define('MSG_ARCHIVE', 'Archives');
define('MSG_MONTHLY', 'Mensuel');
define('MSG_MONTH', 'Mois:');
define('MSG_CURRENTLY_MONTHLY', 'Archives du mois en cours');
define('MSG_DATES', 'Dates');
define('MSG_TITLE_ORG_GEO', 'Origine g&eacute;ographique');
define('MSG_COUNTRY', 'Pays');
define('MSG_GRAPH_HOURS', 'Heures');
define('MSG_GRAPH_DAY', 'Jours');
define('MSG_YESTERDAY', 'Hier');
define('MSG_MONTH_HISTO', 'Historique du mois');
define('MSG_MONTH_ARCHIVED', 'Archiv&eacute;');
define('MSG_MONTH_CACHED', 'En Cache');
define('MSG_MONTH_COMPLETED_CACHE_FILE', 'Mise en Cache terminée');
define('MSG_MONTH_NOT_CACHED', 'Non en cache');
define('MSG_WARNING_MONTH_NOT_CACHED', 'Un ou des mois &eacute;coul&eacute;s ne sont pas en cache<br >Editer les mois &eacute;coul&eacute;s qui ne le sont pas.');
define('MSG_IN_PROGRESS', 'En cours');
define('MSG_BROWSERS_USED', 'Navigateurs utilis&eacute;s');
define('MSG_BROWSERS', 'Navigateurs');
define('MSG_OPERATING_SYSTEM_USED', "Syst&egrave;mes d'exploitation utilis&eacute;s");
define('MSG_OPERATING_SYSTEM', "Syst&egrave;me d'exploitation");
define('MSG_PAGE', "Pages");
define('MSG_PAGES_PERCENTAGE', "% Pages / Total");
define('MSG_VISITED_PAGES', 'Pages visit&eacute;es');
define('MSG_SINCE_BEGIN_MONTH', 'Depuis le d&eacute;but du mois');
define('MSG_KEYWORDS', 'Mots cl&eacute;s');
define('MSG_REFERERS_AND_KEYWORDS', 'R&eacute;f&eacute;rants et mots cl&eacute;s');
define('MSG_REFERERS', 'R&eacute;f&eacute;rants');
define('MSG_BACK', 'Retour');
define('MSG_DIFFERENTS', 'Diff&eacute;rents');
define('MSG_LOGOUT', 'D&eacute;connexion');
define('MSG_ADWORDS_CONTENT_NETWORK', "<font color=#333333>AdWords R&eacute;seau de Contenu</font>"); //<font color=#333333> ou #666666
define('MSG_ADWORDS_KEYWORD', "<font color=#333333>AdWords Keyword</font>");
define('MSG_LAST_VISITOR', 'Dernier visiteur &agrave;');
define('MSG_GRAPH_HOUR_VISITORS_PAGES', 'Visiteurs &amp; Pages visit&eacute;es / Heure');
define('MSG_GRAPH_DAY_VISITORS_PAGES', 'Pages visit&eacute;es &amp; visiteurs / Jour');
define('MSG_GRAPH_MONTH_VISITORS_PAGES', 'Pages visit&eacute;es &amp; visiteurs - Ann&eacute;e:');
define('MSG_NUMBER_VISITED_PAGES', 'Nombre de pages visit&eacute;es');
define('MSG_VISITED_PAGES_BY_VISITOR', 'Nombre de pages visit&eacute;es / visiteur');
define('MSG_FIRST_VISITOR', 'Premier visiteur &agrave;');
define('MSG_NUMBER_VISTORS', 'Nombre de visiteurs');
define('MSG_REPORT_AUDIENCE', "RAPPORT D'AUDIENCE");
define('MSG_REVERSE_DNS', 'Reverse DNS');
define('MSG_TOP_VISITORS', 'Top 20 des visiteurs');
define('MSG_VISITORS_AND_ROBOTS', 'Visiteurs + Robots');
define('MSG_VISITORS', 'Visiteurs');
define('MSG_PERCENTAGE_VISITORS_OS', "% Visiteurs / Operating System"); // n'est plus utilis&eacute;
define('MSG_PERCENTAGE_VISITORS_BOTS', "% Visites / Bot");
define('MSG_PERCENTAGE', "%");
define('MSG_ORIGIN_UNKNOWN', 'Origine non déterminée');
define('MSG_TOTAL_DIFFERENT_KEYWORDS', 'Total mots cl&eacute;s diff&eacute;rents');
define('MSG_TOTAL_DIFFERENT_KEYWORDS_DAY', "Total mots cl&eacute;s diff&eacute;rents pour aujourd'hui");
define('MSG_TOTAL_DIFFERENT_PAGES', 'Total pages diff&eacute;rentes');

define('MSG_TOTAL_PAGES_VISITED', 'Total pages visit&eacute;es');
define('MSG_TOTAL_VISITORS', 'Total visiteurs');

define('MSG_TOTAL_DIFFERENT_COUNTRIES', 'Total Pays diff&eacute;rents');
define('MSG_DIRECTORIE_UPDATE_EXIST', "Le r&eacute;pertoire update/ est pr&eacute;sent!<br>Cliquez sur le bouton ci-dessous pour le supprimer");
define('MSG_DIRECTORIE_INSTALL_EXIST', "Le r&eacute;pertoire install/ est pr&eacute;sent!<br>Cliquez sur le bouton ci-dessous pour le supprimer");
define('MSG_COMPLETE_LIST', 'Afficher liste compl&egrave;te');
define('MSG_SHORTLIST', 'Afficher liste r&eacute;duite');

//--------------------------------------------------------------
define('MSG_EXCLUDED_BOTS', 'Robots exclus');
define('MSG_VISITED_PAGES_BY_BOTS', 'Pages visit&eacute;s par les robots');
define('MSG_OTHER_BOTS', 'Autres Robots');
define('MSG_BOT_VISITS', 'Visites Robots');
define('MSG_BOT_PARENT_NAME', 'Nom (d&eacute;tails)');
define('MSG_BOT_NAME', 'Nom Robot');
define('MSG_BOTS_NB_PAGES_SCANNED', 'Nb de pages scann&eacute;es');
define('MSG_NB_DISTINCT_IP', 'IP distinctes');
define('MSG_BOTS_KNOW_IN_DATABASE', 'Robots d&eacute;finis dans la base: ');
define('MSG_DETAILS_UNKNOWN_BOTS', 'D&eacute;tails des Robots qui ne sont pas d&eacute;fini dans la base: : ');
define('MSG_BOTS_OS_BROWSER_UNKNOWN', 'User Agent: OS, Browsers, Robots non reconnus');
define('MSG_BAD_USER_AGENT_S_I', 'User Agent SPAM(S) et User Agent Inconnu(I)'); //Not use 08-10-2010
define('MSG_USER_AGENT', 'User Agent');
define('MSG_USER_AGENT_SPAM', 'Spam (S)');
define('MSG_USER_AGENT_UNKNOWN', 'Inconnu (I)');
define('MSG_USER_AGENT_OTHER', 'Autre (A)');
define('MSG_USER_AGENT_UNKNOWN_LIST', 'User Agent non reconnu, Spam, Browser, OS or Bot ');

define('MSG_COMMENTS', 'Commentaires');
define('MSG_SCOREBOARD', 'Tableau de bord');
define('MSG_DESCRIPTION', 'Description');
define('MSG_VALUE', 'Valeur');
define('MSG_TOTAL', 'Total');
define('MSG_STATISTICS_OF', 'Statistiques du: ');
define('MSG_NB_VISITORS', 'Nb de visiteurs');
define('MSG_BOTS_GRAPH_HOUR', 'Robots: Graphique /Heure');
define('MSG_VISITED_PAGES_BY_BOT', 'Nombre de pages visit&eacute;es / robot');
define('MSG_NB_VISITED_PAGES_BOTS', 'Nombre de pages vues par les robots');
define('MSG_NB_VISITS_BOTS', 'Nombre de visites par les robots');

//--------------------------------------------------------------

define('MSG_REFRESH', 'Refresh');
define('MSG_VISITEURS', 'Visiteurs');
define('MSG_VISITEURS_AND_BOTS', 'Visiteurs + Robots');
define('MSG_NO_COUNT_VISITS', '<font color=#FF0000>Pour ne pas compter les visites &agrave; partir de cet ordinateur.</font>');
define('MSG_CLICK_HERE', 'Cliquez ICI');
define('MSG_ADMIN_MY_VISITS_MANAGEMENT', 'Gestion de mes visites');
define('MSG_ADMIN_TOOLS', 'Admin');
define('MSG_ADMIN_TOOLS_MENU', 'Admin: Menu principal');
define('MSG_ADMIN_TOOLS_MY_VISITS', 'Mes visites');
define('MSG_ADMIN_TOOLS_BOTS', 'Robots');
define('MSG_ADMIN_TOOLS_NO_UNKNOWN_BOT', 'Aucun robot inconnu &agrave; ajouter.');
define('MSG_ADMIN_TOOLS_BOTS_LIST', 'Liste des robots (crawlers)');
define('MSG_ADMIN_TOOLS_PASSW', 'Mot de passe');
define('MSG_ADMIN_TOOLS_CHGT_PASSW', 'Modification de l\'utilisateur et du mot de passe');
define('MSG_ADMIN_TOOLS_CHGT_USER_PASSW_SUCCESS', 'Le login et le mot de passe ont &eacute;t&eacute; modifi&eacute;s avec succ&egrave;s');
define('MSG_ADMIN_TOOLS_CONFIRM_PASSW_OUT', 'La confirmation de votre mot de passe est incorrecte');

//-----------------------------------------------------------------

define('MSG_BAD_USER_AGENT', 'Bad user agent');
define('MSG_TOOLS_CONFIRM_DELETE', 'Etes vous s&ucirc;r de vouloir supprimer');
define('MSG_ADD', 'Ajouter');
define('MSG_TYPE', 'Type');
define('MSG_ACTION', 'Action');
define('MSG_DELETE', 'Supprimer');
define('MSG_EDIT', 'Editer');
define('MSG_CANCEL', 'Annuler');
define('MSG_BOTS', 'Robots (crawlers)');
define('MSG_ADMIN_BOT_PARENT_NAME', 'Nom parent');
define('MSG_TOOLS_BOT_URL', 'URL du robot');
define('MSG_TOOLS_DELETE_SUCCESS', ' a &eacute;t&eacute; supprim&eacute; avec succ&egrave;s.');
define('MSG_TOOLS_ADD_SUCCESS', ' a &eacute;t&eacute; ajout&eacute; avec succ&egrave;s &agrave; la base de donn&eacute;es.');
define('MSG_TOOLS_MODIFIE_SUCCESS', ' a &eacute;t&eacute; modifi&eacute; avec succ&egrave;s.');

//-----------------------------------------------------------------
//-----------------------------------------------------------------
define('MSG_CHECK', 'V&eacute;rifier');
define('MSG_INSTALL_COOKIE', 'Installer un Cookie');
define('MSG_DELETE_COOKIE', 'Supprimer le Cookie');
define('MSG_COOKIE_INSTALLED', 'Le cookie est install&eacute;');
define('MSG_COOKIE_DELETED', 'Le cookie est supprim&eacute;');
define('MSG_THIS_PC', 'IP de votre connexion actuelle');
define('MSG_VISITS_FROMTHIS_RECORDED', 'Les visites effectu&eacute;es &agrave; partir de cet ordinateur sont comptabilis&eacute;es dans les statistiques.');

define('MSG_VISITS_FROMTHIS_RECORDED_DETAILS', "Pour que les visites effectu&eacute;es &agrave; partir de cet ordinateur ne soient pas comptabilis&eacute;es dans les statistiques,
au moins une des deux possibilit&eacute;s suivantes doit &ecirc;tre appliqu&eacute;e:<br>
1 - &nbsp;<b>Un cookie doit &ecirc;tre install&eacute;.</b><br>
2 - &nbsp;<b>L'adresse ip de votre connexion doit &ecirc;tre d&eacute;finie dans le fichier config_allmystats.php.");

define('MSG_IF_BROWSER_ACCEPT_COOKIES', 'Si votre navigateur accepte les cookies');
define('MSG_VISITS_FROMTHIS_NOT_RECORDED', 'Les visites du site: '.$site.' effectu&eacute;es &agrave; partir de cet ordinateur ne sont pas comptabilis&eacute;es dans les statistiques.');

define('MSG_COOKIE_AND_IP_INSTALLED', "Un cookie est install&eacute; et l'adresse IP ce cette connexion est d&eacute;finie dans config_allmystats.php.<br>
Si vous voulez comptabiliser les visites effectu&eacute;es &agrave; partir de cet ordinateur vous devez supprimer
le cookie et supprimer l'adresse IP du fichier config_allmystats.php<br><br>");

define('MSG_IF_YOU_WANT_RECORD_THIS', 'Si vous voulez comptabiliser les visites effectu&eacute;es &agrave; partir de cet ordinateur : ');

define('MSG_IP_ADRESS_IS_DEFINED_DETAILS', "L'adresse IP ce cette connexion est d&eacute;finie dans config_allmystats.php
<br><br><strong>"."Si votre navigateur accepte les cookies, il est peut &ecirc;tre pr&eacute;f&eacute;rable de placer un cookie (car votre IP est peut &ecirc;tre dynamique): </strong>");

define('MSG_IP_WHOSE_NOT_RECORDED', "<strong>IP dont les visites ne sont pas comptabilis&eacute;es : </strong> (IP d&eacute;finies dans config_allmystats.php)<br>");

define('MSG_NO_IP_DEFINITED', "Aucune adresse IP n'est d&eacute;finie.");

//------------------------------------------------------------------------------------------
//--------------- Install AllMySats --------------------------------------------------------

define('MSG_INSTALL_TITLE', "Installation de AllMyStats<br>Statistiques pour sites web");
define('MSG_INSTALL_LANGUAGES', "Langues");
define('MSG_JETLAG', "D&eacute;calage horaire");
define('MSG_INSTALL_SAME_DATE', "La date et l'heure doivent &ecirc;tre les m&ecirc;mes que chez vous");
define('MSG_INSTALL_MYSQL_SERVER', "MySQL Serveur");
define('MSG_INSTALL_MYSQL_PREFIX', "MySQL Préfixe des tables");
define('MSG_INSTALL_MYSQL_DATABASE_NAME', "MySQL Nom de la base");
define('MSG_INSTALL_MYSQL_LOGIN', "MySQL database login");
define('MSG_INSTALL_PASS', " MySQL Mot de passe");
define('MSG_INSTALL_TABLE_ALREADY_EXIST', " --> Cette table existe d&eacute;j&agrave;");
define('MSG_INSTALL_ALL_TABLE_ALREADY_EXIST', "<strong><font color=#FF0000>Les tables existes d&eacute;j&agrave;. Si vous voulez r&eacute;installer AllMyStats, veuillez d'abord supprimer les tables.</font></strong>");
define('MSG_INSTALL_ONE_OR_TABLES_ALREADY_EXIST', "<strong><font color=#FF0000>Une ou des tables sont d&eacute;j&agrave; pr&eacute;sentes,  Si vous voulez r&eacute;installer AllMyStats, veuillez d'abord les supprimer.</font></strong>");
define('MSG_INSTALL_TABLES_CREATED_SUCCESS', "Les tables ont &eacute;t&eacute; cr&eacute;&eacute;es avec succ&egrave;s");
define('MSG_INSTALL_COMPLETE', "L'installation est termin&eacute;e.<br><font color=#FF0000>Par s&eacute;curit&eacute; n'oubliez pas d'effacer le r&eacute;pertoire /install/</font>");
define('MSG_BUTTON_NEXT_STEP', "Etape suivante");
define('MSG_INSTALL_MYSQL_CONNEXION_ERROR', "Le test de connexion a &eacute;chou&eacute;");

//-------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------
define('MSG_NOTE_BAD_USER_AGENT', "<b>Pour les experts</b><br>
						<b>Attention &agrave; ne pas d&eacute;finir des bots/user agent innocents!</b><br>
						Vous pourrez les bloquer si n&eacute;cessaire avec un fichier .htaccess<br><br>
						Type = S (SPAM) est affich&eacute; en rouge dans Liste Bad user agent et n'est pas compt&eacute; comme visiteur ni comme robot<br>
						Type = I (robot inconnu) est compt&eacute; comme visiteur mais n'est pas affich&eacute;.<br>
						Le user agent est recherch&eacute; &agrave; l'identique (cha&icirc;ne de caract&egrave;res identique) et non dans la cha&icirc;ne.<br>
						<br><b>Note:</b> si aucun bad user agent n'est D&eacute;tect&eacute; &agrave; partir de cette liste, le tableau Bad user agent ne sera pas affich&eacute;.<br>
						Voir aussi: <a href=\"http://www.user-agents.org/\" target=\"_blank\">user-agents.org : Liste user agent</a><br><br>");
						
//-----------------------------------------------------------------------------------------
//Add 23-09-2011
define('MSG_NOTE_ADD_BOT', "Note:<br />
							<strong>Lorsque mod-security est installé</strong>, l'ajout de certains bots peut être bloqué car détecté comme une attaque (ex : wget).<br />
							Pour contourner le problème il faut mettre dans \"Nom Robot\" le nom exact du robot mais dans les champs \"Nom parent\" et \"Commentaires\" ne pas inscrire le nom exact du robot.<br />
							Exemple pour wget: mettre wget_ pour les champs \"Nom parent\" et \"Commentaires\"<br />
							Eviter aussi les mots comme FTP, etc.");

//-------------------- stats_in -----------------------------------------------------------
define('MSG_WEBSITE_STATISTICS', "Statistiques site:");
define('MSG_LAST_UPDATE', "Derni&egrave;re mise &agrave; jour:");
define('MSG_OPERATION_IN_PROGRESS', "Op&eacute;ration en cours, veuillez patienter...");
define('MSG_NOT_ALLOWED_THIS_SECTION', "Vous n'êtes pas autoris&eacute; à visiter cette section");
?>
