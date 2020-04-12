<?php session_start();
 /*                        Copyright 2005 Fl?vio Ribeiro

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
  */

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");


	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

	$imgsPath = "../../includes/imgs/";
	//$hoje = date("Y-m-d H:i:s");

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4,'helpincluirch.php');

	$qry_config = "SELECT * FROM config ";
	$exec_config = mysql_query($qry_config) or die (TRANS('ERR_QUERY'));
	$row_config = mysql_fetch_array($exec_config);


	$qry = $QRY["useropencall_custom"];

	if(!empty($_SESSION['s_screen'])){
		$qry.= " AND  c.conf_cod = '".$_SESSION['s_screen']."'";
	}

   $qryarea = "SELECT * FROM sistemas where sis_id = ".$_SESSION['s_area']."";
	$execarea = mysql_query($qryarea) or die (TRANS('ERR_QUERY'));
	$rowarea = mysql_fetch_array($execarea);
        
        if(mysql_num_rows($execarea) == 0){
            print 'CONFIGURA«√O DE ¡REA N√O ENCONTRADA. INCLUA AS ¡REAS. ';
        }

	$execqry = mysql_query($qry);
	$rowconf = mysql_fetch_array($execqry);


	$qryconfglobal = $QRY["useropencall_custom"];
	$qryconfglobal .= " and c.conf_cod = ";// + $rowarea['sis_screen'];
	$qryconfglobal .= $rowarea['sis_screen'];
	$execqryglobal = mysql_query($qryconfglobal);
	$rowconf_global = mysql_fetch_array($execqryglobal);

	print "<HTML>";
	print "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>";
	print "<BODY bgcolor=".BODY_COLOR." onLoad=\"";//onLoad=\"Habilitar();

   if ($rowconf_global['conf_scr_prob']) {
		print "ajaxFunction('Problema', 'showSelProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea','radio_prob=idRadioProb', 'area_habilitada=idAreaHabilitada');";
		print "ajaxFunction('divProblema', 'showProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea', 'radio_prob=idRadioProb'); ";

		print "ajaxFunction('divInformacaoProblema', 'showInformacaoProb.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea'); ";

	}

	
	
   if ($rowconf_global['conf_scr_local']) {
		if (((!empty($rowconf) && $rowconf['conf_scr_unit']) || empty($rowconf))  && ((!empty($rowconf) && $rowconf['conf_scr_tag']) || empty($rowconf))) {
			print "ajaxFunction('idDivSelLocal', 'showSelLocais.php', 'idLoad', 'unidade=idUnidade', 'etiqueta=idEtiqueta'); ";
		} else
			print "ajaxFunction('idDivSelLocal', 'showSelLocais.php', 'idLoad'); ";
	}
	if ((!empty($rowconf) && $rowconf['conf_scr_foward']) || empty($rowconf)) {
		print "ajaxFunction('divOperator', 'showOperators.php', 'idLoad');";
	}

	print "\">";

	//if (!$rowconf['conf_user_opencall'] and !$rowarea['sis_atende']){ //VER
	if ((!empty($rowconf) && !$rowconf['conf_user_opencall'])) {
			print "<script>mensagem('".TRANS('MSG_DISABLED_OPENCALL','A abertura de chamados est√° desabilitada no sistema',0)."!'); redirect('abertura.php');</script>";
	}


	if (isset($_REQUEST['pai'])) {

		$sql = "select o.*, s.* from ocorrencias o, `status` s where o.`status` = s.stat_id and s.stat_painel not in (3) and o.numero = ".$_REQUEST['pai']."";
		$execSql = mysql_query($sql) or die (TRANS('ERR_QUERY'));
		$ocoOK = mysql_num_rows ($execSql);
		if ($ocoOK != 0) {
			$subCallMsg = "<font color='red'>".TRANS('MSG_OCCO_SUBTICKET')."&nbsp;".$_REQUEST['pai']."</font>";
		} else {
			//$subCallMsg = "<font color='red'>A ocorrencia ".$_REQUEST['pai']." n?o pode possuir subchamados pois n?o est? aberta no sistema!</font>";
			print "<script>mensagem('A ocorrencia ".$_REQUEST['pai']." nao pode possuir subchamados pois nao esta aberta no sistema!'); window.close();</script>";
			exit;
		}

	} else $subCallMsg = "";


print "<BR><B>".TRANS('OCO_TTL_OPENCALL','Abertura de Ocorr?ncias').":&nbsp;".$subCallMsg."</B><BR>";
print "<FORM name='form1' method='POST' action='".$_SERVER['PHP_SELF']."'  ENCTYPE='multipart/form-data'  onSubmit=\"return valida()\">";
	print "<input type='hidden' name='MAX_FILE_SIZE' value='".$row_config['conf_upld_size']."' />";

        print "<TABLE border='0'  align='left' width='1100' bgcolor='".BODY_COLOR."'>";


	if (isset($_POST['carrega'])){

		$sqlTag = "select c.*, l.* from equipamentos c, localizacao l where c.comp_local=l.loc_id and c.comp_inv=".$_POST['equipamento']." and c.comp_inst=".$_POST['instituicao']."";
		$execTag = mysql_query($sqlTag);
		$rowTag = mysql_fetch_array($execTag);

		//$invTag = $rowTag['comp_inv'];
		$invTag = $_POST['equipamento'];
		$invInst = $rowTag['comp_inst'];
		$invLoc = $rowTag['comp_local'];
		$contato = $_POST['contato'];
		$telefone = $_POST['telefone'];

		if (isset($_POST['radio_prob'])){
			$radio_prob = $_POST['radio_prob'];
		} else $radio_prob = -1;

		if (isset($_POST['problema'])) 	{
			$problema = $_POST['problema'];
		}else {
			$problema = -1;
		}

		if (isset($_POST['foward'])){
			$foward = $_POST['foward'];
		} else {
			$foward = -1;
		}

	} else {

		$invTag = "";
		$invInst = "";
		$invLoc = "";
		$contato = "";
		$telefone = "";
		if (isset($_POST['problema'])) 	{
			$radio_prob = $_POST['problema'];
			$problema = $_POST['problema'];
		}else {
			$radio_prob = -1;
			$problema = -1;
		}

		if (isset($_POST['foward'])){
			$foward = $_POST['foward'];
		} else {
			$foward = -1;
		}

	}

		print "<TR>";

		if ($rowconf_global['conf_scr_area']) {
			print "<TD width='15%' style='padding:5px;' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_AREA').":</TD>";
        		print "<TD width='20%' align='left' bgcolor=".BODY_COLOR.">";
			print "<SELECT class='select' name='sistema' id='idArea' size='1' onChange=\" "; //onChange=\"Habilitar();

      if ($rowconf_global['conf_scr_prob']) {
				//print "fillSelectFromArray(this.form.problema, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1])); ";
				print "ajaxFunction('Problema', 'showSelProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea', 'area_habilitada=idAreaHabilitada');";
				print "ajaxFunction('divProblema', 'showProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea');";
			}

				print "ajaxFunction('divInformacaoProblema', 'showInformacaoProb.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea'); ";

			if ((!empty($rowconf) && $rowconf['conf_scr_foward']) || empty($rowconf)) {
				print "ajaxFunction('divOperator', 'showOperators.php', 'idLoad', 'area_cod=idArea');";
			}


			print "\">";

            		//$query = "SELECT * from sistemas where sis_status NOT in (0) and sis_atende = 1 order by sistema"; //NOT in (0) = INATIVO
			$query = "SELECT s.* from sistemas s, areaXarea_abrechamado a WHERE s.sis_status NOT IN (0) AND s.sis_atende = 1 AND s.sis_id = a.area AND a.area_abrechamado IN (".$_SESSION['s_uareas'].") GROUP BY sistema ORDER BY sistema"; //NOT in (0) = INATIVO
			$resultado = mysql_query($query);
            		print "<option value=-1 selected>".TRANS('OCO_SEL_AREA')."</option>";

			if (isset($_POST['sistema'])) {
				$sistema= $_POST['sistema'];
			} else
				$sistema = "-1";

			while ($rowArea=mysql_fetch_array($resultado)){
				print "<option value='".$rowArea['sis_id']."'";
					if ($rowArea['sis_id']==$sistema) print " selected";
				print ">".$rowArea['sistema']."</option>";
			}
			print "</select>";
			print "</td>";
			print "<input type='hidden' name='areaHabilitada' id='idAreaHabilitada' value='sim'>";
		} else  {
			$sistema = $rowconf['conf_opentoarea'];  //$sistema = -1;
			print "<input type='hidden' name='sistema' id='idArea' value='".$sistema."'>";
			print "<input type='hidden' name='areaHabilitada' id='idAreaHabilitada' value='nao'>";
		}

      if ($rowconf_global['conf_scr_prob']) {
			print "<TD width='15%' style='padding:5px;' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_PROB','Problema').":";

			print "</TD>";
			print "<TD width='20%' align='left' bgcolor=".BODY_COLOR.">";
				print "<div id='Problema'>";
					print "<input type='hidden' name='problema' id='idProblema' value='".$problema."'>";
					//print "<input type='hidden' name='problema' id='idProblema' value='-1'>";
				print "</div>";
            		print "</TD>";


		} else {
			$problema = -1;
			//print "<input type='hidden' name='problema' id='idProblema' value='".$problema."'>";
		}

		print "</TR>";

#################################################

			print "<tr><td colspan='6' ><div id='divProblema'>"; //style='{display:none}'
				print "<input type='hidden' name='radio_prob' id='idRadioProb' value='".$radio_prob."'>"; //id='idRadioProb'
			print "</div></td></tr>";

			print "<tr><td colspan='6' ><div id='divInformacaoProblema'></div></td></tr>";

##################################################

		print "<div id='idLoad' class='loading' style='display:none'><img src='../../includes/imgs/loading.gif'></div>";

		print "<TR>";

      if ($rowconf_global['conf_scr_desc']) {
			print "<TD width='15%' style='padding:5px;' align='left' bgcolor=".TD_COLOR." valign='top'>".TRANS('OCO_FIELD_DESC').":</TD>";
			print "<TD colspan='3' align='left' bgcolor=".BODY_COLOR.">";
                        
                        if (isset($_GET['descricao'])){
                                $descricao = $_GET['descricao'];
                        } else
			if (isset($_POST['descricao'])) {
				$descricao = $_POST['descricao'];
			} else
				$descricao = "";


			if (!$_SESSION['s_formatBarOco']) {
				print "<TEXTAREA class='textarea' name='descricao' id='idDescricao'  >".noHtml($descricao)."</textarea>"; //onChange=\"Habilitar();\"
			} else {
				print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
				?>
				<script type="text/javascript">
					var bar = '<?php print $_SESSION['s_formatBarOco'];?>'
					if (bar ==1) {
						var oFCKeditor = new FCKeditor( 'descricao' ) ;
						oFCKeditor.BasePath = '../../includes/fckeditor/';
						oFCKeditor.Value = '<?php print $descricao;?>';
						oFCKeditor.ToolbarSet = 'ocomon';
						//oFCKeditor.ToolbarSet = 'Basic';
						oFCKeditor.Width = '570px';
						oFCKeditor.Height = '100px';
						oFCKeditor.Create() ;
					}
				</script>
				<?php
			}
			print "</td>";

		} else {
			$descricao = TRANS('OCO_NO_DESC');
			print "<input type='hidden' name='descricao' value='".$descricao."'>";
		}
		print "</tr>";

		print "<TR>";
      if ($rowconf_global['conf_scr_unit']) {
			print "<TD width='15%' style='padding:5px;' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_UNIT','Unidade').":</TD>";
            		print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";
			
                        print "<SELECT class='select' name='instituicao' id='idUnidade' size='1' >"; //onChange=\"Habilitar();\"
			print "<option value=null selected>".TRANS('OCO_SEL_UNIT','Selecione a unidade')."</option>";

			$query2 = "SELECT * from instituicao WHERE inst_status not in (0) order by inst_cod";
			$resultado2 = mysql_query($query2);
			$linhas = mysql_numrows($resultado2);

			if (isset($_GET['invInst'])){
				$invInst = $_GET['invInst'];
			} else
			if (isset($_POST['instituicao'])){
				$invInst = $_POST['instituicao'];
			}

			while ($rowInst = mysql_fetch_array($resultado2))
			{
				print "<option value=".$rowInst['inst_cod']."";
					if ($rowInst['inst_cod']== $invInst) print " selected";
				print ">".$rowInst['inst_nome']."</option>";
			}

            		print "</SELECT>";
			print "</td>";
		} else {
			$instituicao = -1;
			print "<input type='hidden' name='instituicao' value='-1'>";
		}

      if ($rowconf_global['conf_scr_tag']) {
			print "<TD width='15%' style='padding:5px;' align='left' bgcolor=".TD_COLOR.">";
			print "".TRANS('OCO_FIELD_TAG','Etiqueta')."";
			//if ($rowconf['conf_scr_chktag'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
			if ((!empty($rowconf) && $rowconf['conf_scr_chktag']) || empty($rowconf)) {
				print "</font></a></b>";
			}
			print "&nbsp;".TRANS('OCO_FIELD_OF_EQUIP','do equipamento').":</TD>";

            		if (isset($_GET['invTag'])) {
            			$invTag = $_GET['invTag'];
            		} //else $invTag = "";
            		else
            		if (isset($_POST['equipamento'])) {
            			$invTag = $_POST['equipamento'];
            		}

            		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text2' name='equipamento' id='idEtiqueta' value='".$invTag."' onblur=\"ajaxFunction('idDivSelLocal', 'showSelLocais.php', 'idLoad', 'unidade=idUnidade', 'etiqueta=idEtiqueta');\">";//onChange=\"Habilitar();\"

         if ($rowconf_global['conf_scr_chktag']) {
				print "&nbsp;&nbsp;<a style='cursor:pointer;' onClick=\"checa_etiqueta()\" title='".TRANS('CONS_CONFIG_EQUIP')."'>&nbsp;<img src='".ICONS_PATH."kcontrol.png' alt='ConfiguraÁ„o do equipamento'></a>";
			}

        if ($rowconf_global['conf_scr_chkhist']) {
				//print "<a class='likebutton' onClick=\"checa_etiqueta()\" title='Consulta a configura??o do equipamento!'><font color='#5E515B'>Configura??o</font></a>";
				print "&nbsp;&nbsp;<a style='cursor:pointer;' onClick=\"checa_chamados()\" title='".TRANS('CONS_CALL_EQUIP')."'>&nbsp;<img src='".ICONS_PATH."image-loading.png' alt='HistÛrico de chamados'></a>";
			}
			print "</TD>";
		} else {
			$equipamento = null;
			print "<input type='hidden' name='equipamento' value=".NULL.">";
		}

		print "</tr>";

        	print "<TR>";
      if ($rowconf_global['conf_scr_contact']) {
			print "<TD width='15%' style='padding:5px;' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_CONTACT','Contato').":</TD>";

			if (isset($_GET['contato'])) {
				$contato = $_GET['contato'];
			} //else $contato = "";
			else {
				if (isset($_POST['contato'])) {
					$contato = $_POST['contato'];
				}
				else {
					if ($_SESSION['s_nivel'] == 3) {
						$contato = isset($_SESSION['nome']);
					}
				}
			}
			print "<TD width='30%' align='left' bgcolor=".BODY_COLOR."><INPUT type='text' class='text' name='contato' id='idContato' value='".$contato."' ></TD>";//onChange=\"Habilitar();\"  onBlur=\"Habilitar();\"
		} else {
			$qry = "select nome from usuarios where user_id = ".$_SESSION['s_uid']."";
			$exec = mysql_query($qry);
			$r_user = mysql_fetch_array($exec);
			$contato = $r_user['nome'];
			print "<input type='hidden' name='contato' value='".$contato."'>";
		}
      if ($rowconf_global['conf_scr_fone']) {
			print "<TD width='15%' style='padding:5px;' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_PHONE','Ramal').":</TD>";

			if (isset($_GET['telefone'])) {
				$telefone = $_GET['telefone'];
			} //else $telefone = "";
			else
			if (isset($_POST['telefone'])) {
				$telefone = $_POST['telefone'];
			}
	            	print "<TD width='30%' align='left' bgcolor=".BODY_COLOR."><INPUT type='text' class='text2' name='telefone' id='idTelefone' value='".$telefone."' ></TD>";//onChange=\"Habilitar();\"
        	} else {
        		$telefone = null;
        		print "<input type='hidden' name='telefone' value=".NULL.">";
        	}
		print "</TR>";

		print "<TR>";

      if ($rowconf_global['conf_scr_local']) {
			print "<TD width='15%' style='padding:5px;' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_LOCAL','Local').": ";
				//if ($rowconf['conf_scr_btloadlocal'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
				if ((!empty($rowconf) && $rowconf['conf_scr_btloadlocal']) || empty($rowconf)) {
 					//print "<input type='submit' class='btPadrao' id='idBtCarrega' title='".TRANS('LOAD_EQUIP_LOCAL')."'onClick=\"LOAD=1;\"".
 						//"style=\"{align:center; valign:middle; width:19px; height:19px; background-image: url('../../includes/icons/kmenu-hack.png'); background-repeat:no-repeat;}\" value='' name='carrega'>";

 					//print "<input type='button' class='btPadrao' id='idBtCarrega' title='".TRANS('LOAD_EQUIP_LOCAL')."' ".
 					//		"onClick=\"ajaxFunction('idDivSelLocal', 'showSelLocais.php', 'idLoad', 'unidade=idUnidade', 'etiqueta=idEtiqueta');\"".
 					//		"style=\"{align:center; valign:middle; width:19px; height:19px; background-image: url('../../includes/icons/kmenu-hack.png'); background-repeat:no-repeat;}\" value='' name='carrega'>";

				}
			print "</TD>";


				//<!--{ background-image: url('/images/css.gif');} -->
			print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";

			if (isset($_GET['invLoc'])){
				$invLoc = $_GET['invLoc'];
			} else
			if (!isset($_POST['carrega'])){
				if (isset($_POST['local'])){
					$invLoc = $_POST['local'];
				}
			}


				print "<div id='idDivSelLocal' style='width:100px'>";
            print "<select class=\"select\" name=\"local\" id=\"idLocal\">" .
						"<option value=null selected>Selecione a localiza√ß√£o</option>";
            $query = "SELECT * from localizacao where loc_status = 1 order by local";
			   $resultado = mysql_query($query);
				while ($rowLocal = mysql_fetch_array($resultado))
				{
					print "<option value='".$rowLocal['loc_id']."'>".$rowLocal['local']."</option>";
				}
                 print "</select>";
				
				print "</div>";
				
				if ($rowconf_global['conf_scr_searchbylocal']) {
					print "<div style='position: absolute; margin-top: -18px; margin-left: 201px; cursor:pointer;'><a onClick=\"checa_por_local()\">&nbsp;&nbsp;<img style=\" position: relative; top: 0px;\" title='".TRANS('CONS_EQUIP_LOCAL')."' width='16' height='16' src='".$imgsPath."consulta.png' border='0'></a></div>";
					
				}

			print "</td>";

		} else {
			$local = -1;
			print "<input type='hidden' name='local' value='-1'>";
		}

      if ($rowconf_global['conf_scr_operator']) {
			print "<TD width='15%' style='padding:5px;' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_OPERATOR','Operador').":</TD>";
			print "<TD width='30%' align='left' bgcolor=".BODY_COLOR."><input class='disable' value='".$_SESSION['s_usuario']."' readonly></TD>";
		} else {
			$operador = $_SESSION['s_usuario'];
			print "<input type='hidden' name='operador' value='".$operador."'>";
		}
        	print "</TR>";


        	print "<TR>";

      if ($rowconf_global['conf_scr_date']) {
			print "<TD width='20%' style='padding:5px;' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_OPEN','Data de abertura').":</TD>";
                	print "<TD width='30%' align='left' bgcolor=".BODY_COLOR."><input name='data_abertura' class='disable' value='".date("d/m/Y H:i:s")."' readonly></TD>";//datab($hoje)
		}
      if ($rowconf_global['conf_scr_status']) {
			print "<TD width='20%'  style='padding:5px;' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_STATUS','Status').":</TD>";
                	print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">".TRANS('OCO_WAITING_STATUS','Aguardando atendimento')."</TD>";
		}
        	print "</TR>";

        	print "<TR>";

      if ($rowconf_global['conf_scr_schedule']) {
			print "<TD width='20%' style='padding:5px;' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_SCHEDULE').": <input type='checkbox' value='ok' name='chk_squedule' onChange=\"checarSchedule();\"></TD>";
                	print "<TD width='30%' align='left' bgcolor=".BODY_COLOR."><input type='text' name='date_schedule' id='idDate_schedule' class='text' value='".formatDate(date("Y-m-d H:i:s"))."' disabled></TD>";
		}

      if ($rowconf_global['conf_scr_replicate']) {
			print "<TD width='20%' style='padding:5px;' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_REPLICATE','Replicar este chamado mais')."</TD>";
		print "<TD  bgcolor=".BODY_COLOR."><INPUT type='text' class='mini' name='replicar' id='idReplicar' value='0' maxlength='2'>&nbsp;".TRANS('TIMES','vezes').".</TD> ";
		} else $replicar = 0;

        	print "</TR>";

		print "<tr>";

      if ($rowconf_global['conf_scr_prior']) {
			print "<TD width='20%' style='padding:5px;' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_PRIORITY').":</TD>";
			print "<TD  width='30%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<select name='prioridade' class='select' id='idPrioridade'>";

				$sql = "select * from prior_atend where pr_default = 1 ";
				$commit1 = mysql_query($sql);
				$rowR = mysql_fetch_array($commit1);
					print "<option value=-1>".TRANS('OCO_PRIORITY')."</option>";
						$sql2="select * from prior_atend order by pr_nivel";
						$commit2 = mysql_query($sql2);
						while($rowB = mysql_fetch_array($commit2)){
							print "<option value=".$rowB["pr_cod"]."";
							if ($rowB['pr_cod'] == $rowR['pr_cod'] ) {
								print " selected";
							}
							print ">".$rowB["pr_desc"]."</option>";
						} // while

				print "</select>";
				print "</td>";
		} else {
			$sql = "select * from prior_atend where pr_default = 1 ";
			$commit1 = mysql_query($sql);
			$rowR = mysql_fetch_array($commit1);
			print "<input type='hidden' name='prioridade' value='".$rowR['pr_cod']."'>";
		}

      if ($rowconf_global['conf_scr_foward']) {
			print "<TD width='20%' style='padding:5px;' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_FOWARD').":</TD>";
			print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";
				print "<div id='divOperator'>";
					print "<input type='hidden' name='foward' id='idFoward' value='".$foward."'>";
					//print "<input type='hidden' name='problema' id='idProblema' value='-1'>";
				print "</div>";
            		print "</TD>";

		}

		print "</tr>";

      if ($rowconf_global['conf_scr_foward']) {
        $query_user = "SELECT user_id, nome from usuarios where nivel != 5 order by login";
			$exec_user = mysql_query($query_user);
			print "<tr>";
				print "<TD width='20%' style='padding:5px;' align='left' bgcolor=".TD_COLOR.">".TRANS('FIELD_OPEN_BY').":</TD>";
				print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";
					print "<SELECT class='select' name='openby' id='idOpenby' onChange=\"checkMailUser(); preencheContato();\">";
						print "<option value='-1' selected>".TRANS('OCO_SEL_USER')."</option>";
							while ($row_user = mysql_fetch_array($exec_user)) {
							print "<option value=".$row_user['user_id'].">".$row_user['nome']."</option>";
						}
					print "</SELECT>";
            print "</TD>";
        print "</tr>";
		}


		/* ----------------- INICIO ALTERACAO ----------------- */
		print "<tr>";
		print "<td colspan='4'>";
      if ($rowconf_global['conf_scr_upload']) {
			for($i=1;$i<=$row_config['conf_qtd_max_anexos']; $i++){
				$estilo = 'width: 100%; margin: 0; height: 30px; margin-bottom: 2px;';
				if($i > 1)
					$estilo .= " display: none;";
				print "<div id='tr_anexo_$i' style=' $estilo '>";
				//print "<tr id='tr_anexo_$i' $estilo>";
					print "<div style='width: 20%; height: 100%; background-color: ".TD_COLOR."; float: left; margin: -1px; padding: 5px;'>".TRANS('OCO_FIELD_ATTACH_FILE','Anexar arquivo').":</div>";
					print "<div style='width: 70%; background-color: ".BODY_COLOR."; float: left; margin-left: 5px;'>";
					print "		<INPUT type='file' class='text' name='anexo_$i' id='id_anexo_$i' />";
					if($i != $row_config['conf_qtd_max_anexos']){
						print "		&nbsp;&nbsp;";
						print "<a id='link_adic_$i' style='cursor:pointer;'
									onclick=\"
									javascript:document.getElementById('tr_anexo_".($i+1)."').style.display='block';
									document.getElementById('link_adic_".($i)."').style.display='none';
                                                                        \"><img src='".ICONS_PATH."mais.png' alt='Anexar outro arquivo'></a>";
					}
					print "</div>";
				print "</div>";
			}
		}
		print "</td>";
		print "</tr>";
		/* ----------------- FIM ALTERACAO ----------------- */


		print "<tr>";
      if ($rowconf_global['conf_scr_mail']) {
			print "<td style='padding: 5px;' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_SEND_MAIL_TO','Enviar e-mail para').":</td>".
				"<td colspan='2'><input type='checkbox' value='ok' name='mailAR' >".TRANS('OCO_FIELD_AREA','')."&nbsp;&nbsp;".
								"<input type='checkbox' value='ok' name='mailOP' title='".TRANS('HNT_SENDMAIL_OPERATOR_SEL_CALL')."'>".TRANS('OCO_FIELD_OPERATOR')."&nbsp;&nbsp;".
								"<input type='checkbox' value='ok' name='mailUS'>".TRANS('OCO_FIELD_USER','Usu?rio')."</td>";
		}
      else {
			print "<INPUT TYPE='hidden' NAME='mailAR' VALUE='ok'>";
			print "<INPUT TYPE='hidden' NAME='mailUS' VALUE='ok'>";
		}
		print "</tr>";


		if (!empty($invTag)){
			$saida = "javascript:window.close()";
		} else
			$saida = "javascript:location.href='abertura.php'";



		print "<TR>";
	        print "<BR>";

		if (isset($_REQUEST['pai'])) {
			print "<input type='hidden' name='pai' value='".$_REQUEST['pai']."'>";
		}

			print "<input type='hidden' name='data_gravada' value='".date("Y-m-d H:i:s")."'>";


                print "<tr><td colspan='4' style='height:10px;'></td></tr>";
                            
		print "<TD colspan='2' align='center' width='50%' bgcolor='".BODY_COLOR."'>
                        
                        <input type='submit' id='idSubmit' class='button_ok' value='".TRANS('BT_OK','OK', 0)."' name='OK' onClick=\"LOAD=0;\">";
		print "</TD>";

		print "<TD colspan='2' align='center' width='50%' bgcolor='".BODY_COLOR."'>
                        
                        <INPUT type='button' class='button_ca' value='".TRANS('BT_CANCEL','Cancelar',0)."' name='desloca' OnClick=".$saida."></TD>";
		print "</TR>";

		$aviso="";
		if (isset($_POST['OK'])==TRANS('BT_OK')) {


			$queryB = "SELECT sis_id,sistema, sis_email FROM sistemas WHERE sis_id = ".$sistema."";
			$sis_idB = mysql_query($queryB);
			$rowSis = mysql_fetch_array($sis_idB);

         if ($rowconf_global['conf_scr_local']) {
				$queryC = "SELECT local from localizacao where loc_id = ".$_POST['local']."";
				$loc_idC = mysql_query($queryC);
				$setor = mysql_result($loc_idC,0);
			}

			$queryD = "SELECT u.*,a.* from usuarios u, sistemas a where u.AREA = a.sis_id and user_id=".$_SESSION['s_uid']."";
			$loginD = mysql_query($queryD);
			$rowqryD = mysql_fetch_array($loginD);
			$nome = $rowqryD['nome'];

			/* ----------------- INICIO ALTERACAO ----------------- */
			$gravaImg = false;
			$qryConf = "SELECT * FROM config";
			$execConf = mysql_query($qryConf) or die (TRANS('ERR_QUERY').", A TABELA CONF FOI CRIADA?");
			$rowConf = mysql_fetch_array($execConf);
			$arrayConf = array();
			$arrayConf = montaArray($execConf,$rowConf);
			for($i=1;$i<=$row_config['conf_qtd_max_anexos']; $i++){
				$nomeAnexo = 'anexo_'.$i;
				if (isset($_FILES[$nomeAnexo]) and $_FILES[$nomeAnexo]['name']!="") {
					$upld = upload($nomeAnexo,$arrayConf,$rowConf['conf_upld_file_types']);
					if ($upld =="OK") {
						$gravaImg[$i] = true;
					} else {
						$gravaImg[$i] = false;
						$upld.="<br><a align='center' onClick=\"exibeEscondeImg('idAlerta');\"><img src='".ICONS_PATH."/stop.png' width='16px' height='16px'>&nbsp;".TRANS('LINK_CLOSE','Fechar')."</a>";
						print "</table>";
						print "<div class='alerta' id='idAlerta'><table bgcolor='#999999'><tr><td colspan='2' bgcolor='yellow'>".$upld."</td></tr></table></div>";
						exit;
					}
				}
			}
			/* ----------------- FIM ALTERACAO ----------------- */

			//$data = date("Y-m-d H:i:s");
			$i = 0;

			if (!isset($_POST['replicar'])){
				$replicate = 0;
			} else {
				$replicate = $_POST['replicar'];
			}

			$date_schedule = date("Y-m-d H:i:s");

			while ($i<=$replicate) //'".noHtml($descricao)."'
			{
					if ($_SESSION['s_nivel'] != 3){
						$operator = $_SESSION['s_uid'];
					} else {
						$operator = 0;
					}
					if ($_POST['openby'] == -1 || !isset($_POST['openby'])){
						$open_by = $_SESSION['s_uid'];
					} else {
						$open_by = $_POST['openby'];
					}


					if (isset($_POST['chk_squedule']) && $_POST['chk_squedule']!=""){
						$schedule = 1;
						$date_schedule = FDate($_POST['date_schedule']);
						$oStatus = $row_config['conf_schedule_status'];
						$first_queued = false;
					} else {
						$schedule = 0;
						$date_schedule = date("Y-m-d H:i:s");

						if (isset($_POST['foward']) && $_POST['foward']!=-1){
							$oStatus = $row_config['conf_foward_when_open'];
							$operator = $_POST['foward'];
						} else
							$oStatus = 1; //Aguardando atendimento

						$first_queued = true;//date("Y-m-d H:i:s");
					}

					if (!isset($_POST['radio_prob'])){
						$catProb = $problema;
					} else {
						$catProb = $_POST['radio_prob'];
					}

					$query = "";
					$query = "INSERT INTO ocorrencias (problema, descricao, instituicao, equipamento, sistema, contato, telefone, local, operador, ".
						"data_abertura, data_fechamento, status, data_atendimento, aberto_por, oco_scheduled, oco_real_open_date, date_first_queued, oco_prior )".
						" values ".
						//"(".$problema.",  ";
						"(".$catProb.",  ";

					if ($_SESSION['s_formatBarOco']) {
						$query.= " '".addslashes($descricao)."',";
					} else {
						$query.= " '".noHtml($descricao)."',";
					}

					if (!$schedule){
						$query.="".$_POST['instituicao'].",'".$_POST['equipamento']."','".$sistema."',".
						"'".noHtml($_POST['contato'])."','".$_POST['telefone']."',".$_POST['local'].",".$operator.",".
                  " '".$date_schedule."',NULL,".$oStatus.",NULL,".$open_by.",".$schedule.", '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."', '".$_POST['prioridade']."')";
					} else {
						$query.="".$_POST['instituicao'].",'".$_POST['equipamento']."','".$sistema."',".
						"'".noHtml($_POST['contato'])."','".$_POST['telefone']."',".$_POST['local'].",".$operator.",".
                  " '".$date_schedule."',NULL,".$oStatus.",NULL,".$open_by.",".$schedule.", '".date("Y-m-d H:i:s")."', NULL, '".$_POST['prioridade']."')";
					}

					$resultado = mysql_query($query) or die (TRANS('ERR_QUERY'));

					$numero = mysql_insert_id();
					$globalID = random();

					//GERA ID GLOBAL PARA ACESSO √Ä OCORR√äNCIA
					$qryGlobal = "INSERT INTO global_tickets (gt_ticket, gt_id) values (".$numero.", ".$globalID.")";
					$execGlobal = mysql_query($qryGlobal) or die($qryGlobal);

					//INSERSAO PARA ARMAZENAR O TEMPO DO CHAMADO EM CADA STATUS
					$sql = " insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$numero.", ".$oStatus.", 0, '".date("Y-m-d H:i:s")."')  ";
					$exec_sql = mysql_query($sql);
					if ($exec_sql == 0) $error = " erro na tabela TEMPO_STATUS ";

					$i++;
			}

			if ($resultado == 0) {
				$aviso.= "ERRO na inclusao dos dados.".$query;
			} else {
				//$numero = mysql_insert_id();

				$sqlDoc = "insert into doc_time (doc_oco, doc_open, doc_edit, doc_close, doc_user) values (".$numero.",".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s")).", 0, 0, ".$_SESSION['s_uid'].")";
				$execDoc = mysql_query($sqlDoc) or die (TRANS('ERR_QUERY').'br>').$sqlDoc;


				if (isset($_POST['pai'])) {
					$sqlDep = "insert into ocodeps (dep_pai, dep_filho) values (".$_POST['pai'].", ".$numero.")";
					$execDep = mysql_query($sqlDep) or die (TRANS('ERR_QUERY').'<br>'.$sqlDep);
					if ($execDep == 0) $aviso.= TRANS('MSG_NOT_TO_TIE_OCCOR');
				}

				/* ----------------- INICIO ALTERACAO ----------------- */
				for($i=1;$i<=$row_config['conf_qtd_max_anexos']; $i++){
					if ($gravaImg[$i]) {
						$nomeAnexo = 'anexo_'.$i;
						//INSERSAO DO ARQUIVO NO BANCO
						$fileinput=$_FILES[$nomeAnexo]['tmp_name'];
						$tamanho = getimagesize($fileinput);
						$tamanho2 = filesize($fileinput);

						if(chop($fileinput)!=""){
							// $fileinput should point to a temp file on the server
							// which contains the uploaded image. so we will prepare
							// the file for upload with addslashes and form an sql
							// statement to do the load into the database.
							$image = addslashes(fread(fopen($fileinput,"r"), 1000000));
							$SQL = "Insert Into imagens (img_nome, img_oco, img_tipo, img_bin, img_largura, img_altura, img_size) values ".
									"('".noSpace($_FILES[$nomeAnexo]['name'])."',".$numero.", '".$_FILES[$nomeAnexo]['type']."', ".
									"'".$image."', '".$tamanho[0]."', '".$tamanho[1]."', '".$tamanho2."')";
							// now we can delete the temp file
							unlink($fileinput);
						} /*else {
							echo "".TRANS('MSG_NOT_IMAGE_SELECT')."";
							exit;
						}*/
						$exec = mysql_query($SQL); //or die ("N?O FOI POSS?VEL GRAVAR O ARQUIVO NO BANCO DE DADOS! ");
						if ($exec == 0)
							$aviso.= TRANS('MSG_ATTACH_IMAGE')."<br>";
					}
				}
				/* ----------------- FIM ALTERACAO ----------------- */


				$qryfull = $QRY["ocorrencias_full_ini"]." WHERE o.numero = ".$numero."";
				$execfull = mysql_query($qryfull) or die(TRANS('ERR_QUERY').$qryfull);
				$rowfull = mysql_fetch_array($execfull);

				$VARS = array();
				$VARS['%numero%'] = $rowfull['numero'];
                                $VARS['%linkglobal%'] = "<a href='".$row_config['conf_ocomon_site']."ocomon/geral/mostra_consulta.php?numero=".$numero."&id=".$globalID."'>".$row_config['conf_ocomon_site']."/ocomon/geral/mostra_consulta.php?numero=".$numero."&id=".$globalID."</a>";
				$VARS['%usuario%'] = $rowfull['contato'];
				$VARS['%contato%'] = $rowfull['contato'];
				$VARS['%descricao%'] = $rowfull['descricao'];
				$VARS['%setor%'] = $rowfull['setor'];
				$VARS['%ramal%'] = $rowfull['telefone'];
				$VARS['%assentamento%'] = $rowfull['descricao'];
				$VARS['%site%'] = "<a href='".$row_config['conf_ocomon_site']."'>".$row_config['conf_ocomon_site']."</a>";
				$VARS['%area%'] = $rowfull['area'];
				$VARS['%operador%'] = $rowfull['nome'];
				$VARS['%editor%'] = $rowfull['nome'];
				$VARS['%aberto_por%'] = $rowfull['aberto_por'];
				$VARS['%problema%'] = $rowfull['problema'];
				$VARS['%solucao%'] = '';
				$VARS['%versao%'] = VERSAO;

				$qryconfmail = "SELECT * FROM mailconfig";
				$execconfmail = mysql_query($qryconfmail) or die (TRANS('ERR_QUERY'));
				$rowconfmail = mysql_fetch_array($execconfmail);

				if (isset($_POST['mailAR'])) {
					$qryemails = "select GROUP_CONCAT(u.email) as email from usuarios_areas as a left join usuarios as u on a.`uarea_uid` = u.user_id where a.uarea_sid = ".$rowfull['area_cod']."";
					$execmail = mysql_query($qryemails) or die(TRANS('ERR_QUERY'));
					$mail =  mysql_fetch_array($execmail);
					//$teste = mysql_num_rows($execmail);
					if ( $mail['email'] != NULL ){
						$rowSis['sis_email'] = $rowSis['sis_email'].",".$mail['email'];
					}
					$event = 'abertura-para-area';
					$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
					$execmsg = mysql_query($qrymsg) or die(TRANS('ERR_QUERY'));
					$rowmsg = mysql_fetch_array($execmsg);

					send_mail($event, $rowSis['sis_email'], $rowconfmail, $rowmsg, $VARS);
				}

				if (isset($_POST['mailOP'])) {
					$event = 'abertura-para-operador';
					$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
					$execmsg = mysql_query($qrymsg) or die(TRANS('MSG_ERR_MSCONFIG'));
					$rowmsg = mysql_fetch_array($execmsg);

					$sqlMailOper = "select * from usuarios where user_id =".$_POST['foward']."";
					$execMailOper = mysql_query($sqlMailOper);
					$rowMailOper = mysql_fetch_array($execMailOper);

					$VARS['%operador%'] = $rowMailOper['nome'];
					send_mail($event, $rowMailOper['email'], $rowconf, $rowmsg, $VARS);
				}

				if (isset($_POST['mailUS'])) {
					$event = 'abertura-para-usuario';
					$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
					$execmsg = mysql_query($qrymsg) or die(TRANS('ERR_QUERY'));
					$rowmsg = mysql_fetch_array($execmsg);
					$sqlMailUser = "select * from usuarios where user_id =".$open_by."";
					$execMailUser = mysql_query($sqlMailUser);
					$rowMailUser = mysql_fetch_array($execMailUser);

					send_mail($event, $rowMailUser['email'], $rowconfmail, $rowmsg, $VARS);
				}


				$aviso.= "".TRANS('MSG_SUCCESS_OPENCALL','Ocorrencia registrada com sucesso!')."!&nbsp;".
							"".TRANS('OCO_FIELD_NUMBER').":&nbsp;<font color=red>".$numero."</font><BR><br>".
							"<a href='atender.php?numero=".$numero."'>".TRANS('OCO_ACT_ASWER','Atender')."</a><br><br>".
							"<a href='encaminhar.php?numero=".$numero."'>".TRANS('OCO_ACT_EDIT_REDIR','Encaminhar/Editar')."</a><br><br>".
							"<a href='encerramento.php?numero=".$numero."'>".TRANS('OCO_ACT_CLOSE','Encerrar')."</a><br><br>";

				$i = 0;
			}


			if ($rowqryD['sis_atende']==1){

				$_SESSION['aviso'] = $aviso;
				$_SESSION['origem'] = "abertura.php";

				if (isset($_POST['pai'])) {
					print "<script>mensagem('".TRANS('MSG_OPEN_CALL_OK').$numero."'); window.opener.location.href=\"mostra_consulta.php?numero=".$numero."\"; window.close();</script>";
				} else {
					print "<script>redirect('mostra_consulta.php?numero=".$numero."&justOpened=true');</script>";
					exit;
				}

			} else {
				$qrymail = "SELECT * FROM usuarios WHERE user_id = ".$_SESSION['s_uid']."";
				$execmail = mysql_query($qrymail) or die(TRANS('ERR_QUERY'));
				$rowmail = mysql_fetch_array($execmail);
				//ENVIA E-MAIL PARA O PR?PRIO USU?RIO QUE ABRIU O CHAMADO

				//$flag = mail_user($rowmail['email'],$rowconf['sis_email'],$rowmail['nome'],$numero,OCOMON_SITE);
				$event = 'abertura-para-usuario';
				$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
				$execmsg = mysql_query($qrymsg) or die(TRANS('ERR_QUERY'));
				$rowmsg = mysql_fetch_array($execmsg);

				//ENVIA E-MAIL PARA O PR?PRIO USU?RIO QUE ABRIU O CHAMADO
				//send_mail($event, $rowSis['sis_email'], $rowconfmail, $rowmsg, $VARS);
				send_mail($event, $rowmail['email'], $rowconfmail, $rowmsg, $VARS);

				if (!empty($rowconf['conf_scr_msg'])){
					$mensagem = str_replace("%numero%",$numero,$rowconf['conf_scr_msg']);
				} else
					$mensagem = str_replace("%numero%",$numero,$rowconf_global['conf_scr_msg']);

				print "<script>mensagem('".$mensagem."'); redirect('abertura_user.php');</script>";
			}
		}

		$qrylogado = "SELECT sis_atende FROM sistemas where sis_id = ".$_SESSION['s_area']."";
		$execlogado = mysql_query($qrylogado) or die(TRANS('ERR_QUERY'));
		$rowlogado = mysql_fetch_array($execlogado);

?>
<script type="text/javascript">
<!--

	function valida(){
		var ok = true;
		LOAD=0;
		if (!LOAD) {
			//var ok = false;

			var operador = <?php print $rowlogado['sis_atende']?>;
			var unit = document.getElementById('idUnidade');
			var tag = document.getElementById('idEtiqueta');

			var sel_area = document.getElementById('idArea');
			var sel_problema = document.getElementById('idProblema');
			var descricao = document.getElementById('idDescricao');
			var contato = document.getElementById('idContato');

			//var carreg = '<?php //print $carrega?>';

			if (ok) {
				if (sel_area != null){
					var ok = validaForm('idArea','COMBO','<?php print TRANS('OCO_FIELD_AREA')?>',1);
				} //else ok = true;
			}

			if (ok) {
				if (sel_problema != null){
					var ok = validaForm('idProblema','COMBO','<?php print TRANS('OCO_FIELD_PROB')?>',1);
				} //else ok = true;
			}

			if (ok) {
				if (descricao != null){
					var ok = validaForm('idDescricao','','<?php print TRANS('OCO_FIELD_DESC')?>',1);
				} //else ok = true;
			}

			if (ok) {
				if (unit != null){
					if (operador == 0){
						var ok = validaForm('idUnidade','COMBO','<?php print TRANS('OCO_FIELD_UNIT')?>',1);
					} else ok = true;
				} else ok = true;
			}

			if (ok) {
				if (tag != null){
					if (operador == 1){
						var ok = validaForm('idEtiqueta','ALFAFULL','<?php print TRANS('OCO_FIELD_TAG')?>',0);
					} else {
						var ok = validaForm('idEtiqueta','ALFAFULL','<?php print TRANS('OCO_FIELD_TAG')?>',1);
					}
				} else ok = true;
			}

			if (ok) {
				if (contato != null){
					var ok = validaForm('idContato','','<?php print TRANS('OCO_FIELD_CONTACT')?>',1);
				} else ok = true;
			}

			if (ok){
				var fone = document.getElementById('idTelefone');
				//if (carreg){
				if (fone != null){
					//var ok = validaForm('idTelefone','INTEIRO','ramal',1);
					var ok = validaForm('idTelefone','FONE','<?php print TRANS('OCO_FIELD_PHONE')?>',1);
				} else ok = true;
				//}
			}
			if (ok){
				var local = document.getElementById('idLocal');
				//if (carreg){
				if (local != null){
					//var ok = validaForm('idTelefone','INTEIRO','ramal',1);
					var ok = validaForm('idLocal','COMBO','<?php print TRANS('OCO_FIELD_LOCAL')?>',1);
				} else ok = true;
				//}
			}
			if (ok){
				var replicate = document.getElementById('idReplicar');
				if (replicate != null){
					var ok = validaForm('idReplicar','INTEIROFULL','<?php print TRANS('OCO_FIELD_REPLICATE')?>',0);
				} else ok = true;
			}
			if (ok){
				var schedule = document.getElementById('idDate_schedule');
				if (schedule != null){
					var ok = validaForm('idDate_schedule','DATAHORA','<?php print TRANS('OCO_FIELD_SCHEDULE')?>',0);
				} else ok = true;
			}
		}
		return ok;

	}

	function popup_alerta(pagina)	{ //Exibe uma janela popUP
      		x = window.open(pagina,'Alerta','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
      		//x.moveTo(100,100);
		//x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false
     	}

	function checa_etiqueta(){
	 	var inst = document.getElementById('idUnidade');
		var inv = document.getElementById('idEtiqueta');
		if (inst != null && inv != null){
			if (inst.value=='null' || !inv.value){
				var msg = '<?php print TRANS('MSG_UNIT_TAG');?>!'
				window.alert(msg);
			} else
			popup_alerta('../../invmon/geral/mostra_consulta_inv.php?comp_inst='+inst.value+'&comp_inv='+inv.value+'&popup='+true);
		}
		return false;
	}


	function checa_chamados(){
	 	var inst = document.getElementById('idUnidade');
		var inv = document.getElementById('idEtiqueta');
		if (inst != null && inv != null){
			if (inst.value=='null' || !inv.value){
				window.alert('<?php print TRANS('FILL_UNIT_TAG');?>');
			} else
			popup_alerta('../../invmon/geral/ocorrencias.php?comp_inst='+inst.value+'&comp_inv='+inv.value+'&popup='+true);
		}
		return false;
	}

	function checa_por_local(){
	 	//var local = document.form1.local.value;
		var local = document.getElementById('idLocal');
		if (local != null) {
			if (local.value==-1){
				window.alert('<?php print TRANS('FILL_LOCATION');?>');
			} else
				popup_alerta('../../invmon/geral/mostra_consulta_comp.php?comp_local='+local.value+'&popup='+true);
		}
		return false;
	}

	function desabilita(v)
	{
		document.form1.OK.disabled=v;

	}

 	function desabilitaCarrega(v){
		//document.form1.carrega.disabled=v;
		var btLoad = document.getElementById('idBtCarrega');
		if (btLoad != null){
			btLoad.disabled = v;
		}
	}

	function Habilitar(){
		var descricao = document.getElementById('idDescricao');
		var ramal = document.getElementById('idTelefone');
		var contato = document.getElementById('idContato');
		var sel_area = document.getElementById('idArea');
		var sel_problema = document.getElementById('idProblema');
		var sel_local = document.getElementById('idLocal');
		var botao = document.getElementById('idSubmit');

		var ok = false;
		var ok2 = true;

		if (descricao != null){
			if (descricao.value == "" ) {ok = true;}
		}
		if (sel_area != null){
			if (sel_area.value ==-1) { ok = true;}
		}
		if (sel_problema != null){
			if (sel_problema.value ==-1) { ok = true;}
		}
		//if (sel_local != null){
			//if (sel_local.value ==-1) { ok = true;}
		//}
		if (ramal != null){
			if (ramal.value =="") { ok = true;}
		}
		if (contato != null){
			if (contato.value =="") {ok = true;}
		}
		if (!ok2)
		{
			//alert('desabilita::true');
			desabilita(true);
			botao.className= "button-disabled";
		} else {
			//alert('desabilita::false');
			desabilita(false);
			botao.className= "button";
		}
	}

	function HabilitarCarrega(){
		var sel_inst = document.getElementById('idUnidade');
		var etiqueta = document.getElementById('idEtiqueta');

		if (sel_inst != null && etiqueta != null){
			if ((sel_inst.value=="null")||(etiqueta.value=="")) {
				desabilitaCarrega(true);
			} else{
				desabilitaCarrega(false);
			}
		}
	}


	function checarSchedule() {
		var checado = false;
		if (document.form1.chk_squedule.checked){
			checado = true;
			disable_schedule(false);
			document.form1.foward.value=-1;
			document.form1.foward.disabled=true;

		} else {
			checado = false;
			disable_schedule(true);
			document.form1.date_schedule.value=document.form1.data_abertura.value;
			document.form1.foward.disabled=false;
		}
		return checado;
	}

	function checkMailOper(){
		if (document.form1.foward.value!=-1){
			document.form1.mailOP.disabled=false;
			document.form1.mailOP.checked=true;
		} else {
			document.form1.mailOP.disabled=true;
			document.form1.mailOP.checked=false;
		}
	}

	function checkMailUser(){
		if (document.form1.openby.value!=-1){
			document.form1.mailUS.disabled=false;
			document.form1.mailUS.checked=true;
		} else {
			document.form1.mailUS.disabled=true;
			document.form1.mailUS.checked=false;
		}
	}

	function preencheContato() {
		if (document.form1.openby.value!=-1){
			var i = document.form1.openby.selectedIndex;
			document.form1.contato.value = document.form1.openby[i].text;
		} else {
			document.form1.contato.value = "";
		}
	}

	function disable_schedule(v) {
		document.form1.date_schedule.disabled = v;
		document.form1.date_schedule.focus();
	}


	//window.setInterval("Habilitar()",100);
	window.setInterval("HabilitarCarrega()",200);

//-->
</script>
<?php
print "</TABLE>";

print "</FORM>";

print "</body>";
print "</html>";
?>
