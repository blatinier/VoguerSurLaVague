<?
/*
 -------------------------------------------------------------------------
 AllMyStats V1.39 - Statistiques de fréquentation visiteurs et robots
 -------------------------------------------------------------------------
 Copyright (C) 2008 - Herve Seywert
 -------------------------------------------------------------------------
 Web:   http://www.wertronic.com
 -------------------------------------------------------------------------
 Ce programme est libre, vous pouvez le redistribuer et/ou le modifier
 selon les termes de la Licence Publique Génrale GNU publiée par la Free
 Software Foundation .
 -------------------------------------------------------------------------
*/

	//--------------- Test si Ip non comptabilisées ------------------------- 
	$IP_PC_Exclue ='';
 	$Tab_element_Ip = explode(".",$_SERVER['REMOTE_ADDR']);

 	for($i=0;$i<count($IpExlues);$i++){
		$Tab_element_IpEx = explode(".",$IpExlues[$i]);
		$Nb = count($Tab_element_IpEx);

		for ($Ni=0; $Ni<count($Tab_element_IpEx); $Ni++) {
			if (trim($Tab_element_IpEx[$Ni])) { 
				$Ip_a_tester .= $Tab_element_Ip[$Ni].'.';
			} else { //si . et rien
				$IpExlues[$i] = substr($IpExlues[$i],0,strlen($IpExlues[$i])-1);
			}
		}
		$Ip_a_tester = substr($Ip_a_tester,0,strlen($Ip_a_tester)-1);

		if(@stristr($IpExlues[$i], $Ip_a_tester)) {
			$IP_PC_Exclue = $_SERVER['REMOTE_ADDR'];
		}
		
		$Ip_a_tester ='';
	}
 
//---------------------------------------------------------------------
?>

<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_TOOLS_MENU; ?></TH>
        </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=5 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
				<TR>
				<td>
				<?php 
					echo $MSG_INSTALL_CODE_DOC;
 				?>
				</td>
	  		</TBODY></TABLE>
		</TBODY>
	  </TABLE>
	 </TD>
	</TR>
  </TBODY>
</TABLE>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><BR>
</p>
