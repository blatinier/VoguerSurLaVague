<?php
//---------------------------------------------------
//New pagerank 07-11-2010
//header("Content-Type: text/plain; charset=utf-8");
define('GOOGLE_MAGIC', 0xE6359A60);
//---------------------------------------------------


    /*
    * Google® PageRank® PHP Script & Google® PageRank® Checksum Algorithm
    * Remanié par: FloBaoti [ www.generatix.fr ]
    *
    * Auteur original: http://pagerank.gamesaga.net/
    *
    * Compatible PHP4 & PHP5 // support X86_64 CPU
    *
    * Ce script permet de récupérer directement sur les serveurs Google,
    * l'indice PageRank d'une URL (cf http://www.webrankinfo.com/google/pagerank/index.php).

	Wertronic - remplace pour compatibilité des pages existantes
	nom function PageRank par getRank 
	et class GooglePR par PagerankSeo
    ajout test si url valide
	* */
    
     class PagerankSeo
     {
     /*
    * * convert a string to a 32-bit integer
    * */
     function StrToNum($Str, $Check, $Magic)
     {
     $Int32Unit = 4294967296; // 2^32
    
     $length = strlen($Str);
     for ($i = 0; $i < $length; $i++)
     {
     $Check *= $Magic;
     //If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31),
     // the result of converting to integer is undefined
     // refer to http://www.php.net/manual/en/language.types.integer.php
     if ($Check >= $Int32Unit)
     {
     $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
     //if the check less than -2^31
     $Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
     }
     $Check += ord($Str{$i});
     }
     return $Check;
     }
    
     /*
     * Genearate a hash for a url
     */
     function HashURL($String)
     {
     $Check1 = $this->StrToNum($String, 0x1505, 0x21);
     $Check2 = $this->StrToNum($String, 0, 0x1003F);
    
     $Check1 >>= 2;
     $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
     $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
     $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);
    
     $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
     $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
    
     return ($T1 | $T2);
     }
    
     /*
     * genearate a checksum for the hash string
     */
     function CheckHash($Hashnum)
     {
     $CheckByte = 0;
     $Flag = 0;
    
     $HashStr = sprintf('%u', $Hashnum) ;
     $length = strlen($HashStr);
    
     for ($i = $length - 1; $i >= 0; $i --)
     {
     $Re = $HashStr{$i};
     if (1 === ($Flag % 2))
     {
     $Re += $Re;
     $Re = (int)($Re / 10) + ($Re % 10);
     }
     $CheckByte += $Re;
     $Flag ++;
     }
    
     $CheckByte %= 10;
     if (0 !== $CheckByte)
     {
     $CheckByte = 10 - $CheckByte;
     if (1 === ($Flag % 2) )
     {
     if (1 === ($CheckByte % 2))
     {
     $CheckByte += 9;
     }
     $CheckByte >>= 1;
     }
     }
    
     return '7'.$CheckByte.$HashStr;
     }
    
	 // Avant 07-10-2011 --> $dcgg = 'www.google.com'
	 function getRank($url, $dcgg = 'toolbarqueries.google.com') {

			if(!ini_get('track_errors')) {
				ini_set('track_errors', 1); //pour avoir err & warning $php_errormsg
				$save_track = 0;
			}

			//TEST FAIT failed to open stream: HTTP request failed! HTTP/1.0 403 Forbidden in ... --> si &ch= non valid
			// Avant 07-10-2011
			//if ($file_query = @file('http://'.$dcgg.'/search?client=navclient-auto&ch='.$this->CheckHash($this->HashURL($url)).'&ie=UTF-8&oe=UTF-8&features=Rank&q=info:'.urlencode($url))) {
			// Depuis 07-10-2011
			if ($file_query = @file('http://'.$dcgg.'/tbr?client=navclient-auto&ch='.$this->CheckHash($this->HashURL($url)).'&ie=UTF-8&oe=UTF-8&features=Rank&q=info:'.urlencode($url))) {
				$file = @implode("", $file_query);
				$result = substr($file,strrpos($file, ":")+1);
			} elseif (strstr($php_errormsg, '403 Forbidden')) {
				$result = 'Forbidden';
			} 
			
			if ($save_track == 0) {
				ini_set('track_errors', ''); //remet etat initial
			}

			return $result; 
     }

 } // End class


	//wertronic ajout test si url valide
	function isUrlValid($Url) {
		if (@fopen($Url, 'r')) return True; else return false;
	}

    ?>