<?php 
 /*                        Copyright 2005 Flávio Ribeiro

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

is_file( "./includes/config.inc.php" )
	or die( "Você precisa configurar o arquivo config.inc.php em OCOMON/INCLUDES/para iniciar o uso do OCOMON!<br>Leia o arquivo <a href='LEIAME.txt'>LEIAME.TXT</a> para obter as principais informações sobre a instalação do OCOMON!".
		"<br><br>You have to configure the config.inc.php file in OCOMON/INCLUDES/ to start using Ocomon!<br>Read the file <a href='README.txt'>README.TXT</a>to get the main informations about the Ocomon Installation!" );
//print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
//print "<!DOCTYPE html>";  
//print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
//print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
//print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">";

	session_start();
	//session_destroy();
	if (!isset($_SESSION['s_language']))  $_SESSION['s_language']= "pt_BR.php";

	if (!isset($_SESSION['s_usuario']))  $_SESSION['s_usuario']= "";
	if (!isset($_SESSION['s_logado']))  $_SESSION['s_logado']= "";
	if (!isset($_SESSION['s_nivel']))  $_SESSION['s_nivel']= "";
	
	/*
		Tentando arrumar a porcaria do IE
	
	*/
				$useragent = $_SERVER['HTTP_USER_AGENT'];
				 
				  if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
					$browser_version=$matched[1];
					$browser = 'IE';
				  } elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$useragent,$matched)) {
					$browser_version=$matched[1];
					$browser = 'Opera';
				  } elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
					$browser_version=$matched[1];
					$browser = 'Firefox';
				  } elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)) {
					$browser_version=$matched[1];
					$browser = 'Chrome';
				  } elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
					$browser_version=$matched[1];
					$browser = 'Safari';
				  } else {
					// browser not recognized!
					$browser_version = 0;
					$browser= 'other';
				  }
				  
				  if ($browser == 'IE' && $browser_version != '6.0'){
					
					//print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
                                        //print "<!DOCTYPE html>";  
                                        //print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
                                        print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
                                        //print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">";
					//print "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE8\">";
				  } elseif ($browser == 'IE' && $browser_version == '6.0'){
					print "SEU BROWSER NÃO DÁ SUPORTE ÀS TECNOLOGIAS UTILIZADAS POR ESSA FERRAMENTA.";
				  }
				  
				  
	/*
		FIM
	*/
	
	include ("PATHS.php");
	//include ("".$includesPath."var_sessao.php");
	include ("includes/functions/funcoes.inc");
	include ("includes/javascript/funcoes.js");
	include ("includes/queries/queries.php");
	include ("".$includesPath."config.inc.php");
	//require_once ("includes/languages/".LANGUAGE."");
	include ("".$includesPath."versao.php");

	include("includes/classes/conecta.class.php");
	$conec = new conexao;
	$conec->conecta('MYSQL') ;

        
	if (is_file("./".$iconPath."favicon.ico")) {
		print "<link rel='shortcut icon' href='./".$iconPath."favicon.ico'>";
	}

	$qryLang = "SELECT * FROM config";
	$execLang = mysql_query($qryLang);
	$rowLang = mysql_fetch_array($execLang);
	if (!isset($_SESSION['s_language'])) $_SESSION['s_language']= $rowLang['conf_language'];


	$uLogado = $_SESSION['s_usuario'];
	if (empty($uLogado)) {
		$USER_TYPE = TRANS('MNS_OPERADOR');//$TRANS['MNS_OPERADOR'];
		$uLogado = TRANS('MNS_NAO_LOGADO'); //$TRANS['MNS_NAO_LOGADO'];
		$logInfo = "<font class='topo'>".TRANS('MNS_LOGON')."</font>"; //$TRANS['MNS_LOGON']
		$hnt = TRANS('HNT_LOGON');
	} else {
		if ($_SESSION['s_nivel'] < 3) {
			$USER_TYPE = TRANS('MNS_OPERADOR');
		} else
			$USER_TYPE = TRANS('MNS_USUARIO');
		$logInfo = "<font color='red'>".TRANS('MNS_LOGOFF')."</font>";
		$hnt = TRANS('HNT_LOGOFF');
	}
	$marca = "HOME";


print "<html>";
print "<head>";

print "<title>OCOMON ".VERSAO."</title>";
print "<link rel='stylesheet' href='includes/css/estilos.css.php'>"; //type='text/css'
print "<!--[if lte IE 7]>
        <style>
            #menuwrapper, #menubar ul a 
            {
            height: 1%;
            }
            a:active {
            width: auto;
            border-color: #fff;
            }

        </style>
        <![endif]-->
    ";
print "</head>
        <body onLoad=\"setHeight('centro2');\">";

print " <table width='100%' border='0px' id='geral'><tr><td>";

//by LRG
print "<table class='topo' border='0' id='cabecalho'>
	<tr>
		<td width='100%'><img src='MAIN_LOGO.png' align='middle' style=\"vertical-align:middle;\"></td>
	</tr>
	</table>";
  print "<table class='menu'><tr>";
//end
	if (empty($_SESSION['s_permissoes'])&& $_SESSION['s_nivel']!=1){
		print "<td width='5%'></td>";
		print "<td width='7%'></td>";
		print "<td width='7%'></td>";
		print "<td width='5%' ></td>";
		print "<td width='76%'></td>";
		$conec->desconecta('MYSQL');
	} else{

// 		include("includes/classes/conecta.class.php");
// 		$conec = new conexao;
// 		$conec->conecta('MYSQL') ;
           
		$qryconf = $QRY["useropencall"];
		$execconf = mysql_query($qryconf) or die('Não foi possível ler as informações de configuração do sistema!');
		$rowconf = mysql_fetch_array($execconf);

		$qryStyle = "SELECT * FROM temas t, uthemes u  WHERE u.uth_uid = ".$_SESSION['s_uid']." and t.tm_id = u.uth_thid";
		$execStyle = mysql_query($qryStyle) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE ESTILOS!<BR>'.$qryStyle);
		$rowStyle = mysql_fetch_array($execStyle);
		$regs = mysql_num_rows($execStyle);
		if ($regs==0){ //SE NÃO ENCONTROU TEMA ESPECÍFICO PARA O USUÁRIO
			unset ($rowStyle);
			$qryStyle = "SELECT * FROM styles";
			$execStyle = mysql_query($qryStyle);
			$rowStyle = mysql_fetch_array($execStyle);
		}

	//LRg
        //print "<td id='button_hid' width='5%'><input type='button' name='botao' onclick='mostra_menu()'><td>";
	//print "<td id='HOME' width='5%' class='barra'><a onMouseOver=\"destaca('HOME')\" onMouseOut=\"libera('HOME')\" onclick=\"loadIframe('menu.php?sis=h','menu','home.php', 'centro',3,'HOME')\" >".TRANS('MNS_HOME')."</a></td>";




		$sis="";
		$sisPath="";
		$sistem="home.php";
		$marca = "HOME";
		
		//MENU OPERADOR 
		if (($_SESSION['s_ocomon']==1) && !isIn($_SESSION['s_area'],$rowconf['conf_ownarea_2'])) {
			//print "<td id='OCOMON' width='7%'  class='barra'><a  onMouseOver=\"destaca('OCOMON')\" onMouseOut=\"libera('OCOMON')\" onclick=\"loadIframe('menu.php?sis=o','menu','".$ocoDirPath."abertura.php','centro',2,'OCOMON')\">".TRANS('MNS_OCORRENCIAS')."</a></td>";

                    //TESTE DE NOVO MENU - INICIO
                    //TESTE DE NOVO MENU - INICIO
           print "<td>";        
           print "<div id='menuwrapper'>
                    <ul id='menubar'>
                    <li class='barra_li'><a class='trigger' href='#'><img src='".$iconPath."gohome.png' />  ".TRANS('MNS_HOME')."</a>
                        <ul>
                            <li><a target='centro' href='home.php'><img src='".$iconPath."gohome.png'/>  ".TRANS('MNL_INICIO')."</a></li>
                            <li><a target='centro' href='".$ocoDirPath."abertura_user.php?action=listall'><img src='".$iconPath."search.png'/>  ".TRANS('MNL_MEUS')."</a></li>
                            
                        </ul>
                    </li>";

                    print "
                    <li class='barra_li'><a class='trigger' href='#'><img src='".$iconPath."fone.png' />  ".TRANS('MNS_OCORRENCIAS')."</a>
                         <ul>
                            <li><a target='centro' href='".$ocoDirPath."abertura.php'><img src='".$iconPath."gohome.png'/>  ".TRANS('MNL_INICIO')."</a></li>
                            <li><a target='centro' href='".$ocoDirPath."incluir.php'><img src='".$iconPath."fone.png'/>  ".TRANS('MNL_ABRIR')."</a></li>
                            <li><a target='centro' href='".$ocoDirPath."consultar.php'><img src='".$iconPath."consulta.png'/>  ".TRANS('MNL_CONSULTAR')."</a></li>
                            <li><a target='centro' href='".$ocoDirPath."alterar.php'><img src='".$iconPath."search.png'/>  ".TRANS('MNL_BUSCA_RAP')."</a></li>
                            <li><a target='centro' href='".$ocoDirPath."consulta_solucoes.php'><img src='".$iconPath."solucoes2.png'/>  ".TRANS('MNL_SOLUCOES')."</a></li>
                            <li><a target='centro' href='".$ocoDirPath."emprestimos.php'><img src='".$iconPath."emprestimos.png'/>  ".TRANS('MNL_EMPRESTIMOS')."</a></li>
                            <li><a target='centro' href='".$ocoDirPath."avisos.php'><img src='".$iconPath."mural.png'/>  ".TRANS('MNL_MURAL')."</a></li>
                                <li><a class='trigger_r' href='#'><img src='".$iconPath."tree_folder_open.png'/>  ".TRANS('MNL_RELATORIOS')."</a>
                                    <ul>
                                        <li><a target='centro' href='".$ocoDirPath."relatorio_slas_2.php'><img src='".$iconPath."fone.png'/> ".TRANS('MNL_SLA')."</a></li>
                                        <li><a target='centro' href='".$ocoDirPath."relatorios.php'><img src='".$iconPath."spread.png'/> ".TRANS('MNL_DIVERSOS')."</a></li>
                                    </ul>
                                </li>
                         </ul>
                    </li>"; 
                    
                    //// FIM

			if ($sis=="") $sis="sis=o";
			$sisPath = $ocoDirPath;
			$sistem = "abertura.php";
			$marca = "OCOMON";
		
                //USUARIO SIMPLES
		} else 	
		
		if (($_SESSION['s_ocomon']==1) && isIn($_SESSION['s_area'], $rowconf['conf_ownarea_2'])) {

                    //TESTE DE NOVO MENU - INICIO
                    print "
                    <div id='menuwrapper'>
                        <ul id='menubar'>
                            <li class='barra_li'><a target='centro' href='".$ocoDirPath."incluir.php'><img src='".$iconPath."fone.png'/>  ".TRANS('MNL_ABRIR')."</a></li>
                            <li class='barra_li'><a target='centro' href='".$ocoDirPath."abertura_user.php?action=listall'><img src='".$iconPath."consulta.png'/>  ".TRANS('MNL_MEUS')."</a></li>
                            <li class='barra_li'><a target='centro' href='".$invDirPath."altera_senha.php'><img src='".$iconPath."password.png'/>  ".TRANS('MNL_SENHA')."</a></li>
                        </ul>
                    
                    ";
                    
                    /*print "
                    <br class='clearit'>
                    </div></td>";
                           */
                    // FIM
			$sis="sis=s";
			$sisPath = $ocoDirPath;
			$sistem = "abertura_user.php?action=listall";
			$marca = "OCOMON";
		} else {
			print "<td width='7%' STYLE='{border-right: thin solid #C7C8C6; color:#C7C8C6}'><img src='".$iconPath."fone.png'/>  ".TRANS('MNS_OCORRENCIAS')."</td>";
                //FIM
                }
		if ($_SESSION['s_invmon']==1){
		
                    print "
                    <li class='barra_li'><a class='trigger' href='#'><img src='".$iconPath."computador.png'/>  ".TRANS('MNS_INVENTARIO')." </a>
                            <ul>
                            <li><a target='centro' title='Tela inicial do sistema' href='".$invDirPath."abertura.php'><img src='".$iconPath."gohome.png'/>  ".TRANS('MNL_INICIO')."</a></li>
                                <li><a class='trigger_r' href='#'><img src='".$iconPath."tree_folder_open.png'/>  ".TRANS('MNL_CAD')."</a>
                                    <ul>
                                        <li><a target='centro' href='".$invDirPath."incluir_computador.php'><img src='".$iconPath."computador.png'/>  ".TRANS('MNL_CAD_EQUIP')."</a></li>
                                        <li><a target='centro' href='".$invDirPath."documentos.php?action=incluir&cellStyle=true'><img src='".$iconPath."contents.png'/>  ".TRANS('MNL_CAD_DOC')."</a></li>
                                        <li><a target='centro' href='".$invDirPath."estoque.php?action=incluir&cellStyle=true'><img src='".$iconPath."mouse.png'/>  ".TRANS('MNL_CAD_ESTOQUE')."</a></li>
                                    </ul>
                                </li>
                                <li><a class='trigger_r' href='#'><img src='".$iconPath."tree_folder_open.png'/>  ".TRANS('MNL_VIS')."</a>
                                    <ul>
                                        <li><a target='centro' href='".$invDirPath."mostra_consulta_comp.php'><img src='".$iconPath."computador.png'/>  ".TRANS('MNL_VIS_EQUIP')."</a></li>
                                        <li><a target='centro' href='".$invDirPath."documentos.php'><img src='".$iconPath."contents.png'/>  ".TRANS('MNL_VIS_DOC')."</a></li>
                                        <li><a target='centro' href='".$invDirPath."estoque.php'><img src='".$iconPath."mouse.png'/>  ".TRANS('MNL_VIS_ESTOQUE')."</a></li>
                                    </ul>
                                </li>
                                <li><a class='trigger_r' href='#'><img src='".$iconPath."tree_folder_open.png'/>  ".TRANS('MNL_CON')."</a>
                                    <ul>
                                        <li><a target='centro' href='".$invDirPath."consulta_inv.php'><img src='".$iconPath."search.png'/>  ".TRANS('MNL_CON_RAP')."</a></li>
                                        <li><a target='centro' href='".$invDirPath."consulta_comp.php'><img src='".$iconPath."consulta.png'/>  ".TRANS('MNL_CON_ESP')."</a></li>
                                        <li><a target='centro' href='".$invDirPath."estoque.php?action=search&cellStyle=true'><img src='".$iconPath."mouse.png'/>  ".TRANS('MNL_VIS_ESTOQUE')."</a></li>
                                            <li><a class='trigger_r' href='#'><img src='".$iconPath."tree_folder_open.png'/>  ".TRANS('MNL_CON_HIST')."</a>
                                                <ul>
                                                    <li><a target='centro' href='".$invDirPath."consulta_hist_inv.php?from_menu=1'><img src='".$iconPath."tag.png'/>  ".TRANS('MNL_CON_HIST_TAG')."</a></li>
                                                    <li><a target='centro' href='".$invDirPath."consulta_hist_local.php'><img src='".$iconPath."tag.png'/>  ".TRANS('MNL_CON_HIST_LOCAL')."</a></li>
                                                </ul>
                                            </li>
                                    </ul>
                                </li>
                                <li><a class='trigger_r' href='#'><img src='".$iconPath."tree_folder_open.png'/>  ".TRANS('MNL_PREVENTIVA')."</a>
                                    <ul>
                                        <li><a target='centro' href='".$invDirPath."prev_com_ch.php'><img src='".$iconPath."search.png'/>  ".TRANS('MNL_PREVENTIVA_COM')."</a></li>
                                        <li><a target='centro' href='".$invDirPath."prev_sem_ch.php'><img src='".$iconPath."search.png'/>  ".TRANS('MNL_PREVENTIVA_SEM')."</a></li>
                                    </ul>
                                </li>
                            <li><a target='centro' href='".$invDirPath."relatorios.php'><img src='".$iconPath."spread.png'/> ".TRANS('MNL_STAT_RELAT')."</a></li>
                            </ul>
                   </li>"; 
               //// FIM    
                                                  
			//print "<td id='INVMON' width='7%'  class='barra'><a onMouseOver=\"destaca('INVMON')\" onMouseOut=\"libera('INVMON')\" onclick=\"loadIframe('menu.php?sis=i','menu','".$invDirPath."abertura.php','centro',2,'INVMON')\">".TRANS('MNS_INVENTARIO')."</a></td>"; //abertura.php   -   ".$invDirPath."".$invHome."
			if ($sis=="") $sis="sis=i";
			if ($sisPath=="") $sisPath=$invDirPath;
			$sistem = "abertura.php";
			if ($marca=="") $marca = "INVMON";
			//$home = "home=true";
		} else {
			/*print "
                            <br class='clearit'>
                            </div></td>";
                           */
                }
		if ($_SESSION['s_nivel']==1 || (isset($_SESSION['s_area_admin']) && $_SESSION['s_area_admin'] == '1')) {
			
                    //TESTE DE NOVO MENU - INICIO
                    print "
                    <li class='barra_li'><a class='trigger' href='#'><img src='".$iconPath."kcontrol.png'/>  ".TRANS('MNS_ADMIN')."</a>
                        <ul>
                        <li><a class='trigger_r' href='#'><img src='".$iconPath."tree_folder_open.png'/>  ".TRANS('MNL_CONF')."</a>
                            <ul>
                                <li><a target='centro' href='".$admDirPath."configGeral.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_CONF_GERAL')."</a></li>
                                <li><a target='centro' href='".$admDirPath."configuserscreen.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_CONF_ABERTURA')."</a></li>
                                <li><a target='centro' href='".$admDirPath."screenprofiles.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_SCREEN_PROFILE')."</a></li>
                                <li><a target='centro' href='".$admDirPath."configmail.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_CONF_SMTP')."</a></li>
                                <li><a target='centro' href='".$admDirPath."configmsgs.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_CONF_MSG')."</a></li>
                                <li><a target='centro' href='".$admDirPath."aparencia.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_CONF_APARENCIA')."</a></li>
                            </ul>
                       </li>
                       <li><a class='trigger_r' href='#'><img src='".$iconPath."tree_folder_open.png'/>  ".TRANS('MNS_OCORRENCIAS')."</a>
                           <ul>
                               <li><a target='centro' href='".$admDirPath."sistemas.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_AREAS')."</a></li>
                               <li><a target='centro' href='".$admDirPath."sistemas_conf_abertura.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_AREAS')."-".TRANS('TTL_CONFIG')."</a></li>
                               <li><a target='centro' href='".$admDirPath."problemas.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_PROBLEMAS')."</a></li>
                               <li><a target='centro' href='".$admDirPath."status.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_STATUS')."</a></li>
                               <li><a target='centro' href='".$admDirPath."prioridades.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_PRIORIDADES')."</a></li>
                               <li><a target='centro' href='".$admDirPath."prioridades_atendimento.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_PRIORIDADES_ATEND')."</a></li>
                               <li><a target='centro' href='".$admDirPath."feriados.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_FERIADOS')."</a></li>
                               <li><a target='centro' href='".$admDirPath."tipo_solucoes.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_SOLUCOES')."</a></li>
                               <li><a target='centro' href='".$admDirPath."scripts.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_SCRIPTS')."</a></li>
                               <li><a target='centro' href='".$ocoDirPath."ocorrencias.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNS_OCORRENCIAS')."</a></li>
                               <li><a target='centro' href='".$admDirPath."mail_templates.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_MAIL_TEMPLATES')."</a></li>
                               <li><a target='centro' href='".$admDirPath."mail_distribution_lists.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_DIST_LISTS')."</a></li>
                           </ul>
                      </li>
                      <li><a class='trigger_r' href='#'><img src='".$iconPath."tree_folder_open.png'/>  ".TRANS('MNS_INVENTARIO')."</a>
                          <ul>
                              <li><a target='centro' href='".$admDirPath."tipo_equipamentos.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_EQUIPAMENTOS')."</a></li>
                              <li><a target='centro' href='".$admDirPath."tipo_componentes.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_COMPONENTES')."</a></li>
                              <li><a target='centro' href='".$invDirPath."fabricantes.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_FABRICANTES')."</a></li>
                              <li><a target='centro' href='".$invDirPath."fornecedores.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_FORNECEDORES')."</a></li>
                              <li><a target='centro' href='".$admDirPath."situacoes.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_SITUACOES')."</a></li>
                              <li><a target='centro' href='".$invDirPath."tempo_garantia.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_GARANTIA')."</a></li>
                              <li><a target='centro' href='".$invDirPath."softwares.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_SW')."</a></li>
                              <li><a target='centro' href='".$admDirPath."preventiva.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_PREVENTIVA')."</a></li>
                              <li><a target='centro' href='".$admDirPath."maq_importadas.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_CONF_MAQIMPORT')."</a></li>
                         </ul>	
                     </li>
                            <li><a target='centro' href='".$admDirPath."usuarios.php'><img src='".$iconPath."personal.png'/>  ".TRANS('MNL_USUARIOS')."</a></li>
                            <li><a target='centro' href='".$admDirPath."locais.php'><img src='".$iconPath."browser.png'/>  ".TRANS('MNL_LOCAIS')."</a></li>
                            <li><a target='centro' href='".$admDirPath."unidades.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_UNIDADES')."</a></li>
                            <li><a target='centro' href='".$admDirPath."ccustos.php'><img src='".$iconPath."tree_leaf.png'/>  ".TRANS('MNL_CC')."</a></li>
                            <li><a target='centro' href='".$admDirPath."permissoes.php'><img src='".$iconPath."password.png'/>  ".TRANS('MNL_PERMISSOES')."</a></li>
                         </ul>
                     </li>
                   </ul>
                </ul>

                   ";
                  //// FIM

			
			//print "<td id='ADMIN' width='5%'  class='barra'><a onMouseOver=\"destaca('ADMIN')\" onMouseOut=\"libera('ADMIN')\" onclick=\"loadIframe('menu.php?sis=a','menu','','','2','ADMIN')\">".TRANS('MNS_ADMIN')."</a></td>";
			if ($sis=="") $sis="sis=a";
			if ($sisPath=="") $sisPath="";
			if ($sistem=="") $sistem = "menu.php";
			if ($marca=="")$marca = "ADMIN";
			$home = "home=true";
		} else {
			/*print "
                            <br class='clearit'>
                            </div></td>";
                           */
						   print "</ul></ul>";
                }

                //FIM
		//print "<td width='72%'></td>";
                    print "<ul id='menubar2'>
                            <li><a class='trigger'  href='#'><img src='".$iconPath."personal.png' /> ".TRANS('usuario').": <b>".$uLogado."</b></a>
                                <ul>
                                    <li><a target='centro' href='".$ocoDirPath."user_theme.php'><img src='".$iconPath."colors.png'/>  ".TRANS('MNL_THEME')."</a></li>
                                    <li><a target='centro' href='".$invDirPath."altera_senha.php'><img src='".$iconPath."password.png'/>  ".TRANS('MNL_SENHA')."</a></li>
                                    <li><a target='centro' href='".$ocoDirPath."user_lang.php'><img src='".$iconPath."brasil-flag-icon.png'/>  ".TRANS('MNL_LANG')."</a></li>
                                    <li><a href='".$commonPath."logout.php'><img src='".$iconPath."exit.png'/> Sair </a></li>
                                </ul>
                            </li>
                        </ul>
                                            
                  <br class='clearit'>
                  </div></td>";
		$conec->desconecta('MYSQL');
	}
	print "</tr></table>";

print "</td></tr>";



if ($_SESSION['s_logado']){

	//BLOCO PARA RECARREGAR A PÁGINA NO MÓDULO ADMIN QUANDO FOR SELECIONADO NOVO TEMA
 	if (isset($_GET['LOAD']) && $_GET['LOAD'] == 'ADMIN'){
 		$PARAM = "&LOAD=ADMIN";
 		$marca = "ADMIN";
 	}else
 		$PARAM = "";

	print "<tr>";
        
	print "<td style=\"width:100%;\" id='centro2'><iframe class='frm_centro' src='".$sisPath."".$sistem."'  name='centro' align='center' width='100%' height='100%' frameborder='0' STYLE='{border-bottom: thin solid #999999;}'></iframe></td>";
	
        print "</tr>";
        
} else {
	
	//Formulï¿½rio de Login
                print "<tr><td >";
		//print "<form name='logar' method='post' action='".$commonPath."login.php?=".session_id()."' onSubmit=\"return valida()\">";
		print "<form name='logar' method='post' action='".$commonPath."login.php?".session_id()."' onSubmit=\"return valida()\">";
		
                print " 
                <div class='div_login'>";
                    

                        if (isset($_GET['inv']) ) {
                                if ($_GET['inv']=="1") {
										
                                        print "<div style='position: absolute; width:203px; height:63px; background-image:url(includes/imgs/err_bg.png);background-repeat: no-repeat; padding:15px;margin-top:50px;margin-left:320px;'>
                                                <font color='white'>".TRANS('ERR_LOGON')."! <br> AUTH_TYPE: ".AUTH_TYPE."</font>
                                              </div>";
                                                
                                }
                        }

                        if (isset($_GET['usu']) ) {
                                $typedUser = $_GET['usu'];
                                
                        } else {
                                $typedUser = "";
                        }
                        
                        print "<div style='margin:0 auto; width:180px; padding:20px;'>
                                    <div style='height:10px;font-size:16px;font-weight:bold;color:#ccc;margin-top:5px;'></div>
                                    <div style='height:120px;'>";
                        print "     <label  id='label_user' class='input_login' for='idLogin' style='display:block;'>".TRANS('MNS_USUARIO')."</label><input class='input_l2' type='text' name='login' value='".$typedUser."' id='idLogin' tabindex='1' \">".
                                "   <br><br>".
                              "     <label  id='label_pass' class='input_login' for='idSenha' style='display:block;'>".TRANS('MNS_SENHA')."</label><input class='input_l2' type='password'  name='password'  id='idSenha' tabindex='2' \">
                                    </div>"; 

                                print " <div style='height:50px;'>
                                        <center><br><input type='submit' class='blogin' value='".TRANS('cx_login')."' tabindex='3'></center>
                                        </div>";
                                
                        print "
                             </div>
                             <center><br>".TRANS('MNS_MSG_CAD_ABERTURA_1')."<a href='' onClick=\"mini_popup('./".$ocoDirPath."newUser.php')\"><b><u><font color='#5e515b'>".TRANS('MNS_MSG_CAD_ABERTURA_2')."!</font></u></b></a></center>";
                    
                print "</div>";
                print "</form>";    
                
                print "</td></tr>";
		
}

        print "<tr><td>";
        print "<div style='position:fixed;width:100%;bottom:0px;height:25px;background-color:#FFFFFF;'>
            <center style='margin:8px;'>".TRANS('MNS_MSG_VERSAO').": <a href='docs/MODIFICACAO.TXT'>".VERSAO."</a> - ".TRANS('MNS_MSG_LIC')." <a href='http://www.gnu.org/licenses/gpl.html'>GPL</a></center>
                </div>";
        print "</td></tr>";
        
print "</table>";
print "</body></html>";
?>
<script type="text/javascript">
<!--
var GLArray = new Array();
	function loadIframe(url1,iframeName1, url2,iframeName2,ACCESS,ID) {

		var nivel_user = '<?php print $_SESSION['s_nivel'];?>';
		var HOM = document.getElementById('HOME');
		var OCO = document.getElementById('OCOMON');
		var INV = document.getElementById('INVMON');
		var ADM = document.getElementById('ADMIN');

		if (nivel_user <= ACCESS) {

			marca(ID);
			if (HOM != null)
				if (ID != "HOME") {
					HOM.style.background ="";
					HOM.style.color ="";
				}
			if (OCO != null)
				if (ID != "OCOMON") {
					OCO.style.background ="";
					OCO.style.color ="";
				}
			if (INV != null)
				if (ID != "INVMON") {
					INV.style.background ="";
					INV.style.color ="";
				}
			if (ADM != null)
				if (ID != "ADMIN") {
					ADM.style.background ="";
					ADM.style.color ="";
				}

			if (iframeName2!=""){
				if ((window.frames[iframeName1]) && (window.frames[iframeName2])) {
					window.frames[iframeName1].location = url1;
					//window.frames[iframeName2].location = url2;
					return false;
				}
			} else
			if (window.frames[iframeName1]) {
				window.frames[iframeName1].location = url1;
				return false;
			}

			else return true;
		} else {
			window.alert('Acesso indisponível!');
			return true;
		}
	}

	function popup(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'Ocomon','width=800,height=600,scrollbars=yes,statusbar=no,resizable=no');
		//x.moveTo(10,10);
		return false
	}

	function showPopup(id){
		var obj = document.getElementById(id);
		if (obj.value==2) {
			return popup('sobre.php');
		} 
		
		if (obj.value==4) {
			return popup('http://sourceforge.net/apps/mediawiki/ocomonphp/index.php?title=Manual');
		} 
		return false;
	}

	function setHeight(id){

		var obj = document.getElementById(id);
		if (obj != null) {
			obj.style.height = screen.availHeight - 220 + 'px';
			marca('<?php print $marca;?>');
		} else {
			document.logar.login.focus();
		}
		return true;
	}


	function mini_popup(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'_blank','dependent=yes,width=400,height=260,scrollbars=yes,statusbar=no,resizable=yes');
		//x.moveTo(window.parent.screenX+50, window.parent.screenY+50);

		return false
	}

	function destaca(id){
			var obj = document.getElementById(id);
			var valor = '<?php isset($rowStyle['tm_barra_fundo_destaque'])? print $rowStyle['tm_barra_fundo_destaque']: print ""?>';
			if (valor!=''){
				if (obj!=null) {
					obj.style.background = valor;
				}
			}
	}

	function libera(id){
		if ( verificaArray('', id) == false ) {
			var obj = document.getElementById(id);
			if (obj!=null) {
				obj.style.background = ''; //#675E66
				//obj.className = "released";
			}
		}
	}

	function marca(id){
		var obj = document.getElementById(id);
		verificaArray('guarda', id);

		var valor = '<?php isset($rowStyle['tm_barra_fundo_destaque'])? print $rowStyle['tm_barra_fundo_destaque']: print ""?>';
		var valor2 = '<?php isset ($rowStyle['tm_barra_fonte_destaque'])? print $rowStyle['tm_barra_fonte_destaque']: print ""?>';
		if (valor != '' && valor2 != '') {
			if (obj!=null) {
				obj.style.background = valor;  //'#666666'
				obj.style.color = valor2;
				//obj.className = "marked";
			}
		}
		verificaArray('libera',id);
	}

	function verificaArray(acao, id) {
		var i;
		var tamArray = GLArray.length;
		var existe = false;

		for(i=0; i<tamArray; i++) {
			if ( GLArray[i] == id ) {
				existe = true;
				break;
			}
		}

		if ( (acao == 'guarda') && (existe==false) ) {  //
			GLArray[tamArray] = id;
		} else if ( (acao == 'libera') ) {
			//-----------------------------
			//-----------------------------
			var temp = new Array(tamArray-1); //-1
			var pos = 0;
			for(i=0; i<tamArray; i++) {
				if ( GLArray[i] == id ) {
					temp[pos] = GLArray[i];
					pos++;
				}
			}

			GLArray = new Array();
			var pos = temp.length;
			for(i=0; i<pos; i++) {
				GLArray[i] = temp[i];
			}
		}

		return existe;
	}

	function valida(){

		var ok = validaForm('idLogin','ALFAFULL','Usuário',1)
		if (ok) var ok = validaForm('idSenha','ALFAFULL','Senha',1);

		return ok;
	}
	
	function mostra_menu() {
		var obj = document.getElementById('menu_centro'); 
	if (obj.height == 0 || obj.width == 0) {obj.height = '100%'; obj.width = '100%';}
	else { obj.height = 0; obj.width = 0;}
		var obj1 = document.getElementById('centro'); 
	if (obj1.width == 0) {obj1.width = '15%';}
	else {obj1.width = 0;}
	}


-->
</script>
<!--
var obj = document.getElementById('tabela_ficha');
           var objOpcoes = document.getElementById('opcoesSel');
                     var valor = objOpcoes.style.height;
           valor = valor.replace('px', '');
           obj.style.height = screen.availHeight - valor - 300;
                     var form = document.forms[0];
           form.acao.value = 'EXIBE_FICHA';
           form.target = 'ficha';
-->
