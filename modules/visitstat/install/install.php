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
mysql(close) ou?

*/
$MySqlConfig = $_POST['MySqlConfig'];

$mysql_login = $_POST['mysql_login'];
$mysql_pass = $_POST['mysql_pass'];
$mysql_host = $_POST['mysql_host'];
$mysql_dbnom = $_POST['mysql_dbnom'];
$langue = $_POST['langue'];
$jetlag = $_POST['jetlag'];
$path_allmystats_abs = $_POST['path_allmystats_abs'];


require('../includes/mysql_tables.php'); //pour test --> si tables existent
require "../includes/langues/$langue.php";

	//-------------------------------------------------------------------
?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<link href="../stylesheet.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<p>&nbsp;</p><p>&nbsp;</p>
<TABLE align="center" CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_INSTALL_TITLE; ?></TH>
          </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
				<TR><TD align="center"><BR> <?

					//----------- Test de la connexion MySQL ----------------------------
					if (isset($MySqlConfig)) {
						if(!@mysql_connect($mysql_host,$mysql_login,$mysql_pass) ||trim($mysql_login)  == '' || trim($mysql_pass) == '' || trim($mysql_host) == '' 
						|| trim($mysql_dbnom) == '') {
							echo $MSG_INSTALL_MYSQL_CONNEXION_ERROR;
								echo "
								<form action='$PHP_SELF' method='post' name='Install'>
									<input name='mysql_login' type='hidden' value='$mysql_login'>
									<input name='mysql_pass' type='hidden' value='$mysql_pass'>
									<input name='mysql_host' type='hidden' value='$mysql_host'>
									<input name='mysql_dbnom' type='hidden' value='$mysql_dbnom'>
									<input name='langue' type='hidden' value='$langue'><br><br>
									<input name='jetlag' type='hidden' value='$jetlag'><br><br>
									<input type='submit' name='ReturnFormulaire' value='OK'><br><br>
								</form>";
								exit;
						} else {
							// Test de connexion à la base de données
							if(!@mysql_select_db($mysql_dbnom)) {
								echo "Le nom de la base de données n'est pas reconnu";
								echo "
								<form action='$PHP_SELF' method='post' name='Install'>
									<input name='mysql_login' type='hidden' value='$mysql_login'>
									<input name='mysql_pass' type='hidden' value='$mysql_pass'>
									<input name='mysql_host' type='hidden' value='$mysql_host'>
									<input name='mysql_dbnom' type='hidden' value='$mysql_dbnom'>
									<input name='langue' type='hidden' value='$langue'><br><br>
									<input name='jetlag' type='hidden' value='$jetlag'><br><br>
									<input type='submit' name='ReturnFormulaire' value='OK'><br><br>
								</form>";
								exit;
							}

							echo '<center>'.'Connexion MySql OK'.'</center><br><br>';

							//------------------- Ecriture du fichier config.php ----------------------
							
							@$content.="<?php\n";
							@$content.="\$mysql_host=\"$mysql_host\"; //Adresse du serveur MySQL\n";
							@$content.="\$mysql_dbnom=\"$mysql_dbnom\"; //Nom de la base de données\n";
							@$content.="\$mysql_login=\"$mysql_login\"; //Login pour accéder à la base de données\n";
							@$content.="\$mysql_pass=\"$mysql_pass\"; //Mot de passe pour accéder à la base de données\n";

							@$content.="\$site= \$_SERVER['HTTP_HOST'];	//Or http://www.site.tld \n";
							@$content.="\$langue=\"$langue\"; //francais, english \n";
							$jetlag = strtr($jetlag, " ", "+"); //si " " devant est +, replace avec + plus clair pour l'utilisateur
							@$content.="\$UTC=\"$jetlag\"; //Décalage horaire ( -12 à +12 ) Vous pouvez V&eacute;rifier la valeur avec &quot;Test UTC&quot; dans l'admin \n"; 

							@$content.="\$horloge=\"24\";	//Type 24 or 12 hours \n";

							@$content.="\$IpExlues = array(\"\");	//exclude ip ex: array(\"123.117.86.38\",\"220.181\"); 220.181 --> plage 220.181.0.0 à 220.181.255.255 \n";
							@$content.="\$Flag_Exclus_by_IP = \"\";	//Flag ip exclus ex: \"AllMyStats: IP non comptabilisée\"; \n"; 
							@$content.="\$Flag_Exclus_by_cookie = \"\";	//Falg cookie installed ex: \"AllMyStats: Visites non comptabilisées (cookie)\" \n"; 

							@$content.="\$user_login = \"admin\";	//login \n";
							@$content.="\$passwd = \"allmystats\";	//Password \n";

							@$content.= "\$path_allmystats_abs = \"$path_allmystats_abs/\"; //Pour stats_in.php - Chemin absolu de allmystats (à partir de la racine du site ) ex : /allmystats/ (avec / au début) \n";
							
							@$content.="?>\n";
							//---------------------------------------------------
							
							$filename='../config_allmystats.php';
							
							//Write 				
							if ( $file = fopen($filename, "w") ) {
								fwrite($file, $content);
								fclose($file);
							}

							//RE Test connect mais du fichier config
							require "../config_allmystats.php";
							require "../includes/langues/$langue.php";							

							$connect_mysql = mysql_connect($mysql_host,$mysql_login,$mysql_pass);
							mysql_select_db($mysql_dbnom);
							
					//-------------------------------------------------------------------
						// Test si les tables existent déjà
						$tables = mysql_query("SHOW TABLES ", $connect_mysql) or die ("MySQL query error"); 
						echo '
						<table align="center" width="75%"  border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td>';
						while (list($tablename)=mysql_fetch_array($tables)) {
							if($tablename == TABLE_ARCHIVE)	{
								$table_archive = 1;
								echo $tablename.$MSG_INSTALL_TABLE_ALREADY_EXIST.'<br>';
							} elseif ($tablename == TABLE_BAD_USER_AGENT){
								$table_bad_user_agent = 1;					
								echo $tablename.$MSG_INSTALL_TABLE_ALREADY_EXIST.'<br>';
							} elseif ($tablename == TABLE_CRAWLER){
								$table_crawler = 1;					
								echo $tablename.$MSG_INSTALL_TABLE_ALREADY_EXIST.'<br>';
							} elseif ($tablename == TABLE_DOMAINES){
								$table_domaines = 1;					
								echo $tablename.$MSG_INSTALL_TABLE_ALREADY_EXIST.'<br>';
							} elseif ($tablename == TABLE_PAGE){
								$table_page = 1;					
								echo $tablename.$MSG_INSTALL_TABLE_ALREADY_EXIST.'<br>';
							} elseif ($tablename == TABLE_VISITEUR){
								$table_visiteur = 1;					
								echo $tablename.$MSG_INSTALL_TABLE_ALREADY_EXIST.'<br>';
							}
						} //

						// ---------------------------------------------------
	
						if ($table_archive == 1 && $table_bad_user_agent == 1 && $table_crawler == 1 && 
						$table_page == 1 && $table_visiteur == 1) {
							unlink($filename);
							echo '<br><br>'.$MSG_INSTALL_ALL_TABLE_ALREADY_EXIST.'<br><br>';
							echo "
							<form action='../' method='post' name='Table_exist'>
								<input name='langue' type='hidden' value='$langue'><br><br>
								<input name='jetlag' type='hidden' value='$jetlag'><br><br>
								<input name='submiNoInstall' type='submit' value='OK'>						
							</form><br><br>";
							exit;

						} elseif ($table_archive == 1 || $table_bad_user_agent == 1 || $table_crawler == 1 || 
						$table_page == 1 || $table_visiteur == 1) {
							unlink($filename);
							echo '<br><br>'.$MSG_INSTALL_ONE_OR_TABLES_ALREADY_EXIST.'<br><br>';
							echo "
							<form action='../' method='post' name='Table_exist'>
								<input name='langue' type='hidden' value='$langue'><br><br>
								<input name='jetlag' type='hidden' value='$jetlag'><br><br>
								<input name='submiNoInstall' type='submit' value='OK'>						
							</form><br><br>";
							exit;
						}
					 		echo '</td>
						  </tr>
						</table>';

					// -------------------- Create table ------------------------------------------

						$N_table = 0;									

						$Createtable = mysql_import_file('allmystats_tables.sql', &$errmsg) ;
						if ($errmsg) {
							echo '<br>'.$errmsg.'<br><br>';							
						} else {
							$N_table = $N_table+1;									
						}

						$Createtable = mysql_import_file('../includes/sql/allmystats_table_bad_user_agent.sql', &$errmsg) ;
						if ($errmsg) {
							echo '<br>'.$errmsg.'<br><br>';							
						} else {
							$N_table = $N_table+1;									
						}

						$Createtable = mysql_import_file('../includes/sql/allmystats_table_crawler.sql', &$errmsg) ;
						if ($errmsg) {
							echo '<br>'.$errmsg.'<br><br>';							
						} else {
							$N_table = $N_table+1;									
						}
	
						if ($N_table == 3) {
							echo '<center>'.$MSG_INSTALL_TABLES_CREATED_SUCCESS.'</center><br><br>';
							echo "<br><strong><center>".$MSG_INSTALL_COMPLETE."</center></strong><br><br>";
							$httpSiteStats = 'http://'.$_SERVER['HTTP_HOST'].str_replace("install","",dirname($_SERVER["PHP_SELF"]));

							if ($langue == 'francais') {
								echo "<center>
								<form action='http://allmystats.wertronic.com/fr_end_install.php' method='post' name='end'>
									<input name='httpSiteStats' type='hidden' value='".$httpSiteStats."'>
									<input name='langue' type='hidden' value='$langue'><br>
									<input name='jetlag' type='hidden' value='$jetlag'><br><br>
									<input name='submitend' type='submit' value='".$MSG_BUTTON_NEXT_STEP."'>						
								</form></center><br><br>";

							} else {
								echo "<center>
								<form action='http://allmystats.wertronic.com/en_end_install.php' method='post' name='end'>
									<input name='httpSiteStats' type='hidden' value='".$httpSiteStats."'>
									<input name='langue' type='hidden' value='$langue'><br>
									<input name='jetlag' type='hidden' value='$jetlag'><br><br>
									<input name='submitend' type='submit' value='".$MSG_BUTTON_NEXT_STEP."'>						
								</form></center><br><br>";
							}
							exit;
						}
						
						exit;

					}// End if(!@mysql_connect($mysql_host,$mysql_login,$mysql_pass) ||trim($mysql_login)  == '' || trim($mysql_pass) == '' || trim($mysql_host) == '' 
							//|| trim($mysql_dbnom) == '') {

				} // Fin de if (isset($MySqlConfig)) {

//######################################################################################################
					// ------------ Formulaire config MySql -------------------
						?>
					<form action="<? $PHP_SELF; ?>" method="post" name="Install">
						<table align="center"  border="0" cellspacing="0" cellpadding="3">
						  <tr>
							<td><?php echo $MSG_INSTALL_MYSQL_LOGIN; ?>:</td>
							<td><input name="mysql_login" type="text" value="<?php echo $mysql_login; ?>"></td>
						  </tr>
						  <tr>
							<td><?php echo $MSG_INSTALL_MYSQL_DATABASE_NAME; ?>:</td>
							<td><input name="mysql_dbnom" type="text" value="<?php echo $mysql_dbnom; ?>"></td>
						  </tr>
						  <tr>
							<td><?php echo $MSG_INSTALL_PASS; ?>:</td>
							<td><input name="mysql_pass" type="password" value="<?php echo $mysql_pass; ?>"></td>
						  </tr>
						  <tr>
							<td><?php echo $MSG_INSTALL_MYSQL_SERVER; ?> (host):</td>
							<td><input name="mysql_host" type="text" value="<?php echo $mysql_host; ?>">&nbsp;&nbsp;localhost</td>
						  </tr>
						  <tr align="center">
						    <td colspan="2">
							<input name='langue' type='hidden' value='<?php echo $langue; ?>'><br><br>
							<input name='jetlag' type='hidden' value='<?php echo $jetlag; ?>'><br><br>
							<input type="submit" name="MySqlConfig" value="OK"><br></td>
						    </tr>
						</table>
					</form>
					<? // ------------ FIN Formulaire config MySql ------------------- ?>


</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>

</BODY>
</HTML>

<?
//------------------------------------------------
function mysql_import_file($filename, &$errmsg) 
{ 
   // lecture du fichier
   $lines = file($filename); 


   if(!$lines)  {
      $errmsg = "cannot open file $filename"; 
      return false; 
   } 

   $scriptfile = false; 

   /* Get rid of the comments and form one jumbo line */ 
   foreach($lines as $line)   {
      $line = trim($line); 

      if(!ereg('^--', $line)) {
         $scriptfile.=" ".$line.'aaa'; //aaa car problème avec les ;
      } 
   } 


   if(!$scriptfile) {
      $errmsg = "no text found in $filename"; 
      return false; 
   } 

   /* Split the jumbo line into smaller lines */ 

   $queries = explode(';aaa', $scriptfile); 


   /* Run each line as a query */

   foreach($queries as $query) {
      $query = trim($query); 
	  $query = str_replace('aaa','',$query);
	//echo 'test = '.$query .'<br>';
      
	  if(trim($query) == "") { continue; } 

      if(!mysql_query($query.';')) { 
         $errmsg = "query ".$query." failed"; 
         return false; 
      } 
   } 

   // retour true si la fonction reussie ^^
   return true; 
} 

?>

