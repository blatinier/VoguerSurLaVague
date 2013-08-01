<?php
/**
 * PageRank Lookup (Based on Google Toolbar for Internet Explorer)
 *
 * @copyright   2011 HM2K <hm2k@php.net>
 * @link        http://pagerank.phurix.net/
 * @author      James Wade <hm2k@php.net>
 * @version     $Revision: 1.5 $
 * @require     PHP 4.3.0 (file_get_contents)
 * @updated		06/10/11

 	Avant 07-10-2011 - search?client=navclient-auto - Depuis 07-10-2011 - /tbr?client=navclient-auto

	Wertronic 30-10-2011 - Re structure function 
	getPageRank ou getRank suivant script qui appelle
	Use curl if exist else file(

	Wertronic 04-12-2011 - Re structure function 
	Use curl if exist, if no response try file_get_contents and try file(
	Add timeout for curl, file_get_contents and file(
*/
    
class PagerankSeo  { // GooglePageRanker pour Arfooo else PagerankSeo

// Track the instance
  private static $instance;


	/**
	 * @var string	$host	The Google hostname for PageRank
	*/
	//var $host = 'www.google.com'; // Hold
	var $host = 'toolbarqueries.google.com';
	
	//var $url_check = 'http://%s/search?client=navclient-auto&ch=%s&features=Rank&q=info:%s'; // Hold
	var $url_check = 'http://%s/tbr?client=navclient-auto&ch=%s&features=Rank&q=info:%s&num=100&filter=0'; // Test add &num=100&filter=0
	
	var $timeout = 2;

	/**
	 * Returns the PageRank of a URL
	 *
	 * @param string  $q		The query/URL
	 * @param string  $context	A stream_context_create() context resource (optional).
	 *
	 * @return string
	 */


	function getRank($q,$context=NULL) {
		$ch = $this->checksum($this->makehash($q));
		$url = sprintf($this->url_check,$this->host,$ch,urlencode($q));

		if(!ini_get('track_errors')) {
			ini_set('track_errors', 1); //pour avoir err & warning $php_errormsg
			$save_track = 0;
		}

		//Important else no response Google timeout random - A voir a remarcher quand ajout user-agent avec GoogleToolbar
		$opts = array(
			'http'=> array(
				'timeout' => $this->timeout,
				'method'=> "GET",
				//'header'=> 'Accept: text/html',
				'user_agent'=> "Mozilla/4.0 (compatible; GoogleToolbar 2.0.114-big; Windows XP 5.1)"
				 )
		); 
					
		$context = stream_context_create($opts);


		if (function_exists('curl_init')) { // Curl exist ?
			$methode = "Try Curl";
			$data = $this->file_get_contents_curl($url);
			$pos = strpos($data, "Rank_");
			
			if($pos === false){ // if no response curl
				$methode .= "Try file_get_contents(";
				$data = @file_get_contents($url,false,$context);
				$pos = strpos($data, "Rank_");	
			}
		} else { // if curl is not instaled
			$methode = "Try 2 file_get_contents(";
			$data = @file_get_contents($url,false,$context);
			$pos = strpos($data, "Rank_");
		}
		
		if($pos === false){ // if no response curl or file_get_contents
			$methode .= "Try file(";
			$data = @implode("", @file($url,false,$context));
			$pos = strpos($data, "Rank_");
		}

/*
		// OK mais ne sert à rien de tester aussi avec, ne fait qu'allonger le temps de conexion à allmystats
		//le test fsockopen est en fin de fichier
		if($pos === false){ // (if(!$pos || $pos === false){) if no response curl && file_get_contents && file(
			$methode .= "Try check_fsockopen(";
			$data = $this->check_fsockopen($q);
			$pos = strpos($data, "Rank_");
		}
*/

		if ($save_track == 0) {
			ini_set('track_errors', ''); //remet etat initial
		}

		if($pos !== false){
			$pagerank = substr($data, $pos + 9);
		} elseif (strstr($php_errormsg, '403 Forbidden')) { 
			$pagerank = 'Forbidden';
		} else {
			$pagerank = 'no response';		
		}


		settype($pagerank, "string") ;
		return trim($pagerank);
		//return trim($methode." ".$pagerank); // Test
	}


	// Convert a string to a 32-bit integer
	function strtonum($str, $check, $magic) {
		$int32unit = 4294967296; // 2^32
		$length = strlen($str);
		for ($i = 0; $i < $length; $i++) {
			$check *= $magic; 	
			/* If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31), 
			 *	the result of converting to integer is undefined.
			 *	@see http://www.php.net/manual/en/language.types.integer.php
			*/
			if ($check >= $int32unit) {
				$check = ($check - $int32unit * (int) ($check / $int32unit));
				//if the check less than -2^31
				$check = ($check < -2147483648) ? ($check + $int32unit) : $check;
			}
			$check += ord($str{$i}); 
		}
		return $check;
	}

	// Genearate a hash for query
	function makehash($string) {
		$check1 = $this->strtonum($string, 0x1505, 0x21);
		$check2 = $this->strtonum($string, 0, 0x1003f);
		$check1 >>= 2; 	
		$check1 = (($check1 >> 4) & 0x3ffffc0 ) | ($check1 & 0x3f);
		$check1 = (($check1 >> 4) & 0x3ffc00 ) | ($check1 & 0x3ff);
		$check1 = (($check1 >> 4) & 0x3c000 ) | ($check1 & 0x3fff);	
		$t1 = (((($check1 & 0x3c0) << 4) | ($check1 & 0x3c)) <<2 ) | ($check2 & 0xf0f);
		$t2 = (((($check1 & 0xffffc000) << 4) | ($check1 & 0x3c00)) << 0xa) | ($check2 & 0xf0f0000);
		return ($t1 | $t2);
	}

	// Genearate a checksum for the hash string
	function checksum($hashnum) {
		$checkbyte = 0;
		$flag = 0;
		$hashstr = sprintf('%u', $hashnum) ;
		$length = strlen($hashstr);
		for ($i = $length - 1;  $i >= 0;  $i --) {
			$re = $hashstr{$i};
			if (1 === ($flag % 2)) {              
				$re += $re;     
				$re = (int)($re / 10) + ($re % 10);
			}
			$checkbyte += $re;
			$flag ++;	
		}
		$checkbyte %= 10;
		if (0 !== $checkbyte) {
			$checkbyte = 10 - $checkbyte;
			if (1 === ($flag % 2) ) {
				if (1 === ($checkbyte % 2)) {
					$checkbyte += 9;
				}
				$checkbyte >>= 1;
			}
		}
		return '7'.$checkbyte.$hashstr;
	}

   // Use curl the get the file contents
   function file_get_contents_curl($url) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_HEADER, 1); // 0
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Set curl to return the data instead of printing it to the browser.
//echo 'test '.$_SERVER['HTTP_USER_AGENT'];
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; GoogleToolbar 2.0.114-big; Windows XP 5.1)rn');
	  curl_setopt($ch, CURLOPT_URL, $url);

      curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);	//Le temps maximum d'exécution de la fonction cURL.
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout); //Le nombre de secondes à attendre durant la tentative de connexion. Utilisez 0 pour attendre indéfiniment. 

      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
   }


function check_fsockopen($page) {

    // Open a socket to the toolbarqueries address, used by Google Toolbar
    $socket = @fsockopen($this->host, 80, $errno, $errstr, 3); // del @ for test

    // If a connection can be established
    if($socket) {

      $out = "GET /tbr?client=navclient-auto&ch=".$this->checksum($this->makehash($page)).
              "&features=Rank&q=info:".$page."&num=100&filter=0 HTTP/1.1\r\n";

      $out .= "Host: toolbarqueries.google.com\r\n";
      $out .= "User-Agent: Mozilla/4.0 (compatible; GoogleToolbar 2.0.114-big; Windows XP 5.1)\r\n";
      $out .= "Connection: Close\r\n\r\n";

      // Write settings to the socket
      fwrite($socket, $out);

      // When a response is received...
      $result = "";
      while(!feof($socket)) {
        $data = fgets($socket, 128);
        //echo 'Test = '.$data.'<br>';
		$pos = strpos($data, "Rank_");
        if($pos !== false){
		  $pagerank = $data;
		}
      }
/*
Résultat du while
Test = HTTP/1.1 200 OK
Test = Date: Thu, 19 Jan 2012 21:08:54 GMT
Test = Pragma: no-cache
Test = Expires: Fri, 01 Jan 1990 00:00:00 GMT
Test = Cache-Control: no-cache, must-revalidate
Test = Content-Type: text/html; charset=ISO-8859-1
Test = Set-Cookie: PREF=ID=361eec1c3e6f8cef:FF=0:TM=1327007334:LM=1327007334:S=6i-WsAnSERXQE95K; expires=Sat, 18-Jan-2014 21:08:54 GMT
Test = ; path=/; domain=.google.com
Test = Server: gws
Test = X-XSS-Protection: 1; mode=block
Test = X-Frame-Options: SAMEORIGIN
Test = Connection: close
Test =
Test = Rank_1:1:0 
*/
      // Close the connection
      fclose($socket);

      // Return the rank!
		settype($pagerank, "string") ;
		return trim($pagerank);
    }
  }

}
?>
