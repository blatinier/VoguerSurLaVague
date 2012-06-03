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
*/

// ----------------------------------------------------------------------------
// Extraction des mots clés quand les référants sont des moteurs de recherche
// ----------------------------------------------------------------------------

function MotsCles($referer) {
global $start_page;

  $motscles = "";

  // Format : keyword|;|position|;|page
  $delimiter = '|;|'; // 22-10-2011 for add keyword position   
    
  $url = parse_url($referer);
  $query = $url["query"];
  $host  = $url["host"];
  
  parse_str($query);

  //Pour les adwords l'url est trouvée car dans visiteur.php est ajouté au referer 
  //$Referer = urldecode($Url_syndication[0]). '?googlesyndication=1' ou '&googlesyndication=1';
  if ($googlesyndication == 1 || $googlesyndicReseauGCLID == 1) {
  	$motscles = $referer;
  }

  // Yahoo
  if ($host == 'fr.dir.yahoo' || strstr($host,'search.yahoo')) {
	if ($b <> "") { //Calcul page ou se trouve le mot clé
		$page = @floor($b/10) + 1 ; 
	} else {
		$page = 1 ; 	
	}
	$motscles = '['.trim((strtolower($p))).']'.$delimiter.$delimiter.$page.$delimiter;

  // Voila - some time redirect to .orange.fr if http://search.ke.voila.fr/S/orange?
  } elseif ($host == 'search.voila.com' || $host == 'search.voila.fr' || $host == 'search.ke.voila.fr') {
	if ($ap <> "") { //Calcul page ou se trouve le mot clé
		$page = $ap ; // Todo verify for Voila.fr, OK if redirect orange ?
	}
	$motscles = '['.trim((strtolower($rdata))).']'.$delimiter.$delimiter.$page.$delimiter;

  // ------------------------------------------------------------

  // Google images new version --> Display hold version
  } elseif (strstr($host,'google') <> '' && $tbm == 'isch' && $sout == 1) {
	$page = @floor($start/20)+1; 
	$motscles = '['.trim(strtolower($q)).']'.$delimiter.$position.$delimiter.$page.$delimiter;

  // Google images new version  
  } elseif (strstr($host,'google') <> '' && $tbm == 'isch' && $sout <> 1) {
 
  //A cafouillé une fois donc on test si numérique
  if(!is_numeric($page)) {
	  $page = '';
  }
 
  // new google images : url &page --> num page - &ved=1t:429,r:21,s:399 eg --> départ 399eme image, 22eme de la page &ved=1t:429,r:6,s:0
	$exp_ved = explode(',', $ved);
	$position = substr($exp_ved[1],2) + substr($exp_ved[2],2);
	$motscles = '['.trim(strtolower($q)).']'.$delimiter.$position.$delimiter.$page.$delimiter;
  
  // Google images  Very hold - on garde
  } elseif (strstr($host,'images.google') <> '') { 
	$motscles = '['.trim(strtolower($q)).']';
	
  // ------------------------------------------------------------
  // Google search - keyword position = $cd in url
  } elseif (strstr($host,'google') <> '' && $q || $as_q) { //as_q vu pour la 1ere fois le 01-09-2009
	if ($num <> "") { //Calcul début 1ere page google ou se trouve le mot clé
		$start_page = @floor($cd/$num)*10 ; 
	} else {
		$start_page = @floor($cd/10)*10 ; // Default num = 10
	}
	// Pour certaines url Google qui ne contiennent pas &source=web et &cd mais contiennent &start
	if($start) {
		$page = @floor($start/10)+1;
	} elseif ($cd){ 
		//$page = @floor($start_page/10)+1;	// OK but currently not used
	}
	if ($q) { $motscles = '['.trim((strtolower($q))).']'.$delimiter.$cd.$delimiter.$page.$delimiter; }
	if ($as_q) { $motscles = '['.trim((strtolower($as_q))).']'.$delimiter.$cd.$delimiter.$page.$delimiter; }

  // Google cache - 25-10-2011 - Obsolete ?
  } elseif ((strstr($host,'216.239.59.104') <> '' || strstr($host,'209.85.135.104') <> ''|| strstr($host,'209.85.129.104') <> '') && $q ) { //22-10-2011 Google cache Obsolète ?
	$CacheMot = explode( '/',($q));
	$motscles = 'Google cache['.trim($CacheMot[sizeof($CacheMot)-1]).']';
  // ------------------------------------------------------------
  
  // Exite
  } elseif (strstr($host,'excite') <> '') {
	$motscles = '['.trim((strtolower($q))).']';

  } elseif (strstr($host,'webring') <> '') {
	$motscles = '['.trim((strtolower($ring))).']';

  } elseif (strstr($host,'dada.net') <> '') {
	$motscles = '['.trim((strtolower($q))).']';

  // Altavista --> Redirect to http://fr.yhs4.search.yahoo.com
  } elseif (strstr($host,'altavista') <> '') {
	$motscles = '['.trim((strtolower($q))).']';

  // AOL
  } elseif (strstr($host,"recherche.aol.fr") <> '' || strstr($host,"search.hp.my.aol") <> '' || strstr($host,"search.aol.com") <> '') { // pour www.aolrecherche.aol.fr et www.recherche.aol.fr
	if (!$page) { //Calcul page ou se trouve le mot clé
		$page = 1;	
	}
	if ($q <>'') {
		$motscles = '['.trim((strtolower($q))).']'.$delimiter.$delimiter.$page.$delimiter;
	} else {
		$motscles = '['.trim((strtolower($query))).']'.$delimiter.$delimiter.$page.$delimiter;
	}
	
  // AliceAdsl
  } elseif (strstr($host,"rechercher.aliceadsl.fr") <> '') {
	$motscles = '['.trim((strtolower($qs))).']';

  // Lycos
  } elseif (strstr($host,'lycos.fr') <> '') {
	if ($page2 <> "") { //Calcul page ou se trouve le mot clé
		$page = $page2 + 1; 
	} else {
		$page = 1;	
	}
	$motscles = '['.trim((strtolower($query))).']'.$delimiter.$delimiter.$page.$delimiter;
  
  } elseif (strstr($host,'hotbot.com') <> '') {
	if ($pn <> "") { //Calcul page ou se trouve le mot clé
		$page = $pn; 
	} else {
		$page = 1;	
	}
	$motscles = '['.trim((strtolower($q))).']'.$delimiter.$delimiter.$page.$delimiter;

  } elseif (strstr($host,'search.free') <> '' && $q) { // Mais pas tjrs  http://search.free.fr/google.pl?next=/search?q= (avant c'était $qs)
	$motscles = '['.trim((strtolower($q))).']';

  } elseif (strstr($host,'seek') <> '') {
	$motscles = ' ['.trim((strtolower($qry_str))).']'; // Si pas espace avant [ --> ne marche pas ???

  } elseif (strstr($host,'www.chello.fr') <> '') {
	$motscles = ' ['.trim((strtolower($q1))).']'; 

  } elseif (strstr($host,'search.sweetim') <> '') {
	if ($start <> "") { //Calcul page ou se trouve le mot clé
		$page = @floor($start/10)+1 ; 
	}
	$motscles = ' ['.trim((strtolower($q))).']'.$delimiter.$delimiter.$page.$delimiter;

  } elseif (strstr($host,'picsearch') <> '') {
	$motscles = ' ['.trim((strtolower($q))).']';

  } elseif (strstr($host,'www.veosearch.com') <> '') {
	$motscles = ' ['.trim((strtolower($q))).']'; 

  } elseif (strstr($host,'www.toile.com') <> '') {
	if ($p <> "") { //Calcul page ou se trouve le mot clé
		$page = $p; 
	} else {
		$page = 1;	
	}
	$motscles = ' ['.trim((strtolower($q))).']'.$delimiter.$delimiter.$page.$delimiter;

  // Bing
  } elseif (strstr($host,'www.bing.com') <> '') { 
	if ($first <> "") { //Calcul page ou se trouve le mot clé
		$page = @floor($first/10) + 1 ; 
	} else {
		$page = 1 ; 	
	}
	$motscles = ' ['.trim((strtolower($q))).']'.$delimiter.$delimiter.$page.$delimiter; 

  // lo.st
  } elseif (strstr($host,'lo.st') <> '') { 
	if ($x_start <> "") { //Calcul page ou se trouve le mot clé
		$page = @floor($x_start/10) + 1 ; 
	} else {
		$page = 1 ; 	
	}
	$motscles = ' ['.trim((strtolower($x_query))).']'.$delimiter.$delimiter.$page.$delimiter; 

  // Exalead
  } elseif (strstr($host,'exalead') <> '') { 
	if ($elements_per_page <> "") { //Calcul page google ou se trouve le mot clé
		$page = @floor($start_index/$elements_per_page) + 1 ; 
	} else {
		$page = @floor($start_index/10) + 1; // Default num = 10
	}
	$motscles = ' ['.trim((strtolower($q))).']'.$delimiter.$delimiter.$page.$delimiter;  

  // Search Babylon
  } elseif (strstr($host,'search.babylon.com') <> '') { 
	if ($start <> "") { //Calcul page ou se trouve le mot clé
		$page = @floor($start/10) + 1 ; 
	} else {
		$page = 1 ; 	
	}
	$motscles = ' ['.trim((strtolower($q))).']'.$delimiter.$delimiter.$page.$delimiter;   

  } elseif (strstr($host,'hooseek.com') <> '') { 
	if ($q <>'') {
		$motscles = '['.trim((strtolower($q))).']';
	} else {
		$motscles = '['.trim((strtolower($recherche))).']';
	}

  } elseif (strstr($host,'search.incredimail.com') <> '') {  
	if ($p <> "") { //Calcul page ou se trouve le mot clé
		$page = $p; 
	} else {
		$page = 1;	
	}
	$motscles = ' ['.trim((strtolower($q))).']'.$delimiter.$delimiter.$page.$delimiter;  

  // ASK
  } elseif (strstr($host,'ask.com') <> '') {  
	//for search &p= --> page - for image search &page
	if ($page) { //Calcul page ou se trouve le mot clé
		$page = $page;	
	} elseif ($p) {
		$page = $p;
	} else {
		$page = 1;	
	}	
	$motscles = ' ['.trim((strtolower($q))).']'.$delimiter.$delimiter.$page.$delimiter;   

  } elseif (strstr($host,'yougoo.fr') <> '') {  //Depend de Yahoo
	$motscles = ' ['.trim((strtolower($q))).']'; 

  } elseif (strstr($host,'searchqu.com') <> '') {  
	$motscles = ' ['.trim((strtolower($q))).']'; 

  } elseif (strstr($host,'fastbrowsersearch.com') <> '') {  
	$motscles = ' ['.trim((strtolower($q))).']'; 

  } elseif (strstr($host,'search.bluewin.ch') <> '') {  
	if ($searchterm <>'') {
		$motscles = '['.trim((strtolower($searchterm))).']';
	} else {
		$motscles = '['.trim((strtolower($searchTerm))).']';
	}

	$motscles = ' ['.trim((strtolower($searchterm))).']'; //04-01-2010

  // search.netscape.com redirect to http://search.aol.com/
  } elseif (strstr($host,'search.netscape.com') <> '') {  
	$motscles = ' ['.trim((strtolower($q))).']'; 

  } elseif (strstr($host,'alltheweb.com') <> '') {  
	$motscles = ' ['.trim((strtolower($q))).']'; 

  } elseif (strstr($host,'doona.fr') <> '') {  
	$motscles = ' ['.trim((strtolower($q))).']'; 

  //--------- Search Engine Cyrillic ------------------

  } elseif (strstr($host,'images.yandex')) {  // .ru Russian, .com, .ua Ukraine, .by Biélorussie
	if ($p <> "") { //Calcul page ou se trouve le mot clé
		$pos = $p + 1; 
	} else {
		$pos = 1;	
	}
	if ($text <>'') {
		$motscles = '['.trim((strtolower($text))).']'.$delimiter.$pos.$delimiter.$delimiter;
	} else {
		$motscles = '['.trim((strtolower($query))).']'.$delimiter.$pos.$delimiter.$delimiter;
	}


  // Yandex 
  } elseif (strstr($host,'yandex') <> '' && $host <> 'mail.yandex') {  // .ru Russian, .com, .ua Ukraine, .by Biélorussie
	if ($p <> "") { //Calcul page ou se trouve le mot clé
		$page = $p + 1; 
	} else {
		$page = 1;	
	}
	if ($text <>'') {
		$motscles = '['.trim((strtolower($text))).']'.$delimiter.$delimiter.$page.$delimiter;
	} else {
		$motscles = '['.trim((strtolower($query))).']'.$delimiter.$delimiter.$page.$delimiter;
	}

  // Rambler ru
  } elseif (strstr($host,'rambler.ru') <> '') {		
	if (!$page) { //Calcul page ou se trouve le mot clé
		$page = 1;	
	}
	if ($query <>'') {
		$motscles = '['.trim((strtolower($query))).']'.$delimiter.$delimiter.$page.$delimiter;
	} else {
		$motscles = '['.trim((strtolower($words))).']'.$delimiter.$delimiter.$page.$delimiter;
	}

  // Metabot ru
  } elseif (strstr($host,'metabot.ru') <> '') {  	
	$motscles = ' ['.trim((strtolower($st))).']'; 

  // Arport ru
  } elseif (strstr($host,'aport.ru') <> '') {  		//only .ru ?
	if ($p <> "") { //Calcul page ou se trouve le mot clé
		$page = $p + 1; 
	} else {
		$page = 1;	
	}
	$motscles = ' ['.trim((strtolower($r))).']'.$delimiter.$delimiter.$page.$delimiter; 

  // go.mail.ru
  } elseif ( strstr($host,'go.mail.ru') <> '')  {  		
	if ($b <> "") { //Calcul page ou se trouve le mot clé
		$page = @floor($sf/10) + 1 ; 
	} else {
		$page = 1 ; 	
	}
	$motscles = ' ['.trim((strtolower($q))).']'.$delimiter.$delimiter.$page.$delimiter;  

  // Mail ru
  } elseif ( strstr($host,'mail.ru') <> '' &&  $host <> 'win.mail.ru')  {  		
	$motscles = ' ['.trim((strtolower($q))).']'; 

  //http://nigma.ru
  } elseif ( strstr($host,'nigma.ru'))  {  		
	if ($startpos <> "") { //Calcul page ou se trouve le mot clé
		$page = @floor($startpos/10) + 1 ; 
	} else {
		$page = 1 ; 	
	}
	$motscles = ' ['.trim((strtolower($s))).']'.$delimiter.$delimiter.$page.$delimiter; 

  //--------------------------------------------------
  }

  
  if ($motscles != "") {
    return $motscles;
  }
	
	
} // End function
?>

