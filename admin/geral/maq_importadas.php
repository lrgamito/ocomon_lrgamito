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

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<head>";
	print "</head>";
	print "<BODY bgcolor=".BODY_COLOR." >"; 

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1,'helpconfigimport.php');
        
        print "<p style='color:red;'><b>Opção ainda não funcional.</b></p><BR><B>".TRANS('TTL_CONFIG_IMPORTER','Máquinas Importadas').":</b><BR>";
        $query = "SELECT * FROM config_import ";
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

                                
				print "<tr><td><b>".TRANS('OPT_IMP_SITUAC','SITUAÇÃO PADRÃO')."</b></td>";
                                $sqlStatus = "SELECT * FROM `situacao` WHERE situac_cod = ".$row['conf_imp_situac']."";
                                     $execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
                                     $rowStatus = mysql_fetch_array($execStatus);
				print "<td>".$rowStatus['situac_nome']."</td>";
				print "</tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				print "<tr><td><b>".TRANS('OPT_IMP_LOCAL','LOCAL PADRÃO')."</b></td>";
                                $sqlStatus = "SELECT * FROM `localizacao` WHERE loc_id = ".$row['conf_imp_local']."";
                                     $execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
                                     $rowStatus = mysql_fetch_array($execStatus);
				print "<td>".$rowStatus['local']."</td>";
				print "</tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				
				print "<tr><td><b>".TRANS('OPT_IMP_MARCA','MARCA PADRÃO')."</b></td>";
                                $sqlStatus = "SELECT * FROM `fabricantes` WHERE fab_cod = ".$row['conf_imp_marca']."";
                                     $execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
                                     $rowStatus = mysql_fetch_array($execStatus);
				print "<td>".$rowStatus['fab_nome']."</td>";
				print "</tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				print "<tr><td><b>".TRANS('OPT_IMP_INST','INSTITUIÇÃO PADRÃO')."</b></td>";
                                $sqlStatus = "SELECT * FROM `instituicao` WHERE inst_cod = ".$row['conf_imp_inst']."";
                                     $execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
                                     $rowStatus = mysql_fetch_array($execStatus);
				print "<td>".$rowStatus['inst_nome']."</td>";
				print "</tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";


                                print "<tr><td><b>".TRANS('OPT_IMP_SOFT','FAZ INV DE SOFTWARE?')."</b></td>";
                                
                                if($row['conf_imp_soft']=='1'){
                                    $SOFT = "checked";
                                } else {
                                    $SOFT = "";
                                }
                                
                                print "<td><input type='checkbox' name='imp_soft' disabled ".$SOFT."></td>";
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

		print "<tr><td><b>".TRANS('OPT_IMP_SITUAC','SITUAÇÃO PADRÃO')."</b></td>";
		print "<td><select name='imp_situac' id='idImp_situac' class='select'>"; 
                        $sqlStatus = "SELECT * FROM `situacao` ORDER BY situac_nome";
			$execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
			while ($rowStatus = mysql_fetch_array($execStatus)) {
				print "<option value='".$rowStatus['situac_cod']."' ";
					if ($rowStatus['situac_cod'] == $row['conf_imp_situac'])
						print " selected";
					print ">".$rowStatus['situac_nome']."</option>";
			}
		print "</select>";
		print "</td>";
		print "</tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td><b>".TRANS('OPT_IMP_LOCAL','LOCAL PADRÃO')."</b></td>";
		print "<td><select name='imp_local' id='idImp_local' class='select'>";
                        $sqlStatus = "SELECT * FROM `localizacao` ORDER BY local";
			$execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
			while ($rowStatus = mysql_fetch_array($execStatus)) {
				print "<option value='".$rowStatus['loc_id']."' ";
					if ($rowStatus['loc_id'] == $row['conf_imp_local'])
						print " selected";
					print ">".$rowStatus['local']."</option>";
			}
		print "</select>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td><b>".TRANS('OPT_IMP_MARCA','MARCA PADRÃO')."</b></td>";
		print "<td><select name='imp_marca' id='idImp_marca' class='select'>"; 
                        $sqlStatus = "SELECT * FROM `fabricantes` ORDER BY fab_nome";
			$execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
			while ($rowStatus = mysql_fetch_array($execStatus)) {
				print "<option value='".$rowStatus['fab_cod']."' ";
					if ($rowStatus['fab_cod'] == $row['conf_imp_marca'])
						print " selected";
					print ">".$rowStatus['fab_nome']."</option>";
			}
		print "</select>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";


		print "<tr><td><b>".TRANS('OPT_IMP_INST','INSTITUIÇÃO PADRÃO')."</b></td>";
		print "<td><select name='imp_inst' id='idImp_inst' class='select'>"; 
                        $sqlStatus = "SELECT * FROM `instituicao` ORDER BY inst_nome";
			$execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
			while ($rowStatus = mysql_fetch_array($execStatus)) {
				print "<option value='".$rowStatus['inst_cod']."' ";
					if ($rowStatus['inst_cod'] == $row['conf_imp_inst'])
						print " selected";
					print ">".$rowStatus['inst_nome']."</option>";
			}
		print "</select>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";

                if($row['conf_imp_soft']=='1'){
                       $SOFT = "checked";
                } else {
                       $SOFT = "";
                }
                
		print "<tr><td><b>".TRANS('OPT_IMP_SOFT','FAZ INV DE SOFTWARE?')."</b></td>";
		print "<td><input type='checkbox' name='imp_soft' ".$SOFT."></td>";
		print "</tr>";
                print "<tr><td colspan='2'>&nbsp;</td></tr>";

                print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td><input type='submit'  class='button' name='submit' value='".TRANS('BT_ALTER','',0)."'></td>";
		print "<td><input type='reset' name='reset'  class='button' value='".TRANS('BT_CANCEL','',0)."' onclick=\"javascript:history.back()\"></td></tr>";
                
		print "</table>";
		print "</form>";
	} else

	if ($_POST['submit'] == TRANS('BT_ALTER')){
            
            if(isset($_POST['imp_soft'])){
                $mak_inv_soft = '1';
                
            } else {
                $mak_inv_soft = '0';
            }

		$qry = "UPDATE config_import SET ".
				"conf_imp_situac = '".$_POST['imp_situac']."', ".
				"conf_imp_local  = '".$_POST['imp_local']."', ".
				"conf_imp_marca  = '".$_POST['imp_marca']."', ".
				"conf_imp_inst   = '".$_POST['imp_inst']."', ".
				"conf_imp_soft   = '".$mak_inv_soft."' ".
				" ";

		//print $qry;
		//exit;
		$exec= mysql_query($qry) or die(TRANS('ERR_EDIT').$qry);

		
		//print "<script>mensagem('Configuração alterada com sucesso!'); window.open('../../index.php?LOAD=ADMIN','_parent',''); </script>";
		print "<script>mensagem('".TRANS('OK_EDIT','',0)."!'); window.open('../../index.php','_parent',''); redirect('".$_SERVER['PHP_SELF']."'); </script>";
		//redirect('configGeral.php');
	}

?>

<script type="text/javascript">
<!--
	function valida(){

		var ok = validaForm('idImp_situac','INTEIRO','SITUAÇÃO',1);
		if (ok) var ok = validaForm('idImp_local','INTEIRO','LOCAL',1);
		if (ok) var ok = validaForm('idImp_marca','INTEIRO','MARCA',1);
		if (ok) var ok =  validaForm('idImp_inst','INTEIRO','INSTITUIÇÃO',1);
		if (ok) var ok =  validaForm('idImp_soft','INTEIRO','SOFTWARE',1);

		return ok;
	}

-->
</script>
<SCRIPT LANGUAGE="JavaScript">//cp.writeDiv()</SCRIPT>
<?php 
print "</body>";
print "</html>";

?>
