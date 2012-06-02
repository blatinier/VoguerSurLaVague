<?
/*
 -------------------------------------------------------------------------
 AllMyStats V1.39 - Statistiques de fréquentation visiteurs et robots
 -------------------------------------------------------------------------
 Copyright (C) 2000 - Cédric TATANGELO (Cedstat)
 Copyright (C) 2008 - Herve Seywert
 -------------------------------------------------------------------------
 Web:   http://www.wertronic.com
 -------------------------------------------------------------------------
 Ce programme est libre, vous pouvez le redistribuer et/ou le modifier
 selon les termes de la Licence Publique Génrale GNU publiée par la Free
 Software Foundation .
 -------------------------------------------------------------------------
*/

// ----------------------------------------------------------------------------
// Extraction des mots clés quand les référants sont des moteurs de recherche
// ----------------------------------------------------------------------------
/*
# Liste originale. Quelques variables modifiées pour coller à la réalité actuelle
yahoo.com p=
altavista.com q=
google.com q=
lycos.com query=
hotbot.com query=
msn.com q=
webcrawler searchText= N'est plus OK
excite q=
recherche.netscape.fr query=
alltheweb.com q=

# Moteurs français ajoutés.
google.fr q=
vachercher.lycos.fr query=
msn.fr q=
voila.fr rdata==
rechercher.aliceadsl.fr qs=
recherche.aol.fr q=
www.aolrecherche.aol.fr query=
search.free.fr qs=
www.seek.fr qry_str=
www.chello.fr q1=

# Autres moteurs (annuaires) ajoutés.
looksmart.com key=
search.dmoz.org search=

*/

function MotsCles($ref, $ref_host) {

  $motscles = "";
  
  $url   = parse_url( $ref );
  $query = $url["query"];
  $host  = $url["host"];
  
  parse_str($query);

  //Pour les adwords l'url est trouvée car dans visiteur.php est ajouté au referer 
  //$Referer = urldecode($Url_syndication[0]). '?googlesyndication=1' ou '&googlesyndication=1';
  if ($googlesyndication==1) {
  	$motscles = $ref;
  }

  if ($host == 'fr.dir.yahoo.com') {
	//$motscles = "Annuaire";
	$motscles = '['.trim(utf8_decode(strtolower($p))).']';
	$ref_host = 'Yahoo !';

  } elseif (strstr($host,'search.yahoo') <> '' && $p) {
	$motscles = '['.trim(utf8_decode(strtolower($p))).']';
	$ref_host = 'Yahoo !';

  } elseif (strstr($host,'altavista') <> '') {
	$motscles = '['.trim(utf8_decode(strtolower($q))).']';
	$ref_host = 'AltaVista';

  } elseif ($host == 'search.voila.com' || $host == 'search.voila.fr' || $host == 'search.ke.voila.fr') {
	$motscles = '['.trim(utf8_decode(strtolower($rdata))).']';
	$ref_host = 'Voila';

  } elseif ($host == 'hotbot.lycos.com') {
	$motscles = '['.trim(utf8_decode(strtolower($MT))).']';
	$ref_host = 'Lycos';

  } elseif (strstr($host,'images.google') <> '') {
	if (strlen(strip_tags($imgurl))>60) { $aff_url=substr($imgurl,0,60).'...'; }
	//$motscles = '[<a href="'.trim(utf8_decode($imgurl)).'" target="_new">'.trim(utf8_decode($aff_url)).'</a>]'; //Pas de strtolower( pour image.google
	$motscles = '['.trim(utf8_decode(strtolower($q))).']';
	$ref_host = 'Google images';

  } elseif (strstr($host,'google') <> '' && $q || $as_q) { //as_q vu pour la 1ere fois le 01-09-2009
	if ($q) { $motscles = '['.trim(utf8_decode(strtolower($q))).']'; }
	if ($as_q) { $motscles = '['.trim(utf8_decode(strtolower($as_q))).']'; }

	$ref_host = 'Google';

  } elseif ((strstr($host,'216.239.59.104') <> '' || strstr($host,'209.85.135.104') <> ''|| strstr($host,'209.85.129.104') <> '') && $q ) {
	$CacheMot = explode( '/',utf8_decode($q));
	$motscles = 'Google cache['.trim($CacheMot[sizeof($CacheMot)-1]).']';
	$ref_host = 'Google';

  } elseif (strstr($host,'search.live') <> '' || strstr($host,'search.msn') <> '') {
	$motscles = '['.trim(utf8_decode(strtolower($q))).']';
	$ref_host = 'MSN';

  } elseif (strstr($host,'excite') <> '') {
	$motscles = '['.trim(utf8_decode(strtolower($s))).']';
	$ref_host = 'ring';

  } elseif (strstr($host,'webring') <> '') {
	$motscles = '['.trim(utf8_decode(strtolower($ring))).']';
	$ref_host = 'ring';

  } elseif (strstr($host,'dada.net') <> '') {
	$motscles = '['.trim(utf8_decode(strtolower($q))).']';
	$ref_host = 'ring';

  } elseif (strstr($host,'altavista') <> '') {
	$motscles = '['.trim(utf8_decode(strtolower($q))).']';
	$ref_host = 'ring';

  } elseif (strstr($host,"recherche.aol.fr") <> '' || strstr($host,"search.hp.my.aol.fr") <> '') { // pour www.aolrecherche.aol.fr et www.recherche.aol.fr
	if ($q <>'') {
		$motscles = '['.trim(utf8_decode(strtolower($q))).']';
	} else {
		$motscles = '['.trim(utf8_decode(strtolower($query))).']';
	}
	$ref_host = 'AOL recherche';
	
  } elseif (strstr($host,"rechercher.aliceadsl.fr") <> '') {
	$motscles = '['.trim(utf8_decode(strtolower($qs))).']';
	$ref_host = 'Aliceadsl (Tiscali)';

  } elseif (strstr($host,'vachercher.lycos') <> '') {
	$motscles = '['.trim(utf8_decode(strtolower($query))).']';
	$ref_host = 'Lycos';
  
  } elseif (strstr($host,'search.free') <> '' && $qs) {
	$motscles = '['.trim(utf8_decode(strtolower($qs))).']';
	$ref_host = 'Free';

  } elseif (strstr($host,'seek') <> '') {
	$motscles = ' ['.trim(utf8_decode(strtolower($qry_str))).']'; // Si pas espace avant [ --> ne marche pas ???
	$ref_host = 'Seek';

  } elseif (strstr($host,'www.chello.fr') <> '') {
	$motscles = ' ['.trim(utf8_decode(strtolower($q1))).']'; // Si pas espace avant [ --> ne marche pas ???
	$ref_host = 'chello.fr';

  } elseif (strstr($host,'search.sweetim') <> '') {
	$motscles = ' ['.trim(utf8_decode(strtolower($q))).']'; // Si pas espace avant [ --> ne marche pas ???
	$ref_host = 'Sweetim';

  } elseif (strstr($host,'pagebull') <> '') {
	$motscles = ' ['.trim(utf8_decode(strtolower($qIn))).']'; // Si pas espace avant [ --> ne marche pas ???
	$ref_host = 'Pagebull';

  } elseif (strstr($host,'picsearch') <> '') {
	$motscles = ' ['.trim(utf8_decode(strtolower($q))).']'; // Si pas espace avant [ --> ne marche pas ???
	$ref_host = 'Picsearch';

  } elseif (strstr($host,'recherche.hit-parade.com') <> '') {
	$motscles = ' ['.trim(utf8_decode(strtolower($p7))).']'; // Si pas espace avant [ --> ne marche pas ???
	$ref_host = 'recherche.hit-parade.com';

  } elseif (strstr($host,'www.veosearch.com') <> '') {
	$motscles = ' ['.trim(utf8_decode(strtolower($q))).']'; // Si pas espace avant [ --> ne marche pas ???
	$ref_host = 'Veosearch.com';

  } elseif (strstr($host,'search15.conduit.com') <> '') {
	$motscles = ' ['.trim(utf8_decode(strtolower($q))).']'; // Si pas espace avant [ --> ne marche pas ???
	$ref_host = 'search15.conduit.com';

  } elseif (strstr($host,'www.toile.com') <> '') {
	$motscles = ' ['.trim(utf8_decode(strtolower($q))).']'; // Si pas espace avant [ --> ne marche pas ???
	$ref_host = 'www.toile.com';

  } elseif (strstr($host,'www.bing.com') <> '') { //Moteur microsoft depuis juin 2009
	$motscles = ' ['.trim(utf8_decode(strtolower($q))).']'; 
	$ref_host = 'www.bing.com';

  } elseif (strstr($host,'lo.st') <> '') { 
	$motscles = ' ['.trim(utf8_decode(strtolower($x_query))).']'; 
	$ref_host = 'lo.st';

  } elseif (strstr($host,'exalead') <> '') { 
	$motscles = ' ['.trim(utf8_decode(strtolower($q))).']'; 
	$ref_host = 'exalead';

  } elseif (strstr($host,'search.conduit.com') <> '') { 
	$motscles = ' ['.trim(utf8_decode(strtolower($q))).']'; 
	$ref_host = 'search.conduit.com';

  } elseif (strstr($host,'search.babylon.com') <> '') { 
	$motscles = ' ['.trim(utf8_decode(strtolower($q))).']'; 
	$ref_host = 'search.babylon.com';

  } elseif (strstr($host,'hooseek.com') <> '') { 
	$motscles = ' ['.trim(utf8_decode(strtolower($q))).']'; 
	$ref_host = 'hooseek.com';

  } elseif (strstr($host,'search.twitter.com') <> '') {    // pas bon moteur de recherche interne
	$motscles = ' ['.trim(utf8_decode(strtolower($q))).']'; 
	$ref_host = 'search.twitter.com';

  }

  if ($motscles != "") 
	/*
	//Ne sert plus (lorqu'on affichait l'url de l'image)
	if (strstr($host,'images.google') <> '') {
		return $motscles = html_entity_decode($motscles); 
	}
	*/
    return stripslashes(htmlentities($motscles));

}
?>

