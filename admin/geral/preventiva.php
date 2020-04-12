<?php

/*

  This file is part of OCOMON.

  OCOMON is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  OCOMON is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with Foobar; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

  This file was created by Leandro Gamito - lr.gamito[at]gmail.com
 */


//Tela de cadastro de configuração para preventivas

session_start();

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
        print "<link rel='stylesheet' href='../../includes/css/calendar.css.php' media='screen'></LINK>";

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<head>";
        print "<script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script>";
	print "</head>";
	print "<BODY bgcolor=".BODY_COLOR." >"; 

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1,'helpconfigprev.php');
        
        print "<BR><B>".TRANS('TTL_CONFIG_PREVENTIVA').":</b><BR>";
        $query = "SELECT * FROM config_preventiva ";
        	$resultado = mysql_query($query) or die (TRANS('ERR_QUERY'));
		$row = mysql_fetch_array($resultado);

        
        if ((empty($_GET['action'])) and empty($_POST['submit'])){

		print "<br><TD align='left'>".
				"<input type='button' class='button_novo' id='idBtIncluir' value='".TRANS('BT_EDIT_CONFIG','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cellStyle=true');\">".
			"</TD><br><BR>";
		if (mysql_numrows($resultado) == 0)
		{
			echo mensagem(TRANS('ALERT_CONFIG_EMPTY'));
		}
		else
		{
				$cor=TD_COLOR;
				$cor1=TD_COLOR;
				$linhas = mysql_numrows($resultado);
				print "<td>";
				print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
				print "<TR class='header'><td>".TRANS('OPT_DIRETIVA')."</TD><td>".TRANS('OPT_VALOR')."</TD></TD></tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				print "<tr><td><b>".TRANS('OPT_PREV_CH','Chamado da Preventiva')."</b></td>";
                                     $sqlStatus = "SELECT * FROM `problemas` WHERE prob_id = ".$row['conf_num_chamado']."";
                                     $execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
                                     $rowStatus = mysql_fetch_array($execStatus);
                                       
				print "<td>".$rowStatus['problema']."</td>";
				print "</tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				print "<tr><td><b>".TRANS('OPT_PREV_ACEIT','Tempo Minimo (meses)')."</b></td>";
				print "<td>".$row['conf_tempo_min']."</td>";
				print "</tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				
				print "<tr><td><b>".TRANS('OPT_PREV_CRIT','Tempo crítico (meses)')."</b></td>";
				print "<td>".$row['conf_tempo_max']."</td>";
				print "</tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				print "<tr><td><b>".TRANS('OPT_PREV_1_PREV','Tempo para 1ª preventiva')."</b></td>";
				print "<td>".$row['conf_maq_nova']."</td>";
				print "</tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";


                                print "<tr><td><b>".TRANS('OPT_PREV_DATA_INI','Data para inicio das preventivas')."</b></td>";
				print "<td>".formatDate($row['conf_data_inic'],'')."</td>";
				print "</tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";
                                

				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				print "</TABLE>";
		}

	} else

	if ((isset($_GET['action']) && ($_GET['action']=="alter")) && empty($_POST['submit'])){


		print "<form name='alter' action='".$_SERVER['PHP_SELF']."' method='post' onSubmit=\"return valida()\">"; //onSubmit='return valida()'
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<TR class='header'><td>".TRANS('OPT_DIRETIVA')."</TD><td>".TRANS('OPT_VALOR')."</TD></TD></tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td><b>".TRANS('OPT_PREV_CH','Chamado da Preventiva')."</b></td>";
		print "<td><select name='num_chamado' id='idNum_chamado' class='select'>"; //<input type='text' name='lang' id='idLang' class='text' value='".$row['conf_language']."'></td>";
                        $sqlStatus = "SELECT * FROM `problemas` ORDER BY problema";
			$execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
			while ($rowStatus = mysql_fetch_array($execStatus)) {
				print "<option value='".$rowStatus['prob_id']."' ";
					if ($rowStatus['prob_id'] == $row['conf_num_chamado'])
						print " selected";
					print ">".$rowStatus['problema']."</option>";
			}
		print "</select>";
		print "</td>";
		print "</tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td><b>".TRANS('OPT_PREV_ACEIT','Tempo Minimo (meses)')."</b></td>";
		print "<td><input type='text' name='tempo_min' id='idTempo_min' class='text' value='".$row['conf_tempo_min']."'></td>";
		print "</tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td><b>".TRANS('OPT_PREV_CRIT','Tempo crítico (meses)')."</b></td>";
		print "<td><input type='text' name='tempo_max' id='idTempo_max' class='text' value='".$row['conf_tempo_max']."'></td>";
		print "</tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";


		print "<tr><td><b>".TRANS('OPT_PREV_1_PREV','Tempo para 1ª preventiva')."</b></td>";
		print "<td><input type='text' class='text' name='maq_nova' id='idMaq_nova' value='".$row['conf_maq_nova']."'></td>";
		print "</tr>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";


		print "<tr><td><b>".TRANS('OPT_PREV_DATA_INI','Data para inicio das preventivas')."</b></td>";
		//print "<td><input type='text' class='text' name='data_inic' id='idData_inic' value='".$row['conf_data_inic']."'></td>";
		print "<td><INPUT type='text' name='data_inic' class='data' id='idData_inic' value='".formatDate($row['conf_data_inic'],'0')."'><a onclick=\"displayCalendar(document.forms[0].data_inic,'dd/mm/yyyy',this)\"><img height='14' width='14' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('HNT_SEL_DATE')."'></a></td>";
                print "</tr>";
                print "<tr><td colspan='2'>&nbsp;</td></tr>";
                
                
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td><input type='submit'  class='button' name='submit' value='".TRANS('BT_ALTER','',0)."'></td>";
		print "<td><input type='reset' name='reset'  class='button' value='".TRANS('BT_CANCEL','',0)."' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if ($_POST['submit'] == TRANS('BT_ALTER')){

		$qry = "UPDATE config_preventiva SET ".
				"conf_num_chamado = '".$_POST['num_chamado']."', ".
				"conf_tempo_min = '".$_POST['tempo_min']."', ".
				"conf_tempo_max = '".$_POST['tempo_max']."', ".
				"conf_maq_nova  = '".$_POST['maq_nova']."', ".
				"conf_data_inic = '".converte_dma_para_amd($_POST['data_inic'])."' ".
				" ";

		//print $qry;
		//exit;
		
                //$exec = mysql_query($qry) or die(TRANS('ERR_EDIT').$qry);

		
		
		print "<script>mensagem('".TRANS('OK_EDIT','',0)."!'); window.open('../../index.php','_parent',''); redirect('".$_SERVER['PHP_SELF']."'); </script>";
		
	}

?>

<script type="text/javascript">
<!--
	function valida(){

		var ok = validaForm('idNum_chamado','INTEIRO','NUMERO DO CHAMADO',1);
		if (ok) var ok = validaForm('idTempo_min','INTEIRO','TEMPO MINIMO',1);
		if (ok) var ok = validaForm('idTempo_max','INTEIRO','TEMPO MAXIMO',1);
		if (ok) var ok =  validaForm('idMaq_nova','INTEIRO','TEMPO MAQUINAS NOVAS',1);
		if (ok) var ok =  validaForm('idData_inic','DATA-','DATA INICIO',1);

		return ok;
	}

-->
</script>
<SCRIPT LANGUAGE="JavaScript">//cp.writeDiv()</SCRIPT>
<?php 
print "</body>";
print "</html>";

?>
