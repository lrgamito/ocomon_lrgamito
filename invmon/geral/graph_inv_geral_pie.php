<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */session_start();
 
 error_reporting(E_ERROR);
        include ("../../includes/config.inc.php");
        include("../../includes/pchart/class/pData.class.php"); 
        include("../../includes/pchart/class/pDraw.class.php"); 
        include("../../includes/pchart/class/pPie.class.php"); 
        include("../../includes/pchart/class/pImage.class.php");
          
        include ("../../includes/classes/conecta.class.php");
	include ("../../includes/classes/auth.class.php");
	include ("../../includes/classes/dateOpers.class.php");
	include ("../../includes/functions/funcoes.inc");
	
	include ("../../includes/languages/".LANGUAGE.""); //TEMPORARIAMENTE
 	
 	include ("../../includes/queries/queries.php");
        
function html2rgb($color)
{
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array('R'=>$r, 'G'=>$g, 'B'=>$b);
}



	//$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];
       
    $_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];
        
    	$conec = new conexao;
	$conec->conecta('MYSQL');
        
	//$auth = new auth;
	//$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);

	//$hoje = date("d-m-Y H:i:s");

	$dados = array(); //Array que ir�guardar os valores para montar o gr�ico
	$legenda = array ();
        $valores = array ();
        $rotulo = array();


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
	//$linhas = mysql_num_rows($resultado);
        
        while ($row = mysql_fetch_array($resultado)) {
			
		$dados[]=$row['Quantidade'];
		$legenda[]=$row['Equipamento'];
	}
        
		$valores = array();
                for ($i=0; $i<count($dados); $i++){
                    if ($i<3){
                        $valores[$i] = $dados[$i];
                    }else {
                        $valores['3']+=$dados[$i];
                    }
                 }
                 
                $rotulo = array();
                for ($i=0; $i<count($legenda); $i++){
                    if ($i<3){
                        $rotulo[$i] = $legenda[$i];
                    }else {
                        $rotulo['3']='Outros';
                    }
                 }
                 /*print "<pre>";
                 print_r($valores);
                 print_r($rotulo);
                 print "</pre>";*/
				        
/* Create and populate the pData object */ 
 $MyData = new pData();    
 $MyData->addPoints($valores,"Serie 1");   
 $MyData->setSerieDescription("Serie 1","Equipamentos"); 

 /* Define the absissa serie */ 
 $MyData->addPoints($rotulo,"Labels"); 
 $MyData->setAbscissa("Labels"); 

 /* Create the pChart object */ 
 $myPicture = new pImage(500,300,$MyData); 

 /* Draw a solid background */ 
 $Settings = array("R"=>250, "G"=>250, "B"=>250); 
 $myPicture->drawFilledRectangle(0,0,499,299,$Settings);
 
 /* Draw a gradient overlay */ 
 $Settings = array("StartR"=>255, "StartG"=>255, "StartB"=>255, "EndR"=>80, "EndG"=>80, "EndB"=>80, "Alpha"=>50); 
 $myPicture->drawGradientArea(0,0,499,299,DIRECTION_VERTICAL,$Settings); 
 //$myPicture->drawGradientArea(0,0,499,20,DIRECTION_VERTICAL,array("StartR"=>10,"StartG"=>10,"StartB"=>100,"EndR"=>250,"EndG"=>250,"EndB"=>250,"Alpha"=>100)); 

 /* Add a border to the picture */ 
 $myPicture->drawRectangle(0,0,499,299,array("R"=>0,"G"=>0,"B"=>0)); 
 
 $myPicture->drawFilledRectangle(1,1,498,30,  html2rgb("#6177A2"));
 
 /* Write the picture title */  
 $myPicture->setFontProperties(array("FontName"=>"../../includes/pchart/fonts/arial.ttf","FontSize"=>11,"R"=>0,"G"=>0,"B"=>0)); 
 $myPicture->drawText(150,25,"Resumo de Equipamentos",array("R"=>255,"G"=>255,"B"=>255)); 

 /* Set the default font properties */  
 $myPicture->setFontProperties(array("FontName"=>"../../includes/pchart/fonts/Forgotte.ttf","FontSize"=>20,"R"=>250,"G"=>250,"B"=>250)); 

 /* Enable shadow computing */  
 //$myPicture->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20)); 

 /* Create the pPie object */  
 $PieChart = new pPie($myPicture,$MyData); 

 /* Draw a simple pie chart */  
 //$PieChart->draw2DPie(120,125,array("SecondPass"=>FALSE)); 

 /* Draw an AA pie chart */  
 //$PieChart->draw2DPie(340,125,array("DrawLabels"=>TRUE,"LabelStacked"=>TRUE,"Border"=>TRUE)); 

 /* Draw a splitted pie chart */  
 //$PieChart->draw2DPie(150,160,array("WriteValues"=>PIE_VALUE_PERCENTAGE,"Radius"=>100,"DataGapAngle"=>8,"DataGapRadius"=>6,"Border"=>TRUE,"BorderR"=>255,"BorderG"=>255,"BorderB"=>255)); 
 
 $PieChart->draw2DPie(150,160,array("WriteValues"=>PIE_VALUE_PERCENTAGE,"ValueR"=>240,"ValueG"=>240,"ValueB"=>240,"ValuePosition"=>PIE_VALUE_INSIDE,"Radius"=>100,"Border"=>TRUE,"BorderR"=>255,"BorderG"=>255,"BorderB"=>255)); 
 
 /* Write the legend */ 
 //$myPicture->setFontProperties(array("FontName"=>"../../includes/pchart/fonts/pf_arma_five.ttf","FontSize"=>14,"R"=>0,"G"=>0,"B"=>0)); 
 //$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20)); 
 //$myPicture->drawText(150,220,"Single AA pass",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE)); 
 //$myPicture->drawText(440,200,"Extended AA pass / Splitted",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE)); 

 /* Write the legend box */  
 $myPicture->setFontProperties(array("FontName"=>"../../includes/pchart/fonts/arial.ttf","FontSize"=>8,"R"=>0,"G"=>0,"B"=>0)); 
 $PieChart->drawPieLegend(300,70,array("Style"=>LEGEND_ROUND,"Mode"=>LEGEND_VERTICAL,"R"=>250,"G"=>250,"B"=>250)); 
 
 /* Render the picture (choose the best way) */ 
 $myPicture->autoOutput();
?>