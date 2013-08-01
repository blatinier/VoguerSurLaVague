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

 function CompareValeurs($val1, $val2) {
	if ($val2[1] == $val1[1])
		return(strcmp($val1[0],$val2[0]));
	else
		return($val2[1] - $val1[1]);
 }


 /**
 * Fonction retournant le nombre de jours dans un mois.
 * @param integer $month Mois de 1 à 12
 * @param integer $year Année
 * @return integer Nombre de jours
 */
 function maxDaysInMonth($month, $year) {
	if( function_exists( "cal_days_in_month" )) {
	  $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
	  return $days;
	} else {
	  //if not compile PHP with support for calendars
	  return $days == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
	}
 }

  /**
   *
   *  Gets the first weekday of that month and year
   *
   *  @param  int   The day of the week (0 = sunday, 1 = monday ... , 6 = saturday)
   *  @param  int   The month (if false use the current month)
   *  @param  int   The year (if false use the current year)
   *
   *  @return int   The timestamp of the first day of that month
   *
   **/ 
  function get_first_day($day_number=1, $month=false, $year=false) {
    $month  = ($month === false) ? strftime("%m"): $month;
    $year   = ($year === false) ? strftime("%Y"): $year;
   
    $first_day = 1 + ((7+$day_number - strftime("%w", mktime(0,0,0,$month, 1, $year)))%7);
 
    return mktime(0,0,0,$month, $first_day, $year);
  }

	//--------------------------------------------------------------------
	// Server - bcmath not installed
	//If you don't have bcmath installed and you need to use bcdiv() with a defined precision / scale you may need this function:
	if( !function_exists( "bcdiv" )) {
		function bcdiv( $first, $second, $scale = 0 ) {
			if ($second <> 0 ) {
				$res = $first / $second;
				return round( $res, $scale );
			}
		}
	}
	
	//if you have compiled php width "--disable-bcmath", you can use this:
	if( !function_exists( "bcmul" )) {
	  function bcmul($_ro, $_lo, $_scale=0) {
		return round($_ro*$_lo, $_scale);
	  }
	}
	//--------------------------------------------------------------------

 /* Delete direcory recursif */
 function delete_directory($dir) { 
 	  
	  if(is_dir($dir)) { $current_dir = opendir($dir); } else { return false; }
 	  
 	  while($entryname = readdir($current_dir)) { 
		   if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")) { 
				delete_directory("${dir}/${entryname}"); 
		   } elseif ($entryname != "." and $entryname!="..") { 
				unlink("${dir}/${entryname}"); 
		   } 
 	  } 
 	  
 	  closedir($current_dir); 
 	  @rmdir(${dir});
	  
	  return true; 
 } 

 /* AlexaRanking */
 function AlexaRanking($domain) {

    $remote_url = 'http://data.alexa.com/data?cli=10&dat=snbamz&url='. trim ( $domain );

    if ($handle = @fopen ( $remote_url , "r" )) {
        $part = "";
        while (!feof ($handle)) {
            $part .= fread ($handle , 8192);
            $pos = strpos ($part , $start);

            if ($pos === false){ 
				continue; 
			} else {
				break;
			}
		}

        $part .= fread ($handle , 8192);
        @fclose ($handle);
	}

	//*********
	$data =  $part;
                       
	//POPULARITY 
	preg_match('/\<POPULARITY URL="(.*?)" TEXT="(.*?)"\/\>/Ui', $data, $p);
	$value = ($p[2]) ? number_format(($p[2])) : 0;
	$popularity = $value;

	//REACH RANK
	preg_match('#<REACH RANK="([0-9]+){1,}"/>#si', $data, $p);
	$value = ($p[1]) ? number_format(($p[1])) : 0;
	$reach_rank = $value;
	
	//RANK DELTA
	preg_match('#<RANK DELTA="([+\-0-9]+){1,}"/>#si', $data, $p);
	$value = ($p[1]) ? number_format(($p[1])) : 0;
	if ($value >= 0) { 
		$value = '+'.$value.'&nbsp;<img src="images/down_arrow.gif" alt="Down" title="Down" width="11" height="11" align="absmiddle">'; 
	} else {
		$value = ''.$value.'&nbsp;<img src="images/up_arrow.gif" alt="Up" title="Up" width="11" height="11">';	
	}
	$rank_delta = $value;

	//Alexa LINKS
	preg_match('/LINKSIN NUM="(.*?)"/Ui', $data, $p);         
	$value = ($p[1]) ? number_format(($p[1])) : 0;
	$alexa_links = $value;
	
	//echo htmlentities($part);
	//*********

    return array($popularity, $reach_rank, $rank_delta, $alexa_links);
 }

 // Get Google Page Rank
 function CalculPagerank($Url) {
	$seoPR = new PagerankSeo();
	$PageRank = $seoPR->getRank($Url);
	return $PageRank;
 }

 // Get GD version
 function gdVersion($user_ver = 0) {
    if (! extension_loaded('gd')) { return; }
    static $gd_ver = 0;
    // Just accept the specified setting if it's 1.
    if ($user_ver == 1) { $gd_ver = 1; return 1; }
    // Use the static variable if function was called previously.
    if ($user_ver !=2 && $gd_ver > 0 ) { return $gd_ver; }
    // Use the gd_info() function if possible.
    if (function_exists('gd_info')) {
        $ver_info = gd_info();
        preg_match('/\d/', $ver_info['GD Version'], $match);
        $gd_ver = $match[0];
        return $match[0];
    }
    // If phpinfo() is disabled use a specified / fail-safe choice...
    if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
        if ($user_ver == 2) {
            $gd_ver = 2;
            return 2;
        } else {
            $gd_ver = 1;
            return 1;
        }
    }
    // ...otherwise use phpinfo().
    ob_start();
    phpinfo(8);
    $info = ob_get_contents();
    ob_end_clean();
    $info = stristr($info, 'gd version');
    preg_match('/\d/', $info, $match);
    $gd_ver = $match[0];
    return $match[0];
 } 

 // Import .sql file http or path
 function mysql_import_file($filename, $SQL_query) { 
	global $errmsg;
   // lecture du fichier
	$SQL_query = array($SQL_query);

	if( substr($filename, 0, 7) == 'http://' ) {
		//Important sinon warning avec mod_security sur serveur check - 25-03-2011
		$opts = array(
			'http'=> array(
			'method'=>   "GET",
			'header'=>    'Accept: text/html',
			'user_agent'=>    'allmystats'
					)
				); 
		$ctx = stream_context_create($opts);
	}

	if(!@file($filename, FILE_SKIP_EMPTY_LINES, $ctx)) { //FILE_SKIP_EMPTY_LINES ou 0
		$errmsg = "Cannot open file $filename"; 
		return false; 
	} else {
		$lines = file($filename);
	}
	$lines = array_merge ($SQL_query, $lines);

   $scriptfile = false; 
   // Get rid of the comments and form one jumbo line  
   foreach($lines as $line) {
      $line = (trim($line)); 

	  if(substr($line, 0, 2) <> '--') { //if the line does not begin with --
		 $scriptfile .= " " . $line; 
      } 
   } 

   if(!$scriptfile) {
      $errmsg = "no text found in". $filename; 
      return false; 
   } 

   // Split the jumbo line into smaller lines  
   $queries = explode(chr(10), $scriptfile); // chr(10) ou "\n"
   
   // Run each line as a query 
   foreach($queries as $query) {
      $query = trim($query); 
      
	  if(trim($query) == "") { continue; } 

      if(!mysql_query($query.';')) { 
         $errmsg = "query ".$query." failed"; 
         return false; 
      } 
   } 

   //return true;
   return $errmsg; 
 } 

/**
 * AllMyStats --> Not used
 * OK mais le problème est que l'on ne peut pas modifier le préfixe des tables !
 * La fonction execute le fichier sql tel qu'il est.
/**
 * Import SQL from file
 *
 * @param string path to sql file
 */
function sqlImport($file) {

    $delimiter = ';';
    $file = fopen($file, 'r');
    $isFirstRow = true;
    $isMultiLineComment = false;
    $sql = '';

    while (!feof($file)) {

        $row = fgets($file);

        // remove BOM for utf-8 encoded file
        if ($isFirstRow) {
            $row = preg_replace('/^\x{EF}\x{BB}\x{BF}/', '', $row);
            $isFirstRow = false;
        }

        // 1. ignore empty string and comment row
        if (trim($row) == '' || preg_match('/^\s*(#|--\s)/sUi', $row)) {
            continue;
        }

        // 2. clear comments
        $row = trim(clearSQL($row, $isMultiLineComment));

        // 3. parse delimiter row
        if (preg_match('/^DELIMITER\s+[^ ]+/sUi', $row)) {
            $delimiter = preg_replace('/^DELIMITER\s+([^ ]+)$/sUi', '$1', $row);
            continue;
        }

        // 4. separate sql queries by delimiter
        $offset = 0;
        while (strpos($row, $delimiter, $offset) !== false) {
            $delimiterOffset = strpos($row, $delimiter, $offset);
            if (isQuoted($delimiterOffset, $row)) {
                $offset = $delimiterOffset + strlen($delimiter);
            } else {
                $sql = trim($sql . ' ' . trim(substr($row, 0, $delimiterOffset)));
                query($sql);

                $row = substr($row, $delimiterOffset + strlen($delimiter));
                $offset = 0;
                $sql = '';
            }
        }
        $sql = trim($sql . ' ' . $row);
    }
    if (strlen($sql) > 0) {
        query($row);
    }

    fclose($file);
}

/**
 * Remove comments from sql
 *
 * @param string sql
 * @param boolean is multicomment line
 * @return string
 */
function clearSQL($sql, &$isMultiComment){
    if ($isMultiComment) {
        if (preg_match('#\*/#sUi', $sql)) {
            $sql = preg_replace('#^.*\*/\s*#sUi', '', $sql);
            $isMultiComment = false;
        } else {
            $sql = '';
        }
        if(trim($sql) == ''){
            return $sql;
        }
    }

    $offset = 0;
    while (preg_match('{--\s|#|/\*[^!]}sUi', $sql, $matched, PREG_OFFSET_CAPTURE, $offset)) {
        list($comment, $foundOn) = $matched[0];
        if (isQuoted($foundOn, $sql)) {
            $offset = $foundOn + strlen($comment);
        } else {
            if (substr($comment, 0, 2) == '/*') {
                $closedOn = strpos($sql, '*/', $foundOn);
                if ($closedOn !== false) {
                    $sql = substr($sql, 0, $foundOn) . substr($sql, $closedOn + 2);
                } else {
                    $sql = substr($sql, 0, $foundOn);
                    $isMultiComment = true;
                }
            } else {
                $sql = substr($sql, 0, $foundOn);
                break;
            }
        }
    }
    return $sql;
}

/**
 * Check if "offset" position is quoted
 *
 * @param int $offset
 * @param string $text
 * @return boolean
 */
function isQuoted($offset, $text) {
    if ($offset > strlen($text))
        $offset = strlen($text);

    $isQuoted = false;
    for ($i = 0; $i < $offset; $i++) {
        if ($text[$i] == "'")
            $isQuoted = !$isQuoted;
        if ($text[$i] == "\\" && $isQuoted)
            $i++;
    }
    return $isQuoted;
}

function query($sql) {
    global $mysqli;
    //echo '#<strong>SQL CODE TO RUN:</strong><br>' . htmlspecialchars($sql) . ';<br><br>';
    if (!$query = $mysqli->query($sql)) {
        throw new Exception("Cannot execute request to the database {$sql}: " . $mysqli->error);
    }
}

// get remote file last modification date (returns unix timestamp)
function GetRemoteLastModified( $uri ) {
    // default
    $unixtime = 0;
   
    $fp = fopen( $uri, "r" );
    if( !$fp ) {return;}
   
    $MetaData = stream_get_meta_data( $fp );
       
    foreach( $MetaData['wrapper_data'] as $response ) {
        // case: redirection
        if( substr( strtolower($response), 0, 10 ) == 'location: ' ) {
            $newUri = substr( $response, 10 );
            fclose( $fp );
            return GetRemoteLastModified( $newUri );
        }
        // case: last-modified
        elseif( substr( strtolower($response), 0, 15 ) == 'last-modified: ' ) {
            //$unixtime = strtotime( substr($response, 15) );
			//$last_modified_date = date('jS F Y h:i:s A (T)', strtotime( substr($response, 15) )); // english
			$last_modified_date = date('j/m/Y h:i:s (T)', strtotime(substr($response, 15))); // French
            break;
        }
    }
    fclose( $fp );
    return $last_modified_date;
}
// ***************** CAMENBERT - PIE GRAPH With GD *****************
function create_img_piegraph($graph_img_name, $serie1, $serie2, $first_show_countries, $total_differents_countries) {
	if ($gdv = gdVersion()) {
		if ($gdv >=2) { // TrueColor functions may be used.
			$GD_ver = 'TrueColor';
		} else { // Avoid the TrueColor functions.
			$GD_ver = 'NOT_TrueColor';
		}
	} else { // The GD extension isn't loaded.
		$GD_ver = 'NOT_loaded';
	}

	if ($GD_ver == 'TrueColor' && $total_differents_countries > 0) {
		
		$pChart_path = 'lib/pChart.1.27d_GD';
		// Standard inclusions	v1.27
		include_once($pChart_path.'/pChart/pData.class');
		include_once($pChart_path.'/pChart/pChart.class');
		
		// Dataset definition 
		$DataSet = new pData;
	
		$DataSet->AddPoint($serie1,"Serie1");
		$DataSet->AddPoint($serie2,"Serie2");
		
		$DataSet->AddAllSeries();
		$DataSet->SetAbsciseLabelSerie("Serie2");
	
		// Initialise the graph
		$init_chart = new pChart(550,350);
		$init_chart->loadColorPalette($pChart_path.'/includes/tones-20c.txt'); // Ajouter pour le camenbert + de couleurs cycliques
		
		// Draw the pie chart
		$init_chart->setShadowProperties(0,0,200,200,200); // Ajouter
		$init_chart->setFontProperties($pChart_path.'/Fonts/tahoma.ttf',7);
		$init_chart->AntialiasQuality = 0;
		
		//														position hrz camenbert, position hte camenbert, diamètre, PIE_PERCENTAGE_LABEL, FALSE, perspective, hauteur camenbert, espace tranches
		//$init_chart->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),270,150,180,PIE_PERCENTAGE_LABEL,TRUE,50,20,5); //(org = TRUE,60,20,5);) --> pas mal TRUE,40,20,5);
		$init_chart->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),270,180,200,PIE_PERCENTAGE_LABEL,TRUE,50,20,5); // Monthly (note avec ,270,150,180 le titre ne s'affiche pas ?)

		//Graph TITLE
		if ($total_differents_countries > $first_show_countries) {	
			$init_chart->setFontProperties($pChart_path.'/Fonts/tahoma.ttf',10);  
			$init_chart->setShadowProperties(1,1,0,0,0);  
			$init_chart->drawTitle(0,0,"Top ".$first_show_countries." Countries",0,0,0,550,50,TRUE);
			$init_chart->clearShadow();  
		}
	
		//Le répertoire cache n'est pas dans le pack d'install, car si update pour ne pas vider le cache du client
		$nbSlashes = substr_count($_SERVER['SCRIPT_NAME'], '/'); // on compte le nombre total de slashes contenu dans le lien relatif du fichier courant
         $nbSlashes --; // on ne compte pas le slash de la racine (placé au début du lien relatif)
         $remontee = ''; // on iniitialise la remontée dans l'arborescence
         for($i = 0; $i < $nbSlashes; $i++) {
             $remontee .= '../';
         }

		if (!is_dir('cache')) { // Note : dirname (__FILE__) gives the path of the thid file script 
			mkdir ('cache');
		}

		$init_chart->Render("cache/".$graph_img_name);
	}
	 
	return $graph_img_name;
}

/*
//Todo pChart2.1.3
$pChart_path = 'lib/pChart2.1.3'; //2013-04-22
include($pChart_path."/class/pData.class.php");
include($pChart_path."/class/pDraw.class.php");
include($pChart_path."/class/pImage.class.php");

// Create your dataset object 
$myData = new pData();

// Add data in your dataset 
$myData->addPoints(array('10', '20'));

// Create a pChart object and associate your dataset 
$myPicture = new pImage(700,230,$myData);

// Define the boundaries of the graph area 
$myPicture->setGraphArea(60,40,670,190);

// Draw the scale, keep everything automatic 
$myPicture->drawSplineChart();

// Build the PNG file and send it to the web browser 
$myPicture->Stroke();


     echo "
	 <tr>
	 <td colspan=\"3\" style=\"".$td_data_CSS." text-align: center;\">
	 	<img src=\"cache/graph_org_geo_day_temp.png\"/>
	</td>
	</tr>";

}
*/
?>