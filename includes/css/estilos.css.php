<?php session_start();
header('Content-type: text/css');
/*ARQUIVO DE ESTILOS DO OCOMON*/
        
	require_once ('../../includes/config.inc.php');

	if (is_file("../../includes/classes/conecta.class.php"))
		require_once ("../../includes/classes/conecta.class.php"); else
		require_once ("../classes/conecta.class.php");

	$conec = new conexao;
	$conec->conecta('MYSQL');

	//$qry = "SELECT * FROM styles";
	//$exec = mysql_query($qry);
	//$row = mysql_fetch_array($exec);

	if (isset($_SESSION['s_uid'])) {
	//if (isset($_COOKIE['cook_oco_uid'])) {

		$qry = "SELECT * FROM temas t, uthemes u  WHERE u.uth_uid = ".$_SESSION['s_uid']." and t.tm_id = u.uth_thid";
		$exec = mysql_query($qry) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DO TEMA!<BR>'.$qry);
		$row = mysql_fetch_array($exec);
		$regs = mysql_num_rows($exec);
		if ($regs==0){ //SE NÃO ENCONTROU TEMA ESPECÍFICO PARA O USUÁRIO
			$qry = "SELECT * FROM styles";
			$exec = mysql_query($qry);
			$row = mysql_fetch_array($exec);
		}
	} else {
		$qry = "SELECT * FROM styles";
		$exec = mysql_query($qry);
		$row = mysql_fetch_array($exec);
	}


print "
	.{
	font-family: Arial, Helvetica, Sans-Serif;
}
";

print "body
{
	font-family: Arial, Helvetica, Sans-Serif;
	color:black;
	font-size:11px;
	background-color:".$row['tm_color_body'].";
} ";/*#cde5ff   background-color:#d9d8da;     5E515B    font-size: 11px; */

//MENU LATERAL DO OCOMON
print "body.menu ".
"{";
	if ($row['tm_color_menu'] == "IMG_DEFAULT") {
		print "background-image:url('../../bg_menu_15.jpg');";
	//	print "background-repeat: no-repeat ;";
		print "background-repeat: repeat ;";
	} else {
		print "background-color:".$row['tm_color_menu'].";";
		//print "background-repeat: repeat ;";
	}
print "}";

//print ".frm_menu {background-color:#F6F6F6;}";
//print ".frm_centro {background-color:#F6F6F6;}"; /* background-color:#d9d8da; */
print ".frm_menu {background-color:".$row['tm_color_body'].";}";
print ".frm_centro {background-color:".$row['tm_color_body'].";}";

/************************************************************/
/*ESTILOS PARA TABELAS*/

print "table {line-height:1.0em; font-size: 11px;}";

print "table.topo
{
	width:100%;
	line-height:1.1em;
	font-family: Arial,Sans-Serif;
	font-size: 12px;
	font-weight: bold;
	text-align:left; ";
	if ($row['tm_color_topo'] == "IMG_DEFAULT") {
		print "background-image:url('./main_bar.png');";
		print "background-repeat: repeat ;";
	} elseif ($row['tm_color_topo'] == "IMG_DEFAULT_2") {
		print "background-image:url('./main_bar-2.png');";
		print "background-repeat: repeat ;";
	} else {
		print "background-color:".$row['tm_color_topo'].";";
		//print "background-repeat: repeat ;";
	}
	print "color:".$row['tm_color_topo_font'].";
}"; /*92959c*/

print "font.topo {color:".$row['tm_color_topo_font'].";}";

print "#geral{position:relative; top:-10px; }";


/*color:#675E66;   #857B84*/
print "table.barra
{
	width:100%;
	line-height:1.1em;
	font-family: Arial, Helvetica, Sans-serif;
	font-size: 12px;
	font-weight:bold;
	color: ".$row['tm_color_barra_font'].";
	text-align:center; ";
	if ($row['tm_color_barra'] == "IMG_DEFAULT") {
		print "background-image:url('./aqua.png');";
		print "background-repeat: repeat ;";
	} else {
		print "background-color:".$row['tm_color_barra'].";";
		//print "background-repeat: repeat ;";
	}
	print "padding:1px;
	border-spacing:0px;
	border-top-width:1px;
	border-top-color:white;
	border-right-width:1px;
	border-right-color:white;
	border-bottom-width:1px;
	border-bottom-color:white;
	border-left-width:1px;
 	border-left-color:white;
 }";//#675E66

print "table.menutop
{
	background-color:#C7C8C6;
	color:#5E515B;
	padding:1px;
	border-spacing:0px;
	border-top-width:1px;
	border-top-color:white;
	border-right-width:1px;
	border-right-color:white;
	border-bottom-width:1px;
	border-bottom-color:white;
	border-left-width:1px;
 	border-left-color:white;
 }";

 print "table.menu{background-color:#C7C8C6; border:1px; border-collapse:collapse;}";

 print "table.titulo {line-height:1.2em; font-family: Arial,Sans-Serif; font-size: 15px; font-weight: bold;}";

 print "table.header_centro{border-bottom:  solid ".$row['tm_color_borda_header_centro']."; }";



print "table.header
{
	width:100%;
	margin-left:auto;
	margin-right: auto;
	text-align:left;
	border: 1px;
	border-spacing:1;
	background-color:black;
	padding-top:0px
}";

print "table.menu
{
	width:100%;
	margin-left: 0px;
	margin-right: 0px;
	text-align:left;
	border: 0px;
	border-spacing:0px;
	border-collapse:collapse;
	background-color:white;

}";

print "table.corpo
{
	width:100%;
	margin-left:auto;
	margin-right: auto;
	text-align:left;
	border: 0px;
	border-spacing:1;
	padding-top:10px;
}";

print "table.corpo2
{
	width:100%;
	margin-left:auto;
	margin-right: auto;
	text-align:left;
	border: 0px;
	border-spacing:0px;
	border-collapse:collapse;
	padding-top:10px;
}";

print "table.estat60
{
	width:60%;
	margin-left:auto;
	margin-right: auto;
	text-align:left;
	border: 0px;
	border-spacing:1;
	padding-top:20px;
}";

print "table.estat80
{
	width:80%;
	margin-left:auto;
	margin-right: auto;
	text-align:left;
	border: 0px;
	border-spacing:1;
	padding-top:10px;
}";
/*FIM TABELAS*/
/************************************************************/
/*LINHAS E COLUNAS*/

print "td.barra {padding:5px;} ";

print "td.default {padding:3px;} ";

print "td.wide {padding:8px;} ";

print "td.barraMenu {border-right: thin solid ".$row['tm_color_barra_font'].";}"; //{border-right: thin solid #675E66;}

print "td.marked {color:blue; background-color: #666666}";

//print "td.released {color:#675E66; background-color: '';}";

print "tr.menutop {background-color:#C7C8C6; color:#5E515B;}";

if ($row['tm_tr_header'] == "IMG_DEFAULT") {
	print "tr.header, input.header {background-image:url('./header_bar3.png'); background-repeat: repeat ;font-weight:bold; color:".$row['tm_color_font_tr_header'].";}";
	print ".msg {background-image:url('./header_bar3.png'); background-repeat: repeat; ".
				"font-weight:bold; color:".$row['tm_color_font_tr_header']."; padding:5px; ".
				//border-bottom:  solid #999999; ".
				//"border-top:  thin solid #999999; border-left:thin solid #999999; border-right: thin solid #999999; ".
                    "padding: 5px;
                    border-radius: 5px;
                    -moz-border-radius: 5px;
                    -webkit-border-radius: 5px;".
                
                "}";

} else {
		print "tr.header {background-color:".$row['tm_tr_header']."; font-weight:bold; color:".$row['tm_color_font_tr_header'].";}";
		print ".msg {background-color:".$row['tm_tr_header']."; color:".$row['tm_color_font_tr_header']."; padding:5px; ".
					//"border-bottom:  solid #999999; ".
					//"border-top:  thin solid #999999; border-left:thin solid #999999; border-right: thin solid #999999; ".
		"}"; //tm_tr_header
	}

print "tr.padrao {background-color:#ECECDB;}";

print "tr.lin_impar {background-color:".$row['tm_color_lin_impar'].";  padding: 5px;}"; /*  F8F8F1  #E5E5E5     #EAEAEA*/

/*tr.lin_par {background-image:url("./header_bar.gif"); background-repeat: repeat ; padding:5px; } /*#D3D3D3*/
//print "tr.lin_par {background-color:#E3E1E1; background-repeat: repeat ; padding:5px; }"; /*#D3D3D3*/
print "tr.lin_par {background-color:".$row['tm_color_lin_par'].";  padding:5px; }"; /*#D3D3D3*/


print "linha_1 {background-color:".$row['tm_color_lin_impar'].";  padding: 5px;}";
print "linha_2 {background-color:".$row['tm_color_lin_par'].";  padding:5px; }";

print "tr.lin_alerta {background-color:#FF0000; color:yellow;}";

print "tr.lin_alerta_par {background-color:".$row['tm_color_lin_par']."; color:#FF0000; font-style:italic; padding:5px;}";
print "tr.lin_alerta_impar {background-color:".$row['tm_color_lin_impar']."; color:#FF0000; font-style:italic; padding:5px;}";

print "td.cborda {height: 20px; }"; /*border: 1px solid #a4a4a4;*/

print "td.line {border-bottom: solid  ".$row['tm_borda_color']."; border-bottom-width:".$row['tm_borda_width']."px;  }"; //border-top:  thin solid ".$row['conf_color_body'].";


/*FIM LINHAS E COLUNAS*/
/************************************************************/
/*LINKS*/
print "a:link {color: #5E515B; text-decoration: none; cursor:pointer;}";
print "a:visited {color: #5E515B; text-decoration: none; cursor:pointer;}";
print "a:hover {color: #5E515B; cursor:pointer;}"; /*  ffe4ca*/ #5E515B
print "a:active {color: #8a4500; cursor:pointer;}";

print ".href {color: #5E515B; text-decoration: none; cursor:pointer;}";

print ".negrito:hover{color:#ffe4ca; background-color:#ffe4ca; font-weight:bold; }";

print "a.barra:link {color: ".$row['tm_color_barra_hover']."; text-decoration: none; cursor:pointer;}";
print "a.barra:visited {color: ".$row['tm_color_barra_hover']."; text-decoration: none; cursor:pointer;}";
print "a.barra:hover {color: ".$row['tm_color_barra_hover'].";  text-decoration: none; cursor:pointer;}";
print "a.barra:active {color: ".$row['tm_color_barra_hover']."; text-decoration: none; cursor:pointer;}";

print "a.menu:link {color: #5E515B; text-decoration: none;}";
print "a.menu:visited {color: #5E515B; text-decoration: none;}";
print "a.menu:hover {color:#5E515B; }";
print "a.menu:active {color:#999999; }";

print "a.no:link {color: black; text-decoration: none; cursor:pointer;}";
print "a.no:visited {color: black; text-decoration: none; cursor:pointer;}";
print "a.no:hover {color:#5E515B;  text-decoration: none; cursor:pointer;}";
print "a.no:active {color:#8a4500; text-decoration: none; cursor:pointer;}";

print ".botao:hover {color:#5E515B; }";

/*FIM LINKS*/
/************************************************************/
/*FORMULÁRIO*/
$formFieldColor = "#F6F6F6"; //#F1F1F1

print ".select, .text, .select2, .text2, input.text
{
	height:20px;
	background-color:".$formFieldColor.";
	font-family: Arial, Helvetica, Sans-serif;
	font-size:11px;
	width:200px;
	color: black;
	border: 1px solid #a4a4a4;
        padding-left: 2px;
}"; //#F1F1F1


print ".textarea_desc
{
	height:60px;
	background-color:".$formFieldColor.";
	font-family: Arial, Helvetica, Sans-serif;
	font-size:11px;
	width:200px;
	color: black;
	border: 1px solid #a4a4a4;
}"; //#F1F1F1


print ".select_sol
{
	height:20px;
	background-color:".$formFieldColor.";
	font-family: Arial, Helvetica, Sans-serif;
	font-size:11px;
	width:570px;
	color: black;
	border: 1px solid #a4a4a4;
}";

print ".select:focus, .text:focus, .select2:focus, .text2:focus, input.text:focus, .text3:focus, .textarea:focus, .textarea-script:focus, ".
	".textarea2:focus, .mini:focus, .mini2:focus, .data:focus, .logon:focus, .help:focus, .select_sol:focus, .textarea_desc:focus 
{
	background-color:white;
}";

print ".checkbox
{
	background-color:white;
	font-family: Arial, Helvetica, Sans-serif;
	color: black;
	border: 1px solid #a4a4a4;
}";

print ".text3
{
	height:20px;
	background-color:#F7F7F7;
	font-family: Arial, Helvetica, Sans-serif;
	font-size:12px;
	width:300px;
	border: 1px solid #a4a4a4;
}";


print ".quadro
{
	height:13px;
	width:13px;
	border: 1px solid #CCCCCC;
}";


print "input.disable, select.disable
{
	height:20px;
	background-color:#F3F3F3;
	font-family: Arial, Helvetica, Sans-serif;
	font-size:11px;
	width:200px;
	color: black;
	border: 1px solid #a4a4a4;
        padding-left: 2px;
}";
print ".textareaDisable {height:40px; background-color:#F3F3F3; font-family: Arial, Helvetica, Sans-serif; font-size:11px; width:570px; border: 1px solid #a4a4a4;}";

print "td.disable
{
	height:20px;
	background-color:#F3F3F3;
	font-family: Arial, Helvetica, Sans-serif;
	font-size:11px;
	width:200px;
	color: black;
	border: 1px solid #a4a4a4;
}";
print "td.borda, tr.borda
{
	height:20px;
	background-color:white;
	font-family: Arial, Helvetica, Sans-serif;
	font-size:11px;
	width:200px;
	color: black;
	border: 1px solid #a4a4a4;
}";

print "td.bordaprint
{
	height:20px;
	background-color:white;
	font-family: Arial, Helvetica, Sans-serif;
	font-size:11px;
	width:200px;
	color: black;
	border-bottom: 1px solid #a4a4a4;
}";
//#F7F7F7
print ".textarea {height:105px; background-color:".$formFieldColor."; font-family: Arial,Sans-Serif; font-size:12px; width:750px; border: 1px solid #a4a4a4;}";

print ".textarea-script {height:400px; background-color:".$formFieldColor."; font-family: Arial,Sans-Serif; font-size:12px; width:570px; border: 1px solid #a4a4a4;}";

print ".textarea2 {height:100px; background-color:".$formFieldColor."; font-family: Arial,Sans-Serif; font-size:12px; width:400px; border: 1px solid #a4a4a4;}";

print ".radio {width: 13px;}";

print ".mini {height:20px; background-color:".$formFieldColor."; font-family: Arial,Sans-Serif; font-size:12px; width:30px; border: 1px solid #a4a4a4;}";

print ".mini2 {height:20px; background-color:".$formFieldColor."; font-family: Arial,Sans-Serif; font-size:12px; width:90px; border: 1px solid #a4a4a4;}";

print ".data {height:20px; background-color:".$formFieldColor."; font-family: Arial,Sans-Serif; font-size:12px; width:90px; border: 1px solid #a4a4a4;}";

/*FIM FORMULÁRIOS*/
/*************************************a:link {color: #5E515B; text-decoration: none; cursor:pointer;}
/*BOTÕES*/

print ".help
{
	height:25px;
	background-color:".$formFieldColor.";
	color: #666;
	font-family: Arial,Sans-Serif;
	font-size: 11px;
	width: 100px;
	border: 1px solid #a4a4a4;
	padding: 1px 4px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	background: #FFFFFF;
	background: -webkit-gradient(linear, 0 0, 0 bottom, from(#FFFFFF), to(#DEDEDE));
	background: -webkit-linear-gradient(#FFFFFF, #DEDEDE);
	background: -moz-linear-gradient(#FFFFFF, #DEDEDE);
	background: -ms-linear-gradient(#FFFFFF, #DEDEDE);
	background: -o-linear-gradient(#FFFFFF, #DEDEDE);
	background: linear-gradient(#FFFFFF, #CCCCCC);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFF', endColorstr='#CCCCCC'); /* for IE */
	-pie-background: linear-gradient(#FFFFFF, #DEDEDE);
	behavior: url(includes/css/ie-css3.htc);
}";

print ".help:focus
{
	-webkit-box-shadow: #6177A2 0px 1px 3px;
	-moz-box-shadow: #6177A2 0px 1px 3px;
	box-shadow: #6177A2 0px 1px 3px;
}";

print ".logon
{
	height:18px;
	background-color:".$formFieldColor.";
	font-family: Arial,Sans-Serif;
	font-size:11px;
	width:80px;
	border: 1px solid #a4a4a4;
}";
print ".logon:hover
{
	color: black;
	border: 1px solid black;
}";

print ".button
{
	height: 24px;
	color: #333333;
	font-size: 12px;
	padding-left: 8px;
	padding-right: 8px;
	background: url('./bg.gif') repeat-x #f0f0f0;
	border: 1px solid #a4a4a4;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
        border-radius: 5px;
}";

$iconsPath = "../icons/";
print ".button_ok
{
	height: 30px;
	color: #333333;
	font-size: 12px;
	padding-left: 25px;
	padding-right: 8px;
	background: url('".$iconsPath."ok_2.png') no-repeat #f0f0f0 4px;
	border: 1px solid #a4a4a4;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
        border-radius: 5px;
}";
print ".button_ok:hover
{
	color: green;
	border: 1px solid black;
}";

print ".button_ca
{
	height: 30px;
	color: #333333;
	font-size: 12px;
	padding-left: 25px;
	padding-right: 8px;
	background: url('".$iconsPath."cancel.png') no-repeat #f0f0f0 4px;
	border: 1px solid #a4a4a4;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
        border-radius: 5px;
}";
print ".button_ca:hover
{
	color: red;
	border: 1px solid black;
}";

print ".button_go1
{
	height: 24px;
	color: #333333;
	font-size: 12px;
	padding-left: 8px;
	padding-right: 8px;
	background: #f0f0f0 url('".$iconsPath."1rightarrow.png') no-repeat center center;
	border: 1px solid #a4a4a4;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
        border-radius: 5px;
}";

print ".button_go2
{
	height: 24px;
	color: #333333;
	font-size: 12px;
	padding-left: 8px;
	padding-right: 8px;
	background: url('".$iconsPath."2rightarrow.png') no-repeat #f0f0f0 center center;
	border: 1px solid #a4a4a4;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
        border-radius: 5px;
}";

print ".button_ba1
{
	height: 24px;
	color: #333333;
	font-size: 12px;
	padding-left: 8px;
	padding-right: 8px;
	background: url('".$iconsPath."1leftarrow.png') no-repeat #f0f0f0 center center;
	border: 1px solid #a4a4a4;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
        border-radius: 5px;
}";

print ".button_ba2
{
	height: 24px;
	color: #333333;
	font-size: 12px;
	padding-left: 8px;
	padding-right: 8px;
	background: url('".$iconsPath."2leftarrow.png') no-repeat #f0f0f0 center center;
	border: 1px solid #a4a4a4;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
        border-radius: 5px;
}";

print ".button:hover ,.button_ba1:hover ,.button_ba2:hover ,.button_go1:hover ,.button_go2:hover
{
	color: black;
	border: 1px solid black;
}";

print ".button_novo
{
	height: 30px;
	color: #333333;
	font-size: 12px;
	padding-left: 25px;
	padding-right: 8px;
	background: url('".$iconsPath."mais.png') no-repeat #f0f0f0 4px;
	border: 1px solid #a4a4a4;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
        border-radius: 5px;
}";
print ".button_novo:hover
{
	color: blue;
	border: 1px solid black;
}";

print ".button_pesquisar
{
	height: 30px;
	color: #333333;
	font-size: 12px;
	padding-left: 25px;
	padding-right: 8px;
	background: url('".$iconsPath."search.png') no-repeat #f0f0f0 4px;
	border: 1px solid #a4a4a4;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
        border-radius: 5px;
}";
print ".button_pesquisar:hover
{
	color: blue;
	border: 1px solid black;
}";

print ".button_limpar
{
	height: 30px;
	color: #333333;
	font-size: 12px;
	padding-left: 25px;
	padding-right: 8px;
	background: url('".$iconsPath."edit-clear.png') no-repeat #f0f0f0 4px;
	border: 1px solid #a4a4a4;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
        border-radius: 5px;
}";
print ".button_limpar:hover
{
	color: #000;
	border: 1px solid black;
}";

print "input.blogin
{
	height: 45px;
        width: 80px;
	color: #666;
	font-size: 14px;
	padding-left: 8px;
	padding-right: 8px;
	background: #f0f0f0;
	border: 1px solid #a4a4a4;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
        border-radius: 5px;
        background: -webkit-gradient(linear, 0 0, 0 bottom, from(#FFFFFF), to(#eee));
	background: -webkit-linear-gradient(#FFFFFF, #eee);
	background: -moz-linear-gradient(#FFFFFF, #eee);
	background: -ms-linear-gradient(#FFFFFF, #eee);
	background: -o-linear-gradient(#FFFFFF, #eee);
	background: linear-gradient(#FFFFFF, #eee);
        behavior: url(includes/css/ie-css3.htc);
        
}";

print "input.blogin:hover
{
	color: #222;
	
	-webkit-box-shadow: #6177A2 3px 1px 3px;
	-moz-box-shadow: #6177A2 3px 1px 3px;
        behavior: url(ie-css3.htc);
	box-shadow: #6177A2 0px 2px 2px;
	
}";

print ".button-disabled
{
	height: 20px;
	color: #D0D0D0;
	font-size: 12px;
	padding-left: 8px;
	padding-right: 8px;
	background: url('./bg.gif') repeat-x #f0f0f0;
	border: 1px solid #a4a4a4;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
        border-radius: 5px;
}";
/*.minibutton {height:15px; width:40px; font-family: Arial, Helvetica, Sans-serif; font-size:9px;} */
print ".minibutton
{
	height: 15px;
	color: #333333;
	font-size: 9px;
	padding-left: 8px;
	padding-right: 8px;
	background: url('./bg.gif') repeat-x #f0f0f0;
	border: 1px solid #a4a4a4;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
        border-radius: 5px;
}";
print ".minibutton:hover
{
	color: black;
	border: 1px solid black;
}";

print ".button_new {height:20px;  background-color:#BDBDBC; color:black;}";

print ".btPadrao {height:20px;  background-color:#ECECDB; color:black;}";

print "table.likebutton
{
	padding-top:  10px;
}";

print "a.likebutton, td.likebutton
{
	border-top: 1px solid #d9d9d9;
	border-left: 1px solid #d9d9d9;
	border-right: 1px solid #000000;
	border-bottom: 1px solid #000000;
	background: #EFEFEC;
	text-align: center;
}";

print "a.likebutton
{
	padding: 3px;
	margin-left: 5px;
}";


/*FIM BOTÕES*/
/************************************************************/

print ".divAlerta 
    {
    background-color: #FAD163; 
    color: #000000;
    border: 1px solid #a4a4a4;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
    padding: 5px;
    -webkit-box-shadow: #6177A2 0px 1px 3px;
    -moz-box-shadow: #6177A2 0px 1px 3px;
    box-shadow: #6177A2 0px 1px 3px;
    }";

print ".relatorio
{
	font-family: Arial,Sans-Serif;
	font-size: 13px;
	background-color:white;
}";

print ".parag
{
	margin-left:10%;
	margin-right: 10%;
	text-indent: 1cm;
	text-align:justify;
}";

print ".parag_header
{
	margin-left:10%;
	margin-right: 10%;
}";

print "p.titulo
{
	font-family: Arial, Helvetica, Sans-serif;
	font-size: 15px;
	text-align:center;
	font-weight:bold;
}";

print ".HNT
{
	position:absolute; background: #FFFFFF; width: 300px;
	padding: 8px; border: 1px solid #d9d9d9;
}";

print ".centro {text-align: center;}";

print "#login {position:absolute; left:40%; top:176px; width:15%; height:10%; z-index:2;}";

/*#HINT {position:relative;} /*position:absolute; */

print "#topo {margin: 5px; height: 40px;}";

print "#menu {position: absolute; top: 100px; left: 10px; width: 150px; }";

print "#corpo {margin-left: 170px; margin-right: 0px; }";

print ".alerta
{
	position: absolute; top: 5px; left: 40%; width: 30%;  z-index:1;

}";

print ".loading
{
	position: absolute; top: 150px; left: 50%; width: 30%;  z-index:1;

}";

/*
ESTILOS PARA AS TOOLTIPS
*/
	print "#bubble_tooltip{
		width:300px;
		position:absolute;
		display:none;
                border: solid 1px;
                border-color: #000;
                padding: 10px;
                border-radius: 5px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
                background-color: #CCC; /* fallback color if gradients are not supported */
                background-image: -webkit-gradient(linear, left top, left bottom, from(#FFF), to(#ccc)); 
                background-image: -webkit-linear-gradient(top, #FFF, #ccc); 
                background-image:    -moz-linear-gradient(top, #FFF, #ccc); 
                background-image:     -ms-linear-gradient(top, #FFF, #ccc); 
                background-image:      -o-linear-gradient(top, #FFF, #ccc); 
                background-image:         linear-gradient(to bottom, #FFF, #ccc); /* current standard, but unimplemented */
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFF', endColorstr='#CCCCCC'); /* for IE */
                opacity: 0.9;
	}";

/*hack para tratar a camada alfa de imagens png (transparências)*/
if ($_SESSION['s_browser'] =='ie') {
	print "img {behavior: url('pngbehavior.htc');}";
        print "img {border: none;}";
}

/* visible, hidden, collapse */

//LRG - Incluido para o Menu

print "#menuwrapper {
	//border-top: 1px solid ".$row['tm_borda_color'].";
	border-bottom: 1px solid ".$row['tm_borda_color'].";
	background-color: ".$row['tm_color_barra'].";
	background: url(../../includes/imgs/bg1.png);
	background-repeat: repeat-x;
    opacity: 1;

}

.clearit {
	clear: both;
	height: 0;
	line-height: 0.0;
	font-size: 0;
}

#menubar, #menubar ul {
	padding: 0;
	margin: 0;
	list-style: none;
        vertical-align: middle;
	font-family: Arial, Helvetica, sans-serif;
}

#menubar a {
	display: block;
	text-decoration: none;
	padding: 5px 10px 5px 10px;
	border-right: 0px solid #666;
	font-size: 12px;
	color: ".$row['tm_color_barra_font'].";
}

#menubar a.trigger {
	padding: 5px 16px 5px 10px;
	background-image: url(../../includes/imgs/down.gif);
	background-repeat: no-repeat;
	background-position: right center;
}

#menubar a.trigger_r {
	padding: 5px 16px 5px 10px;
	background-image: url(../../includes/imgs/right.gif);
	background-repeat: no-repeat;
	background-position: right center;
}

#menubar a.trigger_r ul:hover {
	background-color: ".$row['tm_barra_fundo_destaque'].";
}

#menubar a.trigger_txt {
	padding: 5px 16px 5px 10px;
}

#menubar li.barra_li:hover {
	background-image: url(../../includes/imgs/bg2.png);
}

#menubar li {
	float: left;
	position:relative;
	width: 9em;
}

#menubar li ul, #menubar ul li  {
	width: 17em;
}

#menubar ul li a  {
	color: ".$row['tm_color_barra_font'].";
    background-color: ".$row['tm_color_barra'].";
	border-right: 0;
	padding: 6px 12px 3px 16px;
}

#menubar li ul {
	position: absolute;
	visibility: hidden;
	background-color: ".$row['tm_color_barra'].";
	border-right: 1px solid #666;
	border-bottom: 1px solid #333;
	border-top: 1px solid #666;
	border-left: 1px solid #666;
	background-repeat: repeat-x;
        opacity: 0;
        transition: opacity .25s ease-in-out;
        -moz-transition: opacity .25s ease-in-out;
        -webkit-transition: opacity .25s ease-in-out;
}

#menubar li{
	color: ".$row['tm_color_barra_font']."
	background-color: transparent;
}

#menubar li:hover{
	color: ".$row['tm_barra_fonte_destaque'].";
	background-color: ".$row['tm_barra_fundo_destaque'].";
}

#menubar li:hover ul  {
	visibility: visible;
    opacity: 1;
}

#menubar li:hover ul a {
	color: ".$row['tm_color_barra_font'].";
	background-color: transparent;
}

#menubar li {width: auto;}

#menubar li:hover ul ul{
        visibility: hidden;
	position:absolute; 
	width:187px;
	top:0; 
	left:187px;
        opacity: 0;
        transition: opacity .25s ease-in-out;
        -moz-transition: opacity .25s ease-in-out;
        -webkit-transition: opacity .25s ease-in-out;        
}

#menubar li:hover ul ul ul{
        visibility: hidden;
	position:absolute; 
	width:187px;
	top:0; 
	left:187px;
        opacity: 0;
        transition: opacity .25s ease-in-out;
        -moz-transition: opacity .25s ease-in-out;
        -webkit-transition: opacity .25s ease-in-out;        
}
		
#menubar li:hover ul,#menubar ul li:hover ul,#menubar ul ul li:hover ul{
	visibility: visible;
        opacity: 1;  
}

#menubar2, #menubar2 ul {
	padding: 0;
	margin: 0;
	list-style: none;
        vertical-align: middle;
	font-family: Arial, Helvetica, sans-serif;
}

#menubar2 a {
	display: block;
	text-decoration: none;
	padding: 5px 10px 5px 10px;
	border-left: 0px solid #666;
	font-size: 12px;
	color: ".$row['tm_color_barra_font'].";
}

#menubar2 a.trigger {
	padding: 5px 16px 5px 10px;
	background-image: url(../../includes/imgs/down.gif);
	background-repeat: no-repeat;
	background-position: right center;
}

#menubar2 a.trigger_txt {
	padding: 5px 16px 5px 10px;
}

#menubar2 li {
	float: right;
	position:relative;
	width: 12em;
}

#menubar2 li ul, #menubar2 ul li  {
	width: 17em;
}

#menubar2 ul li a  {
	color: ".$row['tm_color_barra_font'].";
        background-color: ".$row['tm_color_barra'].";
	border-right: 0;
	padding: 6px 12px 3px 16px;
        
}

#menubar2 li ul {
	position: absolute;
	visibility: hidden;
	background-color: ".$row['tm_color_barra'].";
	border-right: 1px solid #666;
	border-bottom: 1px solid #999;
	background-repeat: repeat-x;
        opacity: 0;
        transition: opacity .25s ease-in-out;
        -moz-transition: opacity .25s ease-in-out;
        -webkit-transition: opacity .25s ease-in-out;
}

#menubar2 li{
	color: ".$row['tm_color_barra_font']."
	background-color: transparent;
}

#menubar2 li:hover{
	color: ".$row['tm_barra_fonte_destaque'].";
	background-color: ".$row['tm_barra_fundo_destaque'].";
		background: url(../../includes/imgs/bg2.png);
}


#menubar2 li:hover ul  {
        right:0px;
        visibility: visible;
        opacity: 100;   
}

#menubar2 li:hover ul a {
	color: ".$row['tm_color_barra_font']."
	background-color: transparent;
}

#menubar2 ul a:hover {
	color: ".$row['tm_barra_fonte_destaque'].";
	background-color: ".$row['tm_barra_fundo_destaque'].";
}

#menubar2 li {width: 187px;}

#menubar2 li:hover ul ul{
        display:block;
	position:absolute; 
	width:187px;
	top:0; 
	right:187px; 	  
}

.div_login {
    width: 410px;
    height: 300px;
    margin: 0 auto;
    background: url(../../includes/imgs/f_login.png);
    background-repeat: no-repeat;
}


.input_login {
  margin: 5px 0;
  float: left;
  clear: both;
  font-size: 12px;
  color: #999;
}

.input_login span {
  position: absolute;
  padding: 5px;
  margin-left: 3px;
  color: #ddd;
  font-family: arial;
  font-size: 14px;
  font-weight: bold;
}

.input_l2 {
    width: 180px;
    height: 30px;
    margin-left: 0px;
    padding-left: 5px;
    font-size: 14px;
    font-weight: bold;
    color: #aaa;
    border-color: #999;
    border-width: 1px;
    border-style: solid;
}";

if ($_SESSION['s_browser'] =='ie') {
	print "
    .input_l2 {
    width: 180px;
    height: 30px;
    margin-left: 0px;
    padding-top: 5px;
    padding-left: 5px;
    font-size: 14px;
    font-weight: bold;
    color: #aaa;
    border-color: #999;
    border-width: 1px;
    border-style: solid;
}
";
}

print "
.input_login input, .input_login textarea, .input_login select {
    position: relative;
    margin: 0;
    border-width: 1px;
    padding: 5px;
    background: transparent;
    font-size: 14px;
    font-weight: bold;
    color: #666;
}

/* Hack to remove Safaris extra padding. Remove if you dont care about pixel-perfection. */
@media screen and (-webkit-min-device-pixel-ratio:0) {
    .input_login input, .input_login textarea, .input_login select { padding: 4px; }
}
";


?>
