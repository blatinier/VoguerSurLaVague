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
  function get_first_day($day_number=1, $month=false, $year=false)
  {
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
 	  $current_dir = opendir($dir); 
 	  
 	  while($entryname = readdir($current_dir)) { 
		   if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")) { 
				delete_directory("${dir}/${entryname}"); 
		   } elseif ($entryname != "." and $entryname!="..") { 
				unlink("${dir}/${entryname}"); 
		   } 
 	  } 
 	  
 	  closedir($current_dir); 
 	  rmdir(${dir}); 
} 

/* AlexaRanking */
function AlexaRanking($domain) {
/*
A voir
if (function_exists(‘curl_init’)) {
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
return curl_exec($ch);
} else {
return file_get_contents($url);
}
*/
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
	preg_match('#<POPULARITY URL="(.*?)" TEXT="([0-9]+){1,}"/>#si', $data, $p);
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

	//echo htmlentities($part);
//*********

    return array($popularity, $reach_rank, $rank_delta);
}

function CalculPagerank($Url) {
	$seoPR = new PagerankSeo();
	$PageRank = $seoPR->getRank($Url);
	return $PageRank;
}

//
function gdVersion($user_ver = 0)
{
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
} // End gdVersion()

?>