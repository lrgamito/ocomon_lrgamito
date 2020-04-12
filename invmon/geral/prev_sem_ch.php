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


/* Tela de Controle de Preventiva
 * 
 * Equipamentos que não tiveram preventiva
 * 
 * Com base na data de cadastro do equipamento.
*/

session_start();

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	$cab = new headers;
	$cab->set_title(TRANS('TTL_OCOMON'));

	$hoje = date("d-m-Y H:i:s");

	$logo = LOGO_PATH.'/hcc_logo1.jpg';
        $iconPath = "../../includes/icons/";


	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4,'helpprev_com_ch.php');
	if ($_SESSION['s_nivel']==1)
	{
		$administrador = true;
	} else
		$administrador = false;

	if (!isset($_REQUEST['header'])) {
		$header= TRANS('TXT_REPORT_PERSON');
	} else
		$header = $_REQUEST['header'];


	print "<div id='idLoad' class='loading' style='display:none'><img src='../../includes/imgs/loading.gif'></div>";

        //Config Geral
        
	$qry = "SELECT conf_page_size AS page FROM config";
	$qry_exec = mysql_query($qry) or die (TRANS('MSG_NECESS_UPDATE_TABLE_CONF'));
	$rowConf = mysql_fetch_array($qry_exec);
	$PAGE_SIZE = $rowConf['page'];
         
        
        //$PAGE_SIZE = 10;
        
        //Config de preventiva
        
        $qry = "SELECT * FROM config_preventiva";
	$qry_exec = mysql_query($qry) or die (TRANS('MSG_NECESS_UPDATE_TABLE_CONF'));
	$rowConf_prev = mysql_fetch_array($qry_exec);
	

	//Verifica se a coluna jï¿½ estï¿½ ordenada e seta para ser ordenada em ordem inversa!!
	$az = "";
	$mostra = "";
	$ordenado = "";

	$ICON_ORDER['etiqueta'] = "";
	$ICON_ORDER['instituicao'] = "";
	$ICON_ORDER['tipo'] = "";
	$ICON_ORDER['modelo'] = "";
	$ICON_ORDER['local'] = "";
	$ICON_ORDER['situacao'] = "";
        $ICON_ORDER['preventiva'] = "";

	if (isset($_REQUEST['ordenado'])){
		$ordenado = $_REQUEST['ordenado'];
	} else
		$ICON_ORDER['etiqueta'] = "<img src='../../includes/css/OrderAsc.png' width='16' height='16' align='absmiddle'>";

	if (isset($_REQUEST['coluna']) ) {
	 	if (isset($_REQUEST['ordenado']))
	 	if ($_REQUEST['coluna'] == $_REQUEST['ordenado']) {
			$az = " desc";
			$ordenado = "";
			$mostra = " ".TRANS('TXT_ORDER_BY_DESC');

			$ICON_ORDER['etiqueta'] = "";
			$ICON_ORDER['instituicao'] = "";
			$ICON_ORDER['tipo'] = "";
			$ICON_ORDER['modelo'] = "";
			$ICON_ORDER['local'] = "";
			$ICON_ORDER['situacao'] = "";
                        $ICON_ORDER['preventiva'] = "";

			$ICON_ORDER[$_REQUEST['coluna']] = "<img src='../../includes/css/OrderDesc.png' width='16' height='16' align='absmiddle'>";
	 	} else {
			$ordenado = $_REQUEST['coluna'];
			$az = " asc";
			$mostra = " ".TRANS('TXT_ORDER_BY_INCRESC');

			$ICON_ORDER['etiqueta'] = "";
			$ICON_ORDER['instituicao'] = "";
			$ICON_ORDER['tipo'] = "";
			$ICON_ORDER['modelo'] = "";
			$ICON_ORDER['local'] = "";
			$ICON_ORDER['situacao'] = "";
                        $ICON_ORDER['preventiva'] = "";

			$ICON_ORDER[$_REQUEST['coluna']] = "<img src='../../includes/css/OrderAsc.png' width='16' height='16' align='absmiddle'>";
	 	}
	}

	//Para nï¿½o precisar escrever na tela todos os critï¿½rios de ordenaï¿½ï¿½o eu defino aqui o que deve aparecer!!
	$traduz = array("etiqueta".$az.""=>TRANS('OCO_FIELD_TAG').$mostra,
		"fab_nome".$az.",modelo".$az."" => TRANS('COL_MODEL').$mostra,
		"fab_nome".$az.",modelo".$az.",etiqueta".$az.""=> TRANS('COL_MODEL').$mostra,
		"modelo".$az.",etiqueta".$az.""=> TRANS('COL_MODEL').$mostra,
		"instituicao".$az.",etiqueta".$az."" =>TRANS('OCO_FIELD_UNIT').$mostra,
		"equipamento".$az.",modelo".$az."" =>$TRANS["col_tipo"].$mostra,
		"local".$az."" =>TRANS('col_local').$mostra,
		"equipamento".$az.",fab_nome".$az.",modelo".$az.",etiqueta".$az."" => TRANS('COL_TYPE').$mostra,
		"equipamento".$az.",fab_nome".$az.",modelo".$az.",local".$az.",etiqueta".$az.""=> TRANS('COL_TYPE').$mostra,
		"equipamento".$az.",modelo".$az.",local".$az.",etiqueta".$az."" => TRANS('COL_TYPE').$mostra,
		"fab_nome".$az.",modelo".$az.",local".$az.",etiqueta".$az.""=> TRANS('COL_MANUFACTURE').$mostra,
		"local".$az.",etiqueta".$az.""=> TRANS('COL_LOCALIZATION').$mostra,
		"local".$az.",equipamento".$az.",fab_nome".$az.",modelo".$az.",etiqueta".$az.""=>TRANS('COL_LOCALIZATION').$mostra,
		"serial".$az.""=> TRANS('COL_SN').$mostra,
		"nota".$az.""=> TRANS('COL_NF').$mostra,
		"situac_nome".$az.",etiqueta".$az.""=> TRANS('COL_SITUAC').$mostra,
		"situac_nome".$az.""=> TRANS('COL_SITUAC').$mostra,
		"tipo,localizaçãoo".$az."" => TRANS('COL_TYPE').$mostra,
                "preventiva".$az."" => "preventiva".$mostra);

	if (isset($_REQUEST['visualiza'])) {
		if ($_REQUEST['visualiza']!='impressora' && $_REQUEST['visualiza']!='texto' && $_REQUEST['visualiza']!='relatorio' &&
			$_REQUEST['visualiza']!='mantenedora1' && $_REQUEST['visualiza']!='config' && $_REQUEST['visualiza']!='termo' &&
			$_REQUEST['visualiza']!='transito') {

		} else {
			print "<body class='relatorio' >";
		}
	} else {
		//print "<body class='relatorio' >";
	}
	print "<BODY  onLoad=\"checar();\">"; //bgcolor=".BODY_COLOR."


/*	if (!isset($_GET['negar'])) {
		$negar = "";
	} else
		$negar = $_GET['negar'];*/


	//Cï¿½digo para definir o array de unidades como sendo array de uma ï¿½nica posiï¿½ï¿½o
	$comp_inst ="";
	if (isset($_GET['comp_inst'])) {
		$comp_inst = $_GET['comp_inst'];
	} else
	if (isset($_POST['comp_inst'])){
		$comp_inst = $_POST['comp_inst'];
	}

	if (!isset($_POST['saida']) && !empty($comp_inst))
	{
		$saida="";
		if (is_array($comp_inst)) {
			for ($i=0; $i<count($comp_inst); $i++){
				$saida.= "$comp_inst[$i],";
			}
		} else
			$saida=$comp_inst;

		if (strlen($saida)>0) {
			$saida = substr($saida,0,-1);
		}
		$comp_inst = $saida;
	}
	################################################################
	$comp_inv ="";
	if (isset($_GET['comp_inv'])) {
		$comp_inv = $_GET['comp_inv'];
	} else
	if (isset($_POST['comp_inv'])){
		$comp_inv = $_POST['comp_inv'];
	}

	/**
	*@min = Variï¿½vel referente o primeiro parametro do "limit" na montagem da clausula SLQ
	*@maxAux = Variï¿½vel auxiliar para a montagem dos botoes de navegacao.
	*@minAux = Variï¿½vel auxiliar para a montagem dos botï¿½es de navegacao.
	*
	*/

	$min = 0;
	$maxAux = 0;
	$minAux = 0;
	//$page = 50;

	$msgInst = "";
	$checked = "";
	$comp_inv_flag = false;
	$comp_sn_flag = false;
	$comp_marca_flag = false;
	$comp_mb_flag = false;
	$comp_proc_flag = false;
	$comp_memo_flag = false;
	$comp_video_flag = false;
	$comp_som_flag = false;
	$comp_rede_flag = false;
	$comp_modem_flag = false;
	$comp_modelohd_flag = false;
	$comp_cdrom_flag = false;
	$comp_dvd_flag = false;
	$comp_grav_flag = false;
	$comp_local_flag = false;
	$comp_reitoria_flag = false;
	$comp_nome_flag = false;
	$comp_fornecedor_flag = false;
	$comp_nf_flag = false;
	$comp_inst_flag = false;
	$comp_tipo_equip_flag = false;
	$comp_fab_flag = false;
	$comp_tipo_imp_flag = false;
	$comp_polegada_flag = false;
	$comp_resolucao_flag = false;
	$comp_ccusto_flag = false;
	$comp_situac_flag = false;
	$comp_data_flag = false;
	$comp_data_compra_flag = false;
	$garantia_flag = false;
	$soft_flag = false;
	$comp_assist_flag = false;
	$comp_memo_notnull = false;
	$comp_memo_null = false;
	$comp_leitor_flag = false;  //by LRG
	$comp_os_flag = false;		//by LRG
	$comp_sn_os_flag = false;	//by LRG
	$tmpData = array();


	if (isset($_GET['encadeado'])) {
		$checked = "checked";
	}

        //INICIO DA QUERY
        //
        $QRY["full_detail_prev_ini"] = "SELECT c.comp_inv as etiqueta, c.comp_sn as serial, c.comp_nome as nome, c.comp_sn_os as sn_os, ".		//LRG
 			"\n\tc.comp_nf as nota, inst.inst_nome as instituicao, inst.inst_cod as cod_inst, ".
 			"\n\tc.comp_coment as comentario, c.comp_valor as valor, c.comp_data as data_cadastro, ".
			"\n\tc.comp_data_compra as data_compra, c.comp_ccusto as ccusto, c.comp_situac as situacao, ".
			"\n\tc.comp_local as tipo_local, loc.loc_reitoria as reitoria_cod, reit.reit_nome as reitoria, ".
			"\n\tc.comp_mb as tipo_mb, c.comp_proc as tipo_proc, ".
			"\n\tc.comp_tipo_equip as tipo, c.comp_memo as tipo_memo, c.comp_video as tipo_video, ".
			"\n\tc.comp_modelohd as tipo_hd, c.comp_modem as tipo_modem, c.comp_cdrom as tipo_cdrom, ".
			"\n\tc.comp_dvd as tipo_dvd, c.comp_grav as tipo_grav, c.comp_resolucao as tipo_resol, ".
			"\n\tc.comp_polegada as tipo_pole, c.comp_tipo_imp as tipo_imp, c.comp_assist as assistencia_cod, ".
			"\n\tequip.tipo_nome as equipamento, c.comp_rede as tipo_rede, c.comp_som as tipo_som, ".
			"\n\tt.tipo_imp_nome as impressora, loc.local, ".

			"\n\tproc.mdit_fabricante as fabricante_proc, proc.mdit_desc as processador, ".
			"\n\tproc.mdit_desc_capacidade as clock, proc.mdit_cod as cod_processador, ".
			"\n\tproc.mdit_sufixo as proc_sufixo, ".
			"\n\thd.mdit_fabricante as fabricante_hd, hd.mdit_desc as hd, hd.mdit_desc_capacidade as hd_capacidade, ".
			"\n\thd.mdit_cod as cod_hd, ".
			"\n\thd.mdit_sufixo as hd_sufixo, ".
			"\n\tvid.mdit_fabricante as fabricante_video, vid.mdit_desc as video, vid.mdit_cod as cod_video, ".
			"\n\tred.mdit_fabricante as rede_fabricante, red.mdit_desc as rede, red.mdit_cod as cod_rede, ".
			"\n\tmd.mdit_fabricante as fabricante_modem, md.mdit_desc as modem, md.mdit_cod as cod_modem, ".
			"\n\tcd.mdit_fabricante as fabricante_cdrom, cd.mdit_desc as cdrom, cd.mdit_cod as cod_cdrom, ".
			"\n\tgrav.mdit_fabricante as fabricante_gravador, grav.mdit_desc as gravador, grav.mdit_cod as cod_gravador, ".
			"\n\tleitor.mdit_fabricante as fabricante_leitor, leitor.mdit_desc as leitor, leitor.mdit_cod as cod_leitor, ".		//LRG
			"\n\tos.mdit_fabricante as fabricante_os, os.mdit_desc as os, os.mdit_cod as cod_os, ".								//LRG
			"\n\tdvd.mdit_fabricante as fabricante_dvd, dvd.mdit_desc as dvd, dvd.mdit_cod as cod_dvd, ".
			"\n\tmb.mdit_fabricante as fabricante_mb, mb.mdit_desc as mb, mb.mdit_cod as cod_mb, ".
			"\n\tmemo.mdit_desc_capacidade as memoria, memo.mdit_cod as cod_memoria, memo.mdit_sufixo as memo_sufixo, ".
			"\n\tsom.mdit_fabricante as fabricante_som, som.mdit_desc as som, som.mdit_cod as cod_som, ".

			"\n\tfab.fab_nome as fab_nome, fab.fab_cod as fab_cod, fo.forn_cod as fornecedor_cod, ".
			"\n\tfo.forn_nome as fornecedor_nome, model.marc_cod as modelo_cod, model.marc_nome as modelo, ".
			"\n\tpol.pole_cod as polegada_cod, pol.pole_nome as polegada_nome, ".
			"\n\tres.resol_cod as resolucao_cod, res.resol_nome as resol_nome, ".
			"\n\tsit.situac_cod as situac_cod, sit.situac_nome as situac_nome, sit.situac_destaque as situac_destaque, ".

			"\n\ttmp.tempo_meses as tempo, tmp.tempo_cod as tempo_cod, ".
			"\n\ttp.tipo_garant_nome as tipo_garantia, tp.tipo_garant_cod as garantia_cod, ".

			"\n\tdate_add(c.comp_data_compra, interval tmp.tempo_meses month)as vencimento, ".
                        "\n\tsoft.soft_desc as software, soft.soft_versao as versao, ".
			"\n\tassist.assist_desc as assistencia, ".
                        
                        "\n\toco.problema, oco.data_fechamento,". 
                
                        "\n\tdate_add(c.comp_data, interval cprev.conf_tempo_min day) as prev_min,". 
                        "\n\tdate_add(c.comp_data, interval cprev.conf_maq_nova day) as prev_max".

		"\nFROM (((((((((((((((((((((((((((equipamentos as c left join  tipo_imp as t on ".
			"\n\tt.tipo_imp_cod = c.comp_tipo_imp) ".
			"\n\tleft join polegada as pol on c.comp_polegada = pol.pole_cod) ".
			"\n\tleft join resolucao as res on c.comp_resolucao = res.resol_cod) ".
			"\n\tleft join fabricantes as fab on fab.fab_cod = c.comp_fab) ".
			"\n\tleft join fornecedores as fo on fo.forn_cod = c.comp_fornecedor) ".
			"\n\tleft join situacao as sit on sit.situac_cod = c.comp_situac) ".
			"\n\tleft join tempo_garantia as tmp on tmp.tempo_cod =c.comp_garant_meses) ".
			"\n\tleft join tipo_garantia as tp on tp.tipo_garant_cod = c.comp_tipo_garant) ".

			"\n\tleft join assistencia as assist on assist.assist_cod = c.comp_assist) ".

			"\n\tleft join modelos_itens as proc on proc.mdit_cod = c.comp_proc) ".
			"\n\tleft join modelos_itens as hd on hd.mdit_cod = c.comp_modelohd) ".
			"\n\tleft join modelos_itens as vid on vid.mdit_cod = c.comp_video) ".
			"\n\tleft join modelos_itens as red on red.mdit_cod = c.comp_rede) ".
			"\n\tleft join modelos_itens as md on md.mdit_cod = c.comp_modem) ".
			"\n\tleft join modelos_itens as cd on cd.mdit_cod = c.comp_cdrom) ".
			"\n\tleft join modelos_itens as grav on grav.mdit_cod = c.comp_grav) ".
			"\n\tleft join modelos_itens as dvd on dvd.mdit_cod = c.comp_dvd) ".
			"\n\tleft join modelos_itens as mb on mb.mdit_cod = c.comp_mb) ".
			"\n\tleft join modelos_itens as memo on memo.mdit_cod = c.comp_memo) ".
			"\n\tleft join modelos_itens as som on som.mdit_cod = c.comp_som) ".
			"\n\tleft join modelos_itens as leitor on leitor.mdit_cod = c.comp_leitor) ". 		//LRG
			"\n\tleft join modelos_itens as os on os.mdit_cod = c.comp_os) ".			//LRG

			"\n\tleft join hw_sw as hw on hw.hws_hw_cod = c.comp_inv and hw.hws_hw_inst = c.comp_inst) ".
			"\n\tleft join softwares as soft on soft.soft_cod = hw.hws_sw_cod) ".

			"\n\tleft join localizacao as loc on loc.loc_id = c.comp_local) ".
			"\n\tleft join reitorias as reit on reit.reit_cod = loc.loc_id) ".
                
                        "\n\tleft join ocorrencias as oco on oco.equipamento = c.comp_inv),".

			"\n\tinstituicao as inst, marcas_comp as model, tipo_equip as equip, ".
                
                        "\n\tconfig_preventiva as cprev".
            "\nWHERE ".
 			"\n\t(c.comp_inst = inst.inst_cod) and (c.comp_marca = model.marc_cod) and ".
			"\n\t(c.comp_tipo_equip = equip.tipo_cod) ".
                        "and c.comp_tipo_equip IN (".$rowConf_prev['conf_tipo_equip'].")".
                        "and (c.comp_situac IN (".$rowConf_prev['conf_equip_situac']."))".
                        "and (oco.problema != ".$rowConf_prev['conf_num_chamado'].")".
                        "and (c.comp_data < '".$rowConf_prev['conf_data_inic']."')";

        $QRY["full_detail_prev_fim"] =  "\nGROUP BY comp_inv, comp_inst";
                                        
        
 	$query = $QRY["full_detail_prev_ini"];	// ../includes/queries/
        
        if (isset($_REQUEST['negado']))
	{
		$negado = $_REQUEST['negado'];
	} else
		$negado = false;


	if (empty($logico)) {
		$logico = " and ";
	}

	if (empty($sinal)) {
		$sinal = "=";
		$neg = "";
	}

	if (!empty($comp_inv)) {
		$comp_inv_flag = true;
		$query.= "$logico (c.comp_inv in (".$comp_inv.")) ";
	}

        if (isset($_REQUEST['comp_sn']))
	{
		if ($_REQUEST['comp_sn'] != '') {
			$comp_sn_flag = true;
			$comp_sn = strtoupper($_REQUEST['comp_sn']);
			$query.= "$logico (UPPER(c.comp_sn) = '".$comp_sn."') ";
		}
	}  else
		$comp_sn = "";

        if (isset($_REQUEST['comp_marca'])) {
		if (($_REQUEST['comp_marca'] != -1) && ($_REQUEST['comp_marca'] != '')) {
			$comp_marca_flag = true;
			$query.= " ".$logico." (c.comp_marca = ".$_REQUEST['comp_marca'].") ";
			$sinal_marca = "=";
		}
	}

	if (isset($_REQUEST['comp_mb'])) {
		if (($_REQUEST['comp_mb'] != -1) && ($_REQUEST['comp_mb'] != '')) {
			$comp_mb_flag = true;
			$query.= " ".$logico." (c.comp_mb = ".$_REQUEST['comp_mb'].") ";
		}
	}

	if (isset($_REQUEST['comp_proc'])) {
		if (($_REQUEST['comp_proc'] !=-1) && ($_REQUEST['comp_proc'] !='')) {
			$comp_proc_flag = true;
			$query.=" ".$logico." (c.comp_proc = ".$_REQUEST['comp_proc'].") ";
		}
	}


	if (isset($_REQUEST['comp_memo'])) {
		if (($_REQUEST['comp_memo'] != -1) && ($_REQUEST['comp_memo'] !='')) {
			if ($_REQUEST['comp_memo']==-2) {
				$comp_memo_notnull = true;
				$query.=" ".$logico." (c.comp_memo is not null)";
			} else
			if ($_REQUEST['comp_memo']==-3) {
				$comp_memo_null = true;
				$query.=" ".$logico." (c.comp_memo is null)";
			} else {
				$comp_memo_flag = true;
				$query.=" ".$logico." (c.comp_memo = ".$_REQUEST['comp_memo'].") ";
			}
		}
	}


	if (isset($_REQUEST['comp_video'])) {
		if (($_REQUEST['comp_video'] != -1) && ($_REQUEST['comp_video'] !='')) {
			$comp_video_flag = true;
			$query.= " ".$logico." (c.comp_video = ".$_REQUEST['comp_video'].") ";
		}
	}

	if (isset($_REQUEST['comp_som'])) {
		if (($_REQUEST['comp_som'] != -1) && ($_REQUEST['comp_som']!= '')) {
			$comp_som_flag = true;
			$query.= " ".$logico." (c.comp_som = ".$_REQUEST['comp_som'].") ";
		}
	}

	if (isset($_REQUEST['comp_rede'])) {
		if (($_REQUEST['comp_rede'] != -1) && ($_REQUEST['comp_rede'] !='')) {
			$comp_rede_flag = true;
			$query.= " ".$logico." (c.comp_rede = ".$_REQUEST['comp_rede'].") ";
		}
	}

	if (isset($_REQUEST['comp_modem'])) {
		if (($_REQUEST['comp_modem'] != -1) && ($_REQUEST['comp_modem'] !='')) {
			$comp_modem_flag = true;
			if ($_REQUEST['comp_modem'] ==-2) {$query.= "and (c.comp_modem is null or c.comp_modem = 0)";} else
			if ($_REQUEST['comp_modem'] ==-3) {$query.= "and (c.comp_modem is not null and c.comp_modem != 0)";} else
				$query.= " ".$logico." (c.comp_modem = ".$_REQUEST['comp_modem'].") ";
		}
        }

	if (isset($_REQUEST['comp_modelohd'])) {
		if (($_REQUEST['comp_modelohd'] != -1)&& ($_REQUEST['comp_modelohd']!='')) {
			$comp_modelohd_flag = true;
			$query.= " ".$logico." (c.comp_modelohd = ".$_REQUEST['comp_modelohd'].") ";
		}
        }

	if (isset($_REQUEST['comp_cdrom'])) {
		if (($_REQUEST['comp_cdrom'] != -1) && ($_REQUEST['comp_cdrom']!='')) {
			$comp_cdrom_flag = true;
			if ($_REQUEST['comp_cdrom'] ==-2) {$query.= "and (c.comp_cdrom is null or c.comp_cdrom = 0)";} else
			if ($_REQUEST['comp_cdrom'] ==-3) {$query.= "and (c.comp_cdrom is not null and c.comp_cdrom != 0)";} else
				$query.= " ".$logico." (c.comp_cdrom = ".$_REQUEST['comp_cdrom'].") ";
		}
	}

	if (isset($_REQUEST['comp_dvd'])) {
		if (($_REQUEST['comp_dvd'] != -1) && ($_REQUEST['comp_dvd']!='')) {
			$comp_dvd_flag = true;
			$query.= "$logico (c.comp_dvd = ".$_REQUEST['comp_dvd'].") ";
		}
        }

	if (isset($_REQUEST['comp_grav'])) {
		if (($_REQUEST['comp_grav'] != -1) && ($_REQUEST['comp_grav']!='')) {
			$comp_grav_flag = true;
			if ($_REQUEST['comp_grav'] ==-2) {$query.= "and (c.comp_grav is null or c.comp_grav = 0)";} else
			if ($_REQUEST['comp_grav'] ==-3) {$query.= "and (c.comp_grav is not null and c.comp_grav != 0)";} else
				$query.= " ".$logico." (c.comp_grav = ".$_REQUEST['comp_grav'].") ";
		}
	}


	if (isset($_REQUEST['comp_local'])) {
		if (($_REQUEST['comp_local'] != -1) && ($_REQUEST['comp_local']!='')) {
			$comp_local_flag = true;
			if ($negado== "comp_local") {
				$query.= "$logico (c.comp_local <> ".$_REQUEST['comp_local'].") ";
			} else
				$query.= "$logico (c.comp_local ".$sinal." ".$_REQUEST['comp_local'].") ";
		}
        }

	if (isset($_REQUEST['comp_reitoria'])) {// OBS: nï¿½o existe o campo comp_reitoria, apenas usei esse nome para padronizar!
		if (($_REQUEST['comp_reitoria'] != -1) && ($_REQUEST['comp_reitoria']!='')) {
			$comp_reitoria_flag = true;
			$query.= "$logico (c.comp_reitoria = ".$_REQUEST['comp_reitoria'].") ";
		}
        }
	

		
	if (isset($_REQUEST['comp_nome'])) {
		if (!empty($_REQUEST['comp_nome'])) {
			$comp_nome_flag = true;
			$query.= "$logico (c.comp_nome = ".$_REQUEST['comp_nome'].") ";
		}
        }

	if (isset($_REQUEST['comp_fornecedor'])) {
		if (($_REQUEST['comp_fornecedor'] != -1) && ($_REQUEST['comp_fornecedor']!='')) {
			$comp_fornecedor_flag = true;
			$query.= "$logico (c.comp_fornecedor = ".$_REQUEST['comp_fornecedor'].") ";
		}
        }

	if (isset($_REQUEST['comp_nf'])) {
		if (!empty($_REQUEST['comp_nf'])) {
			$comp_nf_flag = true;
			$query.= "$logico (c.comp_nf = ".$_REQUEST['comp_nf'].") ";
		}
        }

        if (($comp_inst!= -1) and ($comp_inst!='')) {
		$comp_inst_flag = true;
		if ($negado== "comp_inst") {
			$query.= "$logico (c.comp_inst not in (".$comp_inst."))";
		} else
			$query.= "$logico (c.comp_inst in (".$comp_inst."))";
			if ($comp_inst ==1) {$logo = LOGO_PATH.'/logo_unilasalle.gif';} else
			if ($comp_inst ==2) {$logo = LOGO_PATH.'/logo_colegio.gif';}
	}


	if (isset($_REQUEST['comp_tipo_equip'])) {
		if (($_REQUEST['comp_tipo_equip'] != -1) && ($_REQUEST['comp_tipo_equip']!='')) {
			$comp_tipo_equip_flag = true;
			if ($negado== "comp_tipo_equip") {
				$query.= "$logico (c.comp_tipo_equip <> ".$_REQUEST['comp_tipo_equip'].") ";
			} else
				$query.= "$logico (c.comp_tipo_equip ".$sinal." ".$_REQUEST['comp_tipo_equip'].") ";
		}
        }

	if (isset($_REQUEST['comp_fab'])) {
		if (($_REQUEST['comp_fab'] != -1) && ($_REQUEST['comp_fab']!='')) {
			$comp_fab_flag = true;
			$query.= "$logico (c.comp_fab = ".$_REQUEST['comp_fab'].") ";
		}
        }

	if (isset($_REQUEST['comp_tipo_imp'])) {
		if (($_REQUEST['comp_tipo_imp'] != -1) && ($_REQUEST['comp_tipo_imp']!='')) {
			$comp_tipo_imp_flag = true;
			$query.= "$logico (c.comp_tipo_imp = ".$_REQUEST['comp_tipo_imp'].") ";
		}
        }

	if (isset($_REQUEST['comp_polegada'])) {
		if (($_REQUEST['comp_polegada'] != -1) && ($_REQUEST['comp_polegada']!='')) {
			$comp_polegada_flag = true;
			$query.= "$logico (c.comp_polegada = ".$_REQUEST['comp_polegada'].") ";
		}
        }

	if (isset($_REQUEST['comp_resolucao'])) {
		if (($_REQUEST['comp_resolucao'] != -1) && ($_REQUEST['comp_resolucao']!='')) {
			$comp_resolucao_flag = true;
			$query.= "$logico (c.comp_resolucao = ".$_REQUEST['comp_resolucao'].") ";
		}
        }
	if (isset($_REQUEST['comp_ccusto'])) {
		if (($_REQUEST['comp_ccusto'] != -1) && ($_REQUEST['comp_ccusto']!='')) {
			$comp_ccusto_flag = true;
			$query.= "$logico (c.comp_ccusto = ".$_REQUEST['comp_ccusto'].") ";
		}
        }

	
	if (isset($_REQUEST['comp_situac'])) {
		if (($_REQUEST['comp_situac'] != -1) && ($_REQUEST['comp_situac']!='')) {
			$comp_situac_flag = true;

/*			if ($negar == "NEG_SITUACAO") {
				$query.= $logico." (c.comp_situac <> ".$_REQUEST['comp_situac'].") ";

			} else
				$query.= $logico." (c.comp_situac ".$sinal." ".$_REQUEST['comp_situac'].") ";*/

			if ($negado== "comp_situac") {
				$query.= "$logico (c.comp_situac <> ".$_REQUEST['comp_situac'].") ";
			} else
				$query.= "$logico (c.comp_situac ".$sinal." ".$_REQUEST['comp_situac'].") ";
		}
        }

	if (isset($_REQUEST['comp_data'])) { //CADASTRO
		if ( ($_REQUEST['comp_data']!='')) {
			$comp_data_flag = true;
			$comp_data = $_REQUEST['comp_data'];

/*			if (strpos($_REQUEST['comp_data'],"-")) {
				$comp_data = substr(datam2($_REQUEST['comp_data']),0,10);
			}*/
			if (strpos($_REQUEST['comp_data']," ")) {
				$tmpData = explode(" ", $_REQUEST['comp_data']);
				$comp_data = $tmpData[0];
			}

			//$comp_data = substr(datam($comp_data),0,10);

			if (isset($_REQUEST['fromDateRegister'])) {
				$query.= "$logico (c.comp_data >='".$comp_data."')";
			} else {
				$query.= "$logico (c.comp_data like ('".$comp_data."%'))";
			}
		}
        } //else
        	//$comp_data = "";

	if (isset($_REQUEST['comp_data_compra'])) { //CADASTRO
		if ( ($_REQUEST['comp_data_compra']!='')) {
			$comp_data_compra_flag = true;
			$comp_data_compra = $_REQUEST['comp_data_compra'];

			//$comp_data_compra = substr(datam($comp_data_compra),0,10);
			if (strpos($_REQUEST['comp_data_compra']," ")) {
				$tmpData = explode(" ", $_REQUEST['comp_data_compra']);
				$comp_data_compra = $tmpData[0];
			}


			$query.= "$logico (c.comp_data_compra like ('".$comp_data_compra."%'))";
		}
        }

	if (isset($_REQUEST['garantia'])) {
		if (($_REQUEST['garantia'] == 1) && ($_REQUEST['garantia']==2)) {
			$garantia_flag = true;
			if ($_REQUEST['garantia'] == 1){
				$consulta= TRANS('TXT_IN_GUARANT');
				$query.="and (date_add(c.comp_data_compra, interval tmp.tempo_meses month) >=now())";
			} else {
				$consulta= TRANS('TXT_GUARANT_OUTSIDE');
				$query.="and (date_add(c.comp_data_compra, interval tmp.tempo_meses month) <now() or comp_garant_meses is null)";
			}
		}
        }

	if (isset($_REQUEST['software'])) {
		if (($_REQUEST['software'] != -1) && ($_REQUEST['software']!='')) {
			$soft_flag = true;
			$query.= "$logico (soft.soft_cod = ".$_REQUEST['software'].") ";
		}
        }

	if (isset($_REQUEST['comp_assist'])) {
		if (($_REQUEST['comp_assist'] != -1) && ($_REQUEST['comp_assist']!='')) {
			$comp_assist_flag = true;
			if ($_REQUEST['comp_assist'] == -2) {
				$query.= "and (c.comp_assist is null)";
			} else
				$query.= "and (c.comp_assist ".$sinal." ".$_REQUEST['comp_assist'].")";
		}
        }

        //$query.=")";

		if (!isset($_REQUEST['ordena'])) {
			$ordena = "prev_min";
		} else {
			$aux = explode(",",$_REQUEST['ordena']);
			$ordena= "";
			for ($i=0;$i<count($aux);$i++){
				$ordena.=$aux[$i].$az.",";
			}
			$ordena = substr($ordena,0,-1);
		}

		if (isset($_REQUEST['VENCIMENTO'])){
			$query.=  "AND comp_tipo_equip NOT IN (5) ".
				"AND date_add(date_format(comp_data_compra, '%Y-%m-%d') , INTERVAL tempo_meses MONTH) = '".$_REQUEST['VENCIMENTO']."'";
		}
                
                // 
                
		$query.= $QRY["full_detail_prev_fim"];
                
                //ORDER
                $query.= "  order by ".$ordena."";

		$traduzOrdena = strtr("$ordena", $traduz);

                    //DEBUG 
                    //dump($query);
                
##################################################################################
	$qtdTotal = $query;
	$resultadoTotal = mysql_query($qtdTotal) or die (TRANS('MSG_ERR_IN_THE_QUERY').':<br>'.$qtdTotal);
	$linhasTotal = mysql_num_rows($resultadoTotal); //Aqui armazedo a quantidade total de registros
##################################################################################

		if ( (!isset($_REQUEST['visualiza'])) || ($_REQUEST['visualiza']=='tela')) { //condiï¿½ï¿½o para montar na tela os botï¿½es de navegaï¿½ï¿½o

			/*------------------------------------------------------------------------------
			@$min = PRIMEIRO REGISTRO A SER EXIBIDO
			@$max = QUANTIDADE DE REGISTROS POR Pï¿½GINA
			@$top = Nï¿½MERO DO ï¿½LTIMO REGISTRO EXIBIDO DA Pï¿½GINA
			@$base = Nï¿½MERO DO PRIMEIRO REGISTRO EXIBIDO DA Pï¿½GINA
			--------------------------------------------------------------------------------*/

// 			$min = 0;
// 			$maxAux = 0;
// 			$minAux = 0;
// 			$page = 50;

			if (!isset($_POST['min']))  {
				$min =0;
			} else $min = $_POST['min'];

			if (!isset($_POST['max']))  {
				$max =$PAGE_SIZE;
				if ($max > $linhasTotal) {
					$maxAux = $max;
					$max = $linhasTotal;
				}
			} else {
				$max = $_POST['max'] ;//$linhasTotal;
				$maxAux = $_POST['max'];
				if ($max > $linhasTotal) {
					$maxAux = $max;
					$max = $linhasTotal;
				}
			}

			if (!isset($_POST['top'])) {
				if ($max < $linhasTotal) {
					$top = $max;
				} else
					$top = $linhasTotal;
			} else
				$top = $_POST['top'];

			if (!isset($_POST['base'])) {
				$base = $min+1;
			} else
				$base = $_POST['base'];

			if (isset($_POST['avancaUm'])) {
				$minAux = $min;
				$min += $max;
				if ($min >=($linhasTotal)) {
					$min = $minAux;
				}
				$top += $max;
				if ($top >$linhasTotal) {
					$base = $min+1;
					$top = $linhasTotal;
				} else {
					if ($base < (($top - $max))) {
						$base += $max;
					} else {
						$base-=$max;
					}
				}
			} else
			if (isset($_POST['avancaFim'])) {
				$minAux = $min;
				$min=$linhasTotal - $PAGE_SIZE;
				if ($min <=0) {
					$min = $minAux;
				}
				$top = $linhasTotal;
				$base = ($linhasTotal - $PAGE_SIZE)+1;
			} else
			if (isset($_POST['avancaTodos'])) {
				$max=$linhasTotal;
				$min=0;
				$top = $linhasTotal;
				$base = $linhasTotal - $max;
			} else
			if (isset($_POST['voltaUm']) ) {
				if (($_POST['max']==$linhasTotal) && ($_POST['min']==0)) {$max=$_POST['maxAux']; $min=$linhasTotal;}
					//Estï¿½ exibindo todos os registros na tela!

				$min-=$_POST['max'];
				if ($min<0) {$min=0;};

				if (($top - $base) < $max) {
					$top = $base -1;
				} else $top-=$max;
				$base-=$max;
			} else
			if (isset($_POST['voltaInicio']) ) {
				$min=0;
				//$max=$_POST['maxAux'];
				$max = $PAGE_SIZE;
				$top = $max;
				$base = 1;
			}

			$query.=" LIMIT ".$min.", ".$max."";

			if ($top > $linhasTotal) {
				$top = $linhasTotal;
			} else
			if ($top < $max) {
				$top = $max;
			}
			if ($base < 1) {
				$base = 1;
			}
		}


	$resultado = mysql_query($query) or die (TRANS('MSG_ERR_IN_THE_QUERY').': <BR>'.$query);
	$resultadoAux = mysql_query($query);
        $linhas = mysql_num_rows($resultado);

        $row = mysql_fetch_array($resultadoAux);

	######################################################

		//Titulo da consulta que retorna o critï¿½rio de pesquisa.
		//$texto ="com: ";
		$texto ="";
		$tam = (strlen($texto));
		$param ="&";
		$tamParam = (strlen($param));

		if ($comp_tipo_equip_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('FIELD_TYPE_EQUIP')."</b> = ".$row['equipamento']."]"; //Escreve o critï¿½rio de pesquisa
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_tipo_equip=".$_REQUEST['comp_tipo_equip'].""; 	//Monta a lista de parï¿½metros para a consulta
		};
		if ($comp_tipo_imp_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('FIELD_TYPE_PRINTER')."</b> = ".$row['impressora']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_tipo_imp=".$_REQUEST['comp_tipo_imp']."";
		};
		if ($comp_polegada_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('FIELD_MONITOR')."</b> = ".$row['polegada_nome']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_polegada=".$_REQUEST['comp_polegada']."";
		};

		if ($comp_resolucao_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('FIELD_SCANNER')."</b> = ".$row['resol_nome']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_resolucao=".$_REQUEST['comp_resolucao']."";
		};

		if ($comp_inv_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('OCO_FIELD_TAG')."</b> = ".$comp_inv."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_inv=".$comp_inv."";
		};

		if ($comp_sn_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_SN')."</b> = ".$row['serial']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_sn=".$_REQUEST['comp_sn']."";
		};

		if ($comp_fab_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_MANUFACTURE')."</b> = ".$row['fab_nome']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_fab=".$_REQUEST['comp_fab']."";
		};


		if ($comp_marca_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_MODEL')."</b> = ".$row['modelo']."]"; //$sinal
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_marca=".$_REQUEST['comp_marca']."";
		};

		if ($comp_mb_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('FIELD_MB')."</b> = ".$row['fabricante_mb']." ".$row['mb']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_mb=".$_REQUEST['comp_mb']."";
		};
		if ($comp_proc_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_PROC')."</b> = ".$row['processador']." ".$row['clock']." ".$row['proc_sufixo']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_proc=".$_REQUEST['comp_proc']."";
		};
	  	if ($comp_memo_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_MEMO')."</b> = ".$row['memoria']."".$row['memo_sufixo']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_memo=".$_REQUEST['comp_memo']."";
		};
	  	if ($comp_memo_notnull) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_MEMO')."</b> = ".TRANS('FIELD_NOT_NULL')."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_memo=".$_REQUEST['comp_memo']."";
		};
	  	if ($comp_memo_null) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_MEMO')."</b> = ".TRANS('FEILD_NULL')."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_memo=".$_REQUEST['comp_memo']."";
		};

		if ($comp_video_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_VIDEO')."</b> = ".$row['fabricante_video']." ".$row['video']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_video=".$_REQUEST['comp_video']."";
		};
		if ($comp_som_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_SOM')."</b> = ".$row['fabricante_som']." ".$row['som']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_som=".$_REQUEST['comp_som']."";
		};
		if ($comp_cdrom_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if ($_REQUEST['comp_cdrom']==-2) {$texto.="[<b>".TRANS('MNL_CDROM')."</b> = ".TRANS('MSG_NOT_POSS_NONE')."]";} else
			if ($_REQUEST['comp_cdrom']==-3) {$texto.="[<b>".TRANS('MNL_CDROM')."</b> = ".TRANS('MSG_POSS_ANY_MODEL')."]";} else
			$texto.="[<b>".TRANS('MNL_CDROM')."</b> = ".$row['fabricante_cdrom']." ".$row['cdrom']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_cdrom=".$_REQUEST['comp_cdrom']."";
		};

		if ($comp_grav_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if ($_REQUEST['comp_grav']==-2) {$texto.="[<b>".TRANS('FIELD_RECORD_CD')."</b> = ".TRANS('MSG_NOT_POSS_NONE')."]";} else
			if ($_REQUEST['comp_grav']==-3) {$texto.="[<b>".TRANS('FIELD_RECORD_CD')."</b> = ".TRANS('MSG_POSS_ANY_MODEL')."]";} else
			$texto.="[<b>".TRANS('FIELD_RECORD_CD')."</b> = ".$row['fabricante_gravador']." ".$row['gravador']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_grav=".$_REQUEST['comp_grav']."";
		};

		if ($comp_dvd_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if ($_REQUEST['comp_dvd']==-2) {$texto.="[<b>".TRANS('MNL_DVD')."</b> = ".TRANS('MSG_NOT_POSS_NONE')."]";} else
			if ($_REQUEST['comp_dvd']==-3) {$texto.="[<b>".TRANS('MNL_DVD')."</b> = ".TRANS('MSG_POSS_ANY_MODEL')."]";} else
			$texto.="[<b>".TRANS('MNL_DVD')."</b> = ".$row['fabricante_dvd']." ".$row['dvd']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_dvd=".$_REQUEST['comp_dvd']."";
		};


		if ($comp_modem_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if ($_REQUEST['comp_modem']==-2) {$texto.="[<b>".TRANS('FIELD_MODEM')."</b> = ".TRANS('MSG_NOT_POSS_NONE')."]";} else
			if ($_REQUEST['comp_modem']==-3) {$texto.="[<b>".TRANS('FIELD_MODEM')."</b> = ".TRANS('MSG_POSS_ANY_MODEL')."]";} else
			$texto.="[<b>".TRANS('FIELD_MODEM')."</b> = ".$row['fabricante_modem']." ".$row['modem']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_modem=".$_REQUEST['comp_modem']."";
		};

		if ($comp_modelohd_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_HD')."</b> = ".$row['fabricante_hd']." ".$row['hd_capacidade']."".$row['hd_sufixo']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_modelohd=".$_REQUEST['comp_modelohd']."";
		};
		if ($comp_rede_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_REDE')."</b> = ".$row['rede_fabricante']." ".$row['rede']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_rede=".$_REQUEST['comp_rede']."";
		};
		if ($comp_local_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_LOCALIZATION')."</b> = ".$row['local']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_local=".$_REQUEST['comp_local']."";
		};
		if ($comp_reitoria_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_MAJOR')."</b> = ".$row['reitoria']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_reitoria=".$_REQUEST['comp_reitoria']."";
		};
		
		//######################### by LRG #############################

		if ($comp_leitor_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>Leitor</b> = ".$row['leitor']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_leitor=".$_REQUEST['comp_leitor']."";
		};
		
		if ($comp_os_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_MAJOR')."</b> = ".$row['os']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_os=".$_REQUEST['comp_os']."";
		};

		if ($comp_sn_os_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_MAJOR')."</b> = ".$row['sn_os']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_sn_os=".$_REQUEST['comp_sn_os']."";
		};
		
		//#########################  FIM  ##############################
		
		if ($comp_fornecedor_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_VENDOR')."</b> = ".$row['fornecedor_nome']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_fornecedor=".$_REQUEST['comp_fornecedor']."";
		};
		if ($comp_nf_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('FIELD_FISCAL_NOTES')."</b> = ".$row['nota']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_nf=".$_REQUEST['comp_nf']."";
		}


		if (($comp_ccusto_flag)|| ((isset($_REQUEST['visualiza']) && $_REQUEST['visualiza']=='termo'))) {
			if (strlen($texto) > $tam) $texto.= ", ";

			$CC =  $row['ccusto'];
			if ($CC =="") $CC = -1;
			$query2 = "select * from ".DB_CCUSTO.".".TB_CCUSTO." where ".CCUSTO_ID."= $CC "; //
			$resultado2 = mysql_query($query2);
			$rowCC= mysql_fetch_array($resultado2);
			$centroCusto = $rowCC[CCUSTO_DESC];
			$custoNum = $rowCC[CCUSTO_COD];
			$texto.="[<b>".TRANS('FIELD_CENTER_COST')."</b> = ".$centroCusto."]";

			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_ccusto = ".$_REQUEST['comp_ccusto']."";
		}

		if ($comp_inst_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";

			$sqlA ="select inst_nome as inst from instituicao where inst_cod in (".$comp_inst.")";
			$resultadoA = mysql_query($sqlA);
			//$rowA = mysql_fetch_array($resultadoA);
  			//if (($resultadoA = mysql_query($sqlA)) && (mysql_num_rows($resultadoA) > 0) ) {
				while ($rowA = mysql_fetch_array($resultadoA)) {
					$msgInst.= $rowA['inst'].', ';
				}
				$msgInst = substr($msgInst,0,-2);
			//}

			$texto.="[<b>".TRANS('FIELD_INSTITUTION')."</b> = ".$msgInst."]";
			if (strlen($param) > $tamParam) $param.= "&";

			$p_temp = explode(",",$comp_inst);

			for ($i=0;$i<count($p_temp);$i++){
				$param.="comp_inst%5B%5D=".$p_temp[$i]."&";  //%5B%5D  Caracteres especiais do HTML para entender arrays!!
			}
			$param = substr($param,0,-1);
			//$param.= "comp_inst in ($comp_inst)";
		}

		if ($comp_situac_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if (strlen($param) > $tamParam) $param.= "&";

/*			if ($negar=="NEG_SITUACAO") {
				$texto.="[<b>".$TRANS["cx_situacao"]."</b> <> ".$row['situac_nome']."]";
				$param.= "comp_situac <> ".$_REQUEST['comp_situac']."";
			} else {
				$texto.="[<b>".$TRANS["cx_situacao"]."</b> = ".$row['situac_nome']."]";
				$param.= "comp_situac=".$_REQUEST['comp_situac']."";
			}*/

			$texto.="[<b>".TRANS('COL_SITUAC')."</b> = ".$row['situac_nome']."]";
			$param.= "comp_situac=".$_REQUEST['comp_situac']."";
		};
		if ($comp_data_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if (isset($_REQUEST['fromDateRegister'])){
				$texto.="[<b>".TRANS("COL_SUBSCRIBE_DATE")."&nbsp;".TRANS('INV_FROM_DATE_REGISTER')."</b> = ".$comp_data."]";
			} else {
				$texto.="[<b>".TRANS("COL_SUBSCRIBE_DATE")."</b> = ".$comp_data."]";
			}
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_data=".$_REQUEST['comp_data']."";
		};
		if ($comp_data_compra_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('FIELD_DATE_PURCHASE')."</b> = ".$comp_data_compra."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_data_compra=".$_REQUEST['comp_data_compra']."";
		};

		if ($garantia_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('TXT_IN_GUARANT')."</b> = ".$consulta."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "garantia=".$_REQUEST['garantia']."";
		};

		if ($soft_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_SOFT')."</b> = ".$row['software']." ".$row['versao']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "software=".$_REQUEST['software']."";
		};

		if ($comp_assist_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";

			if ($comp_assist==-2) {$texto.="[<b>".TRANS('FIELD_ASSISTENCE')."</b> = ".TRANS('MSG_NOT_DEFINE')."]";} else
				$texto.="[<b>".TRANS('FIELD_ASSISTENCE')."</b> = ".$row['assistencia']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_assist=".$_REQUEST['comp_assist']."";
		};

		if (isset($_REQUEST['VENCIMENTO'])) {
			if (strlen($texto) > $tam) $texto.= ", ";

			$texto.="[<b>".TRANS('WARRANTY_EXPIRE')."</b> = ".$_REQUEST['VENCIMENTO']."]";
			$param.= "VENCIMENTO=".$_REQUEST['VENCIMENTO']."";
		};

		if (strlen($texto)==$tam) {$texto.="[<b>".TRANS('COL_TYPE')."</b> = ".TRANS('FIELD_ALL')."]";}; //Se nenhum campo foi selecionado para a consulta entï¿½o todos os equipamentos sï¿½o listados!!

 		$lim = (strlen($texto)-7);
		$texto2 = (substr($texto,6,$lim));

		#########################################################
		geraLog(LOG_PATH.'invmon.txt',date("d-m-Y H:i:s"),$_SESSION['s_usuario'],$_SERVER['PHP_SELF'],$texto);
		#########################################################

	if ($linhas == 0)
	{
		//print $query."<br><br><a class='likebutton' onClick=\"javascript:history.back();\">Voltar</a>"; exit;

		print "<script>mensagem('".TRANS('MSG_THIS_CONS_NOT_RESULT')."')</script>";
		//dump($query);
		print "<script>history.back()</script>";
		exit;
	} else
	if ($linhas>1){
		if (isset($_REQUEST['visualiza']) && $_REQUEST['visualiza'] =='impressora') {
			print cabecalho($logo,'<a href=abertura.php>Ocomon</a>',$hoje,$header);
			print "<tr><TD bgcolor='".TD_COLOR."'><i>".TRANS('FIELD_CRITE_EXIBIT').": ".$texto.".</i></td></tr><br><br>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B>".TRANS('FOUND')." <font color='red'>".$linhas."</font> ".TRANS('TXT_REG_ORDER_BY')." <u>".$traduzOrdena."</u>: </B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='consulta_comp.php'>[ ".TRANS('LINK_NEW_REPORT')." ]</a>.</B></TD></TR>";
		} else

		if (isset($_REQUEST['visualiza']) && $_REQUEST['visualiza'] =='termo') {
			
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='transito') {
			
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='config') {
			
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='relatorio') {
			
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='mantenedora1') {
			
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] == 'texto') {
			
                        
		} else {  //Visualizaï¿½ï¿½o normal na tela do sistema!!
			print "<table border='0' cellspacing='1' width='100%'>";
			print "<tr><TD with='70%' align='left'><i>".TRANS('FIELD_CRITE_EXIBIT').": ".$texto.".</i></td>
					<td width='30%' align='left'>
					<form name='checagem' method='post' action=''>
						<input  type='checkbox' class='radio' name='encadeia' id='idEncadeia' value='ok' ".$checked." onChange=\"checar();\"><a title='".TRANS('HNT_PIPE')."!'>".TRANS('FIELD_CHAIN_NAV')."</a>";
				print "<input  type='checkbox' class='radio' name='ckpopup' value='ok'><a title='".TRANS('MSG_CONS_DETAIL_EQUIP_POPUP')."'>popup</a>";
				print "<input  type='checkbox' class='radio' name='negada' value='ok'><a title='".TRANS('HNT_NAV_EXCLIVE')."!'>".TRANS('NOT')."</a>";
			print "	</form></td></tr><br>";

			print "</table>";


			print "<table border='0' cellspacing='1' summary=''>";


			print "<FORM method='post' action='".$_SERVER['PHP_SELF']."'>";
			print "<TR>";
			$min++;
			$stilo = "style='{height:17px; width:30px; background-color:#DDDCC5; color:#5E515B; font-size:11px;}'"; //Estilo dos botï¿½es de navegaï¿½ï¿½o
			$stilo2 = "style='{height:17px; width:50px; background-color:#DDDCC5; color:#5E515B;font-size:11px;}'";
			//if ($avanca==$TRANS["bt_todos"]) {$top=$linhasTotal;} else$top=$min+($max-1);
			print "<TD width='750' align='left' ><B>".TRANS('FOUND')." <font color='red'>".$linhasTotal."</font> ".TRANS('TXT_REG_ORDER_BY')." <u>".$traduzOrdena."</u>. ".TRANS('TXT_SHOW_OF')." <font color='red'>".$min."</font> ".TRANS('TXT_THE')." <font color='red'>".$top."</font>.</B></TD>";
			//print "<TD width='50' align='left' ></td>";


				print "<TD width='30%' align='right'><input  type='submit' class='button_ba2' name='voltaInicio' value=' ' ".
					"title='".TRANS('VIEW_THE')." ".$max." ".TRANS('FIRST_RECORDS')."'> <input  type='submit' class='button_ba1'  name='voltaUm' value=' ' ".
					"title='".TRANS('VIEW_THE')." ".$max." ".TRANS('PREVIOUSLY_RECORDS')."'> <input  type='submit' class='button_go1'  name='avancaUm' value=' ' ".
					"title='".TRANS('VIEW_THE_NEXT')." ".$max." ".TRANS('RECORDS')."'> <input  type='submit' class='button_go2'  name='avancaFim' value=' ' ".
					"title='".TRANS('VIEW_THE_LAST')." ".$max." ".TRANS('RECORDS')."'> <input  type='submit' class='button'  name='avancaTodos' value='Todas' ".
					"title='".TRANS('VIEW_ALL')." ".$linhasTotal." ".TRANS('RECORDS')."'></td>";

			print "</tr>";
			$min--;



			print "<input type='hidden' value='".$min."' name='min'>";
			print "<input type='hidden' value='".$max."' name='max'>";
			print "<input type='hidden' value='".$maxAux."' name='maxAux'>";
			print "<input type='hidden' value='".$base."' name='top'>";
			print "<input type='hidden' value='".$top."' name='top'>";
			print "<input type='hidden' value='".$ordena."' name='ordena'>";
			print "<input type='hidden' value='".$comp_inv."' name='comp_inv'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_sn'])."' name='comp_sn'>";
			if (isset($comp_sn))
				print "<input type='hidden' value='".$comp_sn."' name='comp_sn'>";
			if (isset($_REQUEST['comp_marca']))
				print "<input type='hidden' value='".$_REQUEST['comp_marca']."' name='comp_marca'>";
			if (isset($_REQUEST['comp_mb']))
				print "<input type='hidden' value='".$_REQUEST['comp_mb']."' name='comp_mb'>";
			if (isset($_REQUEST['comp_proc']))
				print "<input type='hidden' value='".$_REQUEST['comp_proc']."' name='comp_proc'>";
			if (isset($_REQUEST['comp_memo']))
				print "<input type='hidden' value='".$_REQUEST['comp_memo']."' name='comp_memo'>";
			if (isset($_REQUEST['comp_video']))
				print "<input type='hidden' value='".$_REQUEST['comp_video']."' name='comp_video'>";
			if (isset($_REQUEST['comp_som']))
				print "<input type='hidden' value='".$_REQUEST['comp_som']."' name='comp_som'>";
			if (isset($_REQUEST['comp_rede']))
				print "<input type='hidden' value='".$_REQUEST['comp_rede']."' name='comp_rede'>";
			if (isset($_REQUEST['comp_modem']))
				print "<input type='hidden' value='".$_REQUEST['comp_modem']."' name='comp_modem'>";
			if (isset($_REQUEST['comp_modelohd']))
				print "<input type='hidden' value='".$_REQUEST['comp_modelohd']."' name='comp_modelohd'>";

			if (isset($_REQUEST['comp_cdrom']))
				print "<input type='hidden' value='".$_REQUEST['comp_cdrom']."' name='comp_cdrom'>";
			if (isset($_REQUEST['comp_dvd']))
				print "<input type='hidden' value='".$_REQUEST['comp_dvd']."' name='comp_dvd'>";
			if (isset($_REQUEST['comp_grav']))
				print "<input type='hidden' value='".$_REQUEST['comp_grav']."' name='comp_grav'>";
			if (isset($_REQUEST['comp_local']))
				print "<input type='hidden' value='".$_REQUEST['comp_local']."' name='comp_local'>";
			if (isset($_REQUEST['comp_nome']))
				print "<input type='hidden' value='".$_REQUEST['comp_nome']."' name='comp_nome'>";
			if (isset($_REQUEST['comp_fornecedor']))
				print "<input type='hidden' value='".$_REQUEST['comp_fornecedor']."' name='comp_fornecedor'>";
			if (isset($_REQUEST['comp_nf']))
				print "<input type='hidden' value='".$_REQUEST['comp_nf']."' name='comp_nf'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_inst'])."' name='comp_inst[]'>";
			if (isset($_REQUEST['comp_inst']))
				print "<input type='hidden' value='".$comp_inst."' name='comp_inst[]'>";
			if (isset($_REQUEST['comp_tipo_equip']))
				print "<input type='hidden' value='".$_REQUEST['comp_tipo_equip']."' name='comp_tipo_equip'>";
			if (isset($_REQUEST['comp_fab']))
				print "<input type='hidden' value='".$_REQUEST['comp_fab']."' name='comp_fab'>";
			if (isset($_REQUEST['comp_tipo_imp']))
				print "<input type='hidden' value='".$_REQUEST['comp_tipo_imp']."' name='comp_tipo_imp'>";
			if (isset($_REQUEST['comp_polegada']))
				print "<input type='hidden' value='".$_REQUEST['comp_polegada']."' name='comp_polegada'>";
			if (isset($_REQUEST['comp_resolucao']))
				print "<input type='hidden' value='".$_REQUEST['comp_resolucao']."' name='comp_resolucao'>";
			if (isset($_REQUEST['comp_ccusto']))
				print "<input type='hidden' value='".$_REQUEST['comp_ccusto']."' name='comp_ccusto'>";
			if (isset($_REQUEST['comp_situac']))
				print "<input type='hidden' value='".$_REQUEST['comp_situac']."' name='comp_situac'>";

			if (isset($comp_data))
				print "<input type='hidden' value='".$comp_data."' name='comp_data'>";
			if (isset($comp_data_compra))
				print "<input type='hidden' value='".$comp_data_compra."' name='comp_data_compra'>";

			//print "<input type='hidden' value='".isset($_REQUEST['comp_data'])."' name='comp_data'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_data_compra'])."' name='comp_data_compra'>";
			if (isset($_REQUEST['garantia']))
				print "<input type='hidden' value='".$_REQUEST['garantia']."' name='garantia'>";
			if (isset($_REQUEST['negado']))
				print "<input type='hidden' value='".$_REQUEST['negado']."' name='negado'>";


			print "</form>";
			print "</table>";

		}
	}
	 else //APENAS 1 REGISTRO
	{
		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='impressora') {
			print cabecalho('<a href=abertura.php>OcoMon</a>','',TRANS('TXT_REPORT_PERSON'));
			print "<tr><TD bgcolor='".TD_COLOR."'><i>".TRANS('FIELD_CRITE_EXIBIT').": ".$texto.".</i></td></tr><br><br>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B>".TRANS('FOUND_ONE')."<font color='red'>1</font>".TRANS('TXT_CAD_REG_SYSTEM').":</B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='consulta_comp.php'>[ ".TRANS('LINK_NEW_REPORT')." ]</a>.</B></TD></TR>";
		} else
		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='termo') {
			
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='transito') {
			
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='texto') {
			
		} else { //Visualizaï¿½ï¿½o normal na tela do sistema!!
			print "<table border='0' cellspacing='1' width='100%'>";
			print "<tr><TD with='70%' align='left'><i>".TRANS('FIELD_CRITE_EXIBIT').": ".$texto."</i></td><td width='30%' align='left'><form name='checagem' method='post' action=''><input type='checkbox' name='encadeia' value='ok' disabled><a title='".TRANS('HNT_PIPE')."'>".TRANS('FIELD_CHAIN_NAV')."</a>";
			print "<input  type='checkbox' class='radio' name='ckpopup' value='ok' disabled><a title='".TRANS('MSG_CONS_DETAIL_EQUIP_POPUP')."'>popup</a>";
			print "</form></td></tr><br>";
			print "<TR><td class='line'><B>".TRANS('FOUND_ONE')." <font color='red'>1</font> ".TRANS('TXT_CAD_REG_SYSTEM').":</B></TD><td class='line'></td></TR>";
			print "</table>";
		}
	}
		print "</TD>";

		// Se a consulta foi solicitada para a impressora ele monta outra saï¿½da tipo relatï¿½rio
                //
                // LRG - Talvez eu use
		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='impressora') {
			print "<hr width='80%' align='center'>";
			$i=0;
        		$j=2;
			while ($row = mysql_fetch_array($resultado)) {
				if ($j % 2)
				{
					$color =  'white';//BODY_COLOR;
				}
				else
				{
					$color = 'white';
				}
				$j++;

				//print "<title>InvMon - Relatï¿½rio</title>";
				print "<TABLE WIDTH='80%' BORDER='0' CELLPADDING='4' CELLSPACING='0' align='center'>";
				print "<link rel=stylesheet type=text/css href='../includes/css/estilos.css.php'>";
				print "	<COL WIDTH='10%'>";
				print "<COL WIDTH='20%'>";
				print "	<COL WIDTH='10%'>";
				print "	<COL WIDTH='20%'>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT'>".strtoupper(TRANS('COL_TYPE')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT'>".$row['equipamento']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT'>".strtoupper(TRANS('COL_MANUFACTURE')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT'>".$row['fab_nome']."</P>";
				print "			</TH>";
				print "		</TR>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('OCO_FIELD_TAG')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><a href='mostra_consulta_inv.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."'>".$row['etiqueta']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_SN')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['serial']."</P>";
				print "			</TH>";
				print "		</TR>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_MODEL')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['modelo']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_NF')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['nota']."</P>";
				print "			</TH>";
				print "		</TR>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_SITUAC')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['situac_nome']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_LOCALIZATION')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['local']."</P>";
				print "			</TH>";
				print "		</TR>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('OCO_FIELD_UNIT')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['instituicao']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><BR>";
				print "				</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><BR>";
				print "				</P>";
				print "			</TH>";
				print "		</TR>";
				print "</TABLE>		";
				print "		<hr width='80%' align='center'>";
                print" <hr width='80%' align='center'>";
                $i++;
		}

		print "<b><a href='abertura.php'>OcoMon</a> - ".TRANS('MENU_TTL_MOD_INV').". ".TRANS('OCO_DATE').": ".$hoje.".</b>";
        	print "</TABLE>";

	} else 
            
        if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='termo') {
            
	} else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='transito') {
            
	} else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='config') {
            
	} else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='relatorio') {

	} else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='mantenedora1') {
            
	} else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='texto') { 
            
	}
	else ####### Mostra Consulta normal na tela principal do sistema!!
	{
		print "<fieldset><legend>Máquinas sem Preventiva</legend>";
		print "<TABLE border='0' cellpadding='3' cellspacing='0' align='center' width='100%'>";
		print "<TR class='header'>".
				"<TD class='line' valign='middle'><b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?ordena=etiqueta&coluna=etiqueta&ordenado=".$ordenado."&".$param."')\" title='".TRANS('HNT_ORDER_BY_TAG').".'>".TRANS('OCO_FIELD_TAG')."</a>".$ICON_ORDER['etiqueta']."</TD>".
				"<td class='line'><b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?ordena=instituicao,etiqueta&coluna=instituicao&ordenado=".$ordenado."&".$param."')\" title='".TRANS('HNT_ORDER_BY_UNIT')."'.>".TRANS('OCO_FIELD_UNIT')."</a>".$ICON_ORDER['instituicao']."</TD>".
                                
                                "<td class='line'><b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?ordena=equipamento,modelo&coluna=tipo&ordenado=".$ordenado."&".$param."')\" title='".TRANS('HNT_ORDER_BY_TYPE_EQUIP')."'>".TRANS('COL_TYPE')."</a>".$ICON_ORDER['tipo']."</TD>".
				"<td class='line'><b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?ordena=fab_nome,modelo&coluna=modelo&ordenado=".$ordenado."&".$param."')\" title='".TRANS('HNT_ORDER_BY_MODEL_EQUIP')."'>".TRANS('COL_MODEL')."</a>".$ICON_ORDER['modelo']."</TD>".
				"<td class='line'><b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?ordena=local&coluna=local&ordenado=".$ordenado."&".$param."')\" title='".TRANS('HNT_ORDER_BY_LOCAL')."'>".TRANS('COL_LOCALIZATION')."</a>".$ICON_ORDER['local']."</TD>".
				"<td class='line'><b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?ordena=situac_nome&coluna=situacao&ordenado=".$ordenado."&".$param."')\" title='".TRANS('HNT_ORDER_BY_SITUAC')."'>".TRANS('COL_SITUAC')."</a>".$ICON_ORDER['situacao']."</TD>".
                                
                                "<td class='line'><b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?ordena=prev_min&coluna=preventiva_min&ordenado=".$ordenado."&".$param."')\" title='".TRANS('HNT_ORDER_BY_PREV')."'.>".TRANS('OCO_FIELD_PREV_MIN')."</a>".$ICON_ORDER['preventiva_min']."</TD>".
                                "<td class='line'><b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?ordena=prev_max&coluna=preventiva_max&ordenado=".$ordenado."&".$param."')\" title='".TRANS('HNT_ORDER_BY_PREV')."'.>".TRANS('OCO_FIELD_PREV_MAX')."</a>".$ICON_ORDER['preventiva_max']."</TD>".
                                "<td class='line'><b>Iniciar Preventiva</TD>";
                print "</TR>";        
		$i=0;
		$j=2;
		$cont=0;
  		while ($row = mysql_fetch_array($resultado)) {
			$cont++;
			if ($j % 2)
			{
				if (($row['situac_destaque']=='1')) {//Situaï¿½ï¿½o com destaque
					$color="#FF0000";
					$alerta = "style='color:red;'";
					$trClass = "lin_alerta_par";
					$corDestaque = '#FF0000';
				} else {
					$color =  BODY_COLOR;
					$alerta = "";
					$trClass = "lin_par";
					$corDestaque = $_SESSION['s_colorLinPar'];
				}
			}
			else
			{
				if (($row['situac_destaque']=='1')) {
					$color='#FF0000';
					$alerta = "style='color:red;'";
					$trClass = "lin_alerta_impar";
					$corDestaque = '#FF0000';
				} else {
					$color = 'white';
					$alerta = "";
					$trClass = "lin_impar";
					$corDestaque = $_SESSION['s_colorLinImpar'];
				}
			}
                        
                        $data_controle = date_diff2($row['prev_max'], date('Y-m-d' ));
                            
                        if ($data_controle > '30'){
                            $alerta2 = "style='color:red;bold;'";
                        } else {
                            $alerta2 = "";
                        }
                        
                	$j++;
			//print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
			print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";


			//print "<td class='line'><a ".$alerta." onClick=\"montaPopup('mostra_consulta_inv.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."')\" title='".TRANS('HNT_SHOW_DATEIL_EQUIP_CAD')."'>".$row['etiqueta']."</a></TD>";
			
			//Click Ajax
			print "<td class='line'><a ".$alerta." onClick=\"exibeEscondeImg('idTr".$j."'); exibeEscondeImg('idDivLinha".$j."'); ajaxFunction('idDivLinha".$j."', 'mostra_consulta_inv.php', 'idLoad', 'comp_inv=idEtiqueta".$j."', 'comp_inst=idUnidade".$j."' , 'INDIV=idINDIV');\" title='".TRANS('HNT_SHOW_DATEIL_EQUIP_CAD')."'>".$row['etiqueta']."</a></TD>";

			print "<td class='line'><a ".$alerta." title='".TRANS('HNT_FILTER_EQUIP_UNIT')." ".$row['instituicao'].".' href=\"javascript:monta_link('?comp_inst%5B%5D=".$row['cod_inst']."&ordena=fab_nome,modelo,local,etiqueta&coluna=instituicao&ordenado=".$ordenado."','".$param."','comp_inst')\">".$row['instituicao']."</a></td>";
			print "<td class='line'><a ".$alerta." title='".TRANS('HNT_FILTER_EQUIP_TYPE')." ".$row['equipamento'].".' href=\"javascript:monta_link('?comp_tipo_equip=".$row['tipo']."&ordena=fab_nome,modelo,local,etiqueta&coluna=tipo&ordenado=".$ordenado."','".$param."','comp_tipo_equip')\">".$row['equipamento']."</a></td>";
			print "<td class='line'><a ".$alerta." title='".TRANS('HNT_FILTER_EQUIP_MODEL')." ".$row['fab_nome']." ".$row['modelo'].".' href=\"javascript:monta_link('?comp_marca=".$row['modelo_cod']."&ordena=local,etiqueta&coluna=modelo&ordenado=".$ordenado."','".$param."','comp_marca')\">".$row['fab_nome']." ".$row['modelo']."</a></td>";
			print "<td class='line'><a ".$alerta." title='".TRANS('HNT_FILTER_EQUIP_LOCAL_SECTOR')." ".$row['local'].".' href=\"javascript:monta_link('?comp_local=".$row['tipo_local']."&ordena=equipamento,fab_nome,modelo,etiqueta&coluna=local&ordenado=".$ordenado."','".$param."','comp_local')\">".$row['local']."</a></td>";
			print "<td class='line'><a ".$alerta." title='".TRANS('HNT_FILTER_EQUIP_SITUAC')." ".$row['situac_nome'].".' href=\"javascript:monta_link('?comp_situac=".$row['situac_cod']."&ordena=fab_nome,modelo,local,etiqueta&coluna=modelo&ordenado=etiqueta','".$param."','NEG_SITUACAO')\">".$row['situac_nome']."</a></td>";
			
                        print "<td class='line'><a ".$alerta2." title='' href=\"javascript:monta_link('?prev_min=".$row['prev_min']."&ordena=equipamento,fab_nome,modelo,etiqueta&coluna=local&ordenado=".$ordenado."','".$param."','comp_local')\">".formatDate($row['prev_min'])."</a></td>";
			print "<td class='line'><a ".$alerta2." title='' href=\"javascript:monta_link('?prev_max=".$row['prev_max']."&ordena=fab_nome,modelo,local,etiqueta&coluna=modelo&ordenado=etiqueta','".$param."','NEG_SITUACAO')\">".formatDate($row['prev_max']). "</a></td>";
			
                        print "<td class='line'><a title='Iniciar Chamado de Preventiva' href=\"../../ocomon/geral/incluir.php?invTag=".$row['etiqueta']."&problema=".$rowConf_prev['conf_num_chamado']."&descricao=Preventiva Agendada&invInst=".$row['cod_inst']."&InvLoc=".$row['tipo_local']."&contato=".$_SESSION['s_usuario']."\"> <img src='".$iconPath."fone.png' alt='Incluir' /> </a></td>";
			
			print "</TR>";

			print "<tr id='idTr".$j."' style='display:none;'><td colspan='8'><div id='idDivLinha".$j."' style='display:none;'></div></td></tr>";
			print "<input type='hidden' name='etiquetaAjax".$j."' id='idEtiqueta".$j."' value='".$row['etiqueta']."'>";
			print "<input type='hidden' name='unidadeAjax".$j."' id='idUnidade".$j."' value='".$row['cod_inst']."'>";
			print "<input type='hidden' name='INDIV' id='idINDIV' value='INDIV'>";

			$i++;
		}
		print "</TABLE>";

		if ($linhas>5) { //Colocar rodapï¿½ se a quantidade de registros for maior do que 20 registros.

			print "</fieldset>";
			print "<table border='0' cellpadding='3' cellspacing='0' summary=''>";
			print "<FORM method='post' action='".$_SERVER['PHP_SELF']."'>";

			print "<TR>";
			$min++;
			if (isset($avancaTodos)) {$top=$linhasTotal;} else $top=$min+($max-1);
			print "<TD width='750' align='left' ><B>".TRANS('FOUND')." <font color='red'>".$linhasTotal."</font> ".TRANS('TXT_REG_ORDER_BY')." <u>".$traduzOrdena."</u>. ".TRANS('TXT_SHOW_OF')." <font color='red'>".$min."</font> ".TRANS('TXT_THE')." <font color='red'>".$top."</font>.</B></TD>";
			print "<TD width='50' align='left' ></td>";

			print "<TD width='30%' align='right'><input  type='submit' class='button_ba2' name='voltaInicio' value=' ' ".
				"title='".TRANS('VIEW_THE')." ".$max." ".TRANS('FIRST_RECORDS')."'> <input  type='submit' class='button_ba1'  name='voltaUm' value=' ' ".
				"title='".TRANS('VIEW_THE')." ".$max." ".TRANS('PREVIOUSLY_RECORDS')."'> <input  type='submit' class='button_go1'  name='avancaUm' value=' ' ".
				"title='".TRANS('VIEW_THE_NEXT')." ".$max." ".TRANS('RECORDS')."'> <input  type='submit' class='button_go2'  name='avancaFim' value=' ' ".
				"title='".TRANS('VIEW_THE_LAST')." ".$max." ".TRANS('RECORDS')."'> <input  type='submit' class='button'  name='avancaTodos' value='Todas' ".
				"title='".TRANS('VIEW_ALL')." ".$linhasTotal." ".TRANS('RECORDS')."'></td>";


			print "</tr>";
			$min--;

			print "<input type='hidden' value='".$min."' name='min'>";
			print "<input type='hidden' value='".$max."' name='max'>";
			print "<input type='hidden' value='".$maxAux."' name='maxAux'>";
			print "<input type='hidden' value='".$base."' name='top'>";
			print "<input type='hidden' value='".$top."' name='top'>";
			print "<input type='hidden' value='".$ordena."' name='ordena'>";
			print "<input type='hidden' value='".$comp_inv."' name='comp_inv'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_sn'])."' name='comp_sn'>";
			if (isset($comp_sn))
				print "<input type='hidden' value='".$comp_sn."' name='comp_sn'>";
			if (isset($_REQUEST['comp_marca']))
				print "<input type='hidden' value='".$_REQUEST['comp_marca']."' name='comp_marca'>";
			if (isset($_REQUEST['comp_mb']))
				print "<input type='hidden' value='".$_REQUEST['comp_mb']."' name='comp_mb'>";
			if (isset($_REQUEST['comp_proc']))
				print "<input type='hidden' value='".$_REQUEST['comp_proc']."' name='comp_proc'>";
			if (isset($_REQUEST['comp_memo']))
				print "<input type='hidden' value='".$_REQUEST['comp_memo']."' name='comp_memo'>";
			if (isset($_REQUEST['comp_video']))
				print "<input type='hidden' value='".$_REQUEST['comp_video']."' name='comp_video'>";
			if (isset($_REQUEST['comp_som']))
				print "<input type='hidden' value='".$_REQUEST['comp_som']."' name='comp_som'>";
			if (isset($_REQUEST['comp_rede']))
				print "<input type='hidden' value='".$_REQUEST['comp_rede']."' name='comp_rede'>";
			if (isset($_REQUEST['comp_modem']))
				print "<input type='hidden' value='".$_REQUEST['comp_modem']."' name='comp_modem'>";
			if (isset($_REQUEST['comp_modelohd']))
				print "<input type='hidden' value='".$_REQUEST['comp_modelohd']."' name='comp_modelohd'>";

			if (isset($_REQUEST['comp_cdrom']))
				print "<input type='hidden' value='".$_REQUEST['comp_cdrom']."' name='comp_cdrom'>";
			if (isset($_REQUEST['comp_dvd']))
				print "<input type='hidden' value='".$_REQUEST['comp_dvd']."' name='comp_dvd'>";
			if (isset($_REQUEST['comp_grav']))
				print "<input type='hidden' value='".$_REQUEST['comp_grav']."' name='comp_grav'>";
			if (isset($_REQUEST['comp_local']))
				print "<input type='hidden' value='".$_REQUEST['comp_local']."' name='comp_local'>";
			if (isset($_REQUEST['comp_nome']))
				print "<input type='hidden' value='".$_REQUEST['comp_nome']."' name='comp_nome'>";
			if (isset($_REQUEST['comp_fornecedor']))
				print "<input type='hidden' value='".$_REQUEST['comp_fornecedor']."' name='comp_fornecedor'>";
			if (isset($_REQUEST['comp_nf']))
				print "<input type='hidden' value='".$_REQUEST['comp_nf']."' name='comp_nf'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_inst'])."' name='comp_inst[]'>";
			if (isset($_REQUEST['comp_inst']))
				print "<input type='hidden' value='".$comp_inst."' name='comp_inst[]'>";
			if (isset($_REQUEST['comp_tipo_equip']))
				print "<input type='hidden' value='".$_REQUEST['comp_tipo_equip']."' name='comp_tipo_equip'>";
			if (isset($_REQUEST['comp_fab']))
				print "<input type='hidden' value='".$_REQUEST['comp_fab']."' name='comp_fab'>";
			if (isset($_REQUEST['comp_tipo_imp']))
				print "<input type='hidden' value='".$_REQUEST['comp_tipo_imp']."' name='comp_tipo_imp'>";
			if (isset($_REQUEST['comp_polegada']))
				print "<input type='hidden' value='".$_REQUEST['comp_polegada']."' name='comp_polegada'>";
			if (isset($_REQUEST['comp_resolucao']))
				print "<input type='hidden' value='".$_REQUEST['comp_resolucao']."' name='comp_resolucao'>";
			if (isset($_REQUEST['comp_ccusto']))
				print "<input type='hidden' value='".$_REQUEST['comp_ccusto']."' name='comp_ccusto'>";
			if (isset($_REQUEST['comp_situac']))
				print "<input type='hidden' value='".$_REQUEST['comp_situac']."' name='comp_situac'>";

			//if (isset($_REQUEST['comp_data']))
			if (isset($comp_data))
				print "<input type='hidden' value='".$comp_data."' name='comp_data'>";
			//if (isset($_REQUEST['comp_data_compra']))
			if (isset($comp_data_compra))
				print "<input type='hidden' value='".$comp_data_compra."' name='comp_data_compra'>";

			//print "<input type='hidden' value='".isset($_REQUEST['comp_data'])."' name='comp_data'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_data_compra'])."' name='comp_data_compra'>";
			if (isset($_REQUEST['garantia']))
				print "<input type='hidden' value='".$_REQUEST['garantia']."' name='garantia'>";
			if (isset($_REQUEST['negado']))
				print "<input type='hidden' value='".$_REQUEST['negado']."' name='negado'>";


			print "</form>";
			print "</table>";

		} else {
			print "<TABLE border='0' cellpadding='1' cellspacing='0' align='center' width='100%' bgcolor='".BODY_COLOR."'>";
			print "<TR><TD bgcolor='".TD_COLOR."'><font color='".TD_COLOR."'>&nbsp</font></TD></TR>";
			print "</table>";
			print "</fieldset>";
		}
	}

	?>
	<SCRIPT LANGUAGE="JAVASCRIPT">
	<!--

		desabilitaLinks(<?php print $_SESSION['s_invmon'];?>);


		function desabilita(v){
			if (document.checagem.negada !=null)
				document.checagem.negada.disabled = v;
		}

		function checar() {
			var checado = false;
			if (document.checagem.encadeia.checked){
				checado = true;
				desabilita(false);
			} else {
				checado = false;
				desabilita(true);
			}
			return checado;
		}

		function ckPopup() {
			var popup = false;
			if (document.checagem.ckpopup.checked){
				popup = true;
			} else {
				popup = false;
			}
			return popup;
		}


		function montaPopup(pagina)	{ //Exibe uma janela popUP

			if (ckPopup()==false){
				window.location.href=pagina;
			} else {
				x = window.open(pagina,'_blank','dependent=yes,width=650,height=470,scrollbars=yes,statusbar=no,resizable=yes');
				//x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
			}
			return false
		}

		function negar() {
			var negado = false;
			if (document.checagem.negada.checked){
				negado = true;
			} else {
				negado = false;
			}
			return negado;
		}

		function monta_link(clicado,parametro,negaCampo){

			var encadeado = "encadeado=1";
			if (checar()==false){
				parametro = "";
				encadeado = "";
				negaCampo ="";
			}

			//FIM DO BLOCO ALTERADO
			window.location.href=clicado+"&"+parametro+"&"+encadeado;
		}

		//-->
		</SCRIPT>
		<?php 
			//else
			//	if (negar()==false){
			//		negaCampo = "";
			//	} else {
			//		negaCampo = "negar="+negaCampo;
			//	}
				//window.location.href=clicado+"&"+negaCampo+"&"+parametro;

print "</body>";
print "</html>";
?>