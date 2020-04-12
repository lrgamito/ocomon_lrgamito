<?php 

 /*                        Copyright 2005 Flï¿½io Ribeiro

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
  */session_start();


	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
        include ("includes/functions/funcoes.inc");
	include ("includes/javascript/funcoes.js");

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	$cab = new headers;
	$cab->set_title(TRANS("html_title"));
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);

	print "<BODY bgcolor=".BODY_COLOR.">";
	$hoje = date("d-m-Y H:i:s");

// 	$cor  = TD_COLOR;
// 	$cor1 = TD_COLOR;
// 	$cor3 = BODY_COLOR;

	$dados = array(); //Array que irï¿½guardar os valores para montar o grï¿½ico
	$legenda = array ();


	$queryB = $QRY["total_equip"]." where comp_inst not in (".INST_TERCEIRA.")";
		$queryB .= "and comp_situac in (1,3,5,6,8)";
		//echo $queryB;
	$resultadoB = mysql_query($queryB);
	$row = mysql_fetch_array($resultadoB);
	//$total = mysql_result($resultadoB,0);
	$total = $row["total"];

	// Select para retornar a quantidade e percentual de equipamentos cadastrados no sistema
	$query = "SELECT count(*) as Quantidade, count(*)*100/".$total." as Percentual, ".
		"T.tipo_nome as Equipamento, T.tipo_cod as tipo ".
		"FROM equipamentos as C, tipo_equip as T ".
		"WHERE C.comp_tipo_equip = T.tipo_cod and C.comp_inst not in (".INST_TERCEIRA.") and comp_situac in (1,3,5,6,8)".
		"GROUP by C.comp_tipo_equip ORDER BY Quantidade desc,Equipamento";
	

	$resultado = mysql_query($query);
	$linhas = mysql_num_rows($resultado);

	print "<table class=estat40 align='center'>";
	print "<tr><td></TD></tr>";
	print "<tr><td></TD></tr>";

	print "<tr><td align='center'><b>".TRANS("abert_titulo")."<br>Apenas Operacionais: <font color='red'>".$total."</b></td></tr>";

	print "<td>";
	print "<fieldset><legend>".TRANS("quadro")."</legend>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
	print "<TR><td><b>".TRANS("equip")."</TD><td><b>".TRANS("qtd")."</TD><td><b>".TRANS("perc")."</TD></tr>";
	$i=0;
	$j=2;

	while ($row = mysql_fetch_array($resultado)) {
		$color =  BODY_COLOR;
		$j++;
		print "<tr id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  ".
				"onMouseDown=\"marca('linha".$j."');\">";
		//print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
			print "<td><a href='mostra_consulta_comp.php?comp_tipo_equip=".$row['tipo']."' title='".TRANS('hint_geral','',0)."'>".$row['Equipamento']."</a></TD>";
			print "<td>".$row['Quantidade']."</TD>";
			print "<td>".round($row['Percentual'],2)."%</TD>";
		print "</TR>";
		$dados[]=$row['Quantidade'];
		$legenda[]=$row['Equipamento'];
		$i++;
	}

        print "<TR><td><hr><b>".TRANS('total','Total')."</TD><td><hr><b>".$total."</TD><td><hr><b>100%</TD></tr>";

		print "</TABLE>";

		$valores = "";
		for ($i=0; $i<count($dados);$i++){
			$valores.="data%5B%5D=".$dados[$i]."&";
		}
		for ($i=0; $i<count($legenda); $i++){
				$valores.="legenda%5B%5D=".$legenda[$i]."&";
		}
			$valores = substr($valores,0,-1);
                
                //LRG Created
                //Computadores Importados
                        $queryC = $QRY["total_equip"]." where comp_inst not in (".INST_TERCEIRA.")";
                        $queryC .= "and comp_situac in (10)";
                            //echo $queryC;
                        $resultadoC = mysql_query($queryC);
                        $rowC = mysql_fetch_array($resultadoC);
                        
                        $totalC = $rowC["total"];
              if($totalC != 0){
                        
                print "<hr>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
                print "<TR><td></td><td><b>".TRANS("equip")."</TD><td><b>".TRANS("qtd")."</TD><td><b></TD></tr>";
                print "<tr><td><img src='../../includes/icons/dialog-warning.png' width='24' height='24'></td><td><a href='mostra_consulta_comp.php?comp_situac=10' title='".TRANS('hint_geral','',0)."'>".TRANS("import", "Computadores Importados")."</a></td><td>".$totalC."</TD><td></td></tr>";
                print "</TABLE>";
              }
              
                //Computadores Em Estoque
                     $queryD = $QRY["total_equip"]." where comp_inst not in (".INST_TERCEIRA.")";
                        $queryD .= "and comp_situac in (8)";
                            //echo $queryC;
                        $resultadoD = mysql_query($queryD);
                        $rowD = mysql_fetch_array($resultadoD);
                        
                        $totalD = $rowD["total"];
              if($totalD != 0){
                        
                print "<hr>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
                print "<TR><td></td><td><b>".TRANS("equip")."</TD><td><b>".TRANS("qtd")."</TD><td><b></TD></tr>";
                print "<tr><td><img src='../../includes/icons/dialog-warning.png' width='24' height='24'></td><td><a href='mostra_consulta_comp.php?comp_situac=8' title='".TRANS('hint_geral','',0)."'>".TRANS("compestoque", "Computadores em Estoque")."</a></td><td>".$totalD."</TD><td></td></tr>";
                print "</TABLE>";
              }
                //LRG END
		print "</fieldset>";

		print "<TABLE align=center>";
		print "<tr><td></TD></tr>";
		print "<tr><td></TD></tr>";
		print "<tr><td></TD></tr>";
		print "<tr><td></TD></tr>";

		//print "<tr><td width=60% align=center><b><a href=mostra_consulta_comp.php?visualiza=relatorio&ordena=equipamento,".
		//		"modelo,etiqueta title='".TRANS('hint_relat_geral')."'>".TRANS('relat_geral')."</a>.</b></td></tr>";
		print "</TABLE>";


		print "<TABLE align='left' width='505'>";
		print "<tr><td><IMG ID='imggeral' SRC='../../includes/icons/open.png' width='9' height='9' ".
			"STYLE=\"cursor: pointer;\" onClick=\"invertView('grafico')\"> <b>Gráfico</b> </TD></tr>";
                
                print "<tr><td><div id='grafico' style='display: none;'><img src='graph_inv_geral_pie.php'></div></TD></tr>";
		
		print "<tr><td></TD></tr>";
		print "<tr><td></TD></tr>";

		$msgInst = "";
		//$nome = "titulo=".TRANS('tit_graf_geral')."";
		//print "<tr><td width=60% align=center><input type='button' class='button' value='".TRANS('grafico','Grï¿½fico',0)."' ".
		//	"onClick=\"return popup('graph_geral_barras.php?".$valores."&".$nome."&instituicao=".$msgInst."')\"></td></tr>";

		print "<tr><td width=40% align=center>";

		print "</td></tr>";

		print "</TABLE>";


	$cab->set_foot();

?>


