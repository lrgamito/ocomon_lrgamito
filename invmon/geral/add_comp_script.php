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


//Arquivo para invent�rio autom�tico


    error_reporting(E_ERROR);
	echo "<pre>";
	print_r($_REQUEST);
	echo "</pre>";
	
		//Variaveis
		$comp_nome              = $_REQUEST['comp_nome'];
		$comp_os		= $_REQUEST['comp_os'];
		$comp_sn_os		= $_REQUEST['comp_os_sn'];
		$comp_rede		= $_REQUEST['comp_rede'];
		$comp_proc		= $_REQUEST['comp_proc'];
		$comp_memo		= $_REQUEST['comp_memo'];
		$comp_hd		= $_REQUEST['comp_hd'];
		$comp_video		= $_REQUEST['comp_video'];
		$comp_cdrom		= $_REQUEST['comp_cdrom'];
		$comp_som		= $_REQUEST['comp_som'];
		$comp_comment           = $_REQUEST['comp_comment'];
		$comp_vnc		= $_REQUEST['comp_vnc'];
		$script_hard            = $_REQUEST['script_hard'];
		
			include ("../../includes/include_geral.inc.php");
			include ("../../includes/include_geral_II.inc.php");
		
		//Se for HardWare !!
		if($script_hard=='true'){
			//Verificar nome do computador
			if(isset($comp_nome)){
				$prefix = strtoupper(substr($comp_nome,0,3));
				$sufix  = strtoupper(substr($comp_nome,3,8));
				
				//print $prefix ." --> ". $sufix;
				
				if($prefix == 'HCC' && strlen($sufix)==5){
				
					$query_equip = "SELECT comp_inv FROM equipamentos WHERE comp_inv =". $sufix;
					
					$res = mysql_query($query_equip) or die (TRANS('ERR_QUERY'));
					$linha = mysql_num_rows($res);
					$row = mysql_fetch_row($res);
                                        
                                        if($linha == 0){
                                        //testa se há equipamentos com esse numero
                                            //Não existe, inclui.
                                            
                                            //Captura modelos dos itens no banco
                                            if(isset($comp_proc)){
                                                $comp_proc = str_replace("PROCESSOR: ","",ltrim($comp_proc));
                                                $comp_proc = explode(" - ", $comp_proc);
                                                    $comp_proc_fab = str_replace(" "," - ",$comp_proc[0]);
                                                    $comp_proc_fab = explode(" - ",$comp_proc_fab);
                                                $select_proc = "SELECT mdit_cod FROM modelos_itens WHERE mdit_tipo = 11 AND mdit_fabricante LIKE '".$comp_proc_fab[0]."' AND mdit_desc LIKE '".$comp_proc_fab[1]."' AND mdit_desc_capacidade LIKE '".$comp_proc[1]."'";
                                                    $res_proc = mysql_query($select_proc) or die ('ERRO PROC -> Pesquisa do Processador:<br>'.$select_proc);
                                                    $row_l_proc = mysql_num_rows($res_proc);
                                                    $row_proc = mysql_fetch_array($res_proc);
                                                    if($row_l_proc == 0){$row_proc['mdit_cod'] = "NULL";}
                                            } else {
                                                $row_proc = "NULL";
                                            }
                                            if(isset($comp_memo)){
                                                $comp_memo = str_replace("MEM: ","",ltrim($comp_memo));
                                                $comp_memo = explode(" - ", $comp_memo);
                                                    
                                                switch($comp_memo[1]){
                                                        case "0":
                                                            $comp_memo[1] = "DDR3";
                                                            break;
                                                        case "20":
                                                            $comp_memo[1] = "DDR1";
                                                            break;
                                                        case "21":
                                                            $comp_memo[1] = "DDR2";
                                                            break;
                                                        default:
                                                            $comp_memo[1] = "DDR3";
                                                    }
                                                    
                                                $select_memo = "SELECT mdit_cod FROM modelos_itens WHERE mdit_tipo = 7 AND mdit_fabricante LIKE 'Gen%rica' AND mdit_desc ='".$comp_memo[1]."' AND mdit_desc_capacidade =".$comp_memo[0]."";
                                                    $res_memo = mysql_query($select_memo) or die ('ERRO MEMO -> Pesquisa da Memória:<br>'.$select_memo);
                                                    $row_l_memo = mysql_num_rows($res_memo);
                                                    $row_memo = mysql_fetch_array($res_memo);
                                                    if($row_l_memo == 0){$row_memo['mdit_cod'] = "NULL";}
                                            } else {
                                                $row_memo = "NULL";
                                            }
                                            if(isset($comp_os)){
                                                $comp_os = ltrim($comp_os);
                                                $comp_os = explode(" ", $comp_os);
                                                $select_os   = "SELECT mdit_cod FROM modelos_itens WHERE mdit_tipo = 14 AND mdit_fabricante LIKE '%".$comp_os[0]."%' AND mdit_desc LIKE '%".$comp_os[2]."%'";
                                                    $res_os = mysql_query($select_os) or die ('ERRO OS -> Pesquisa do SO:<br>'.$select_os);
                                                    $row_l_os = mysql_num_rows($res_os);
                                                    $row_os = mysql_fetch_array($res_os);
                                                    if($row_l_os == 0){$row_os['mdit_cod'] = "NULL";}
                                            } else {
                                                $row_os = "NULL";
                                            }
                                            //Debug
                                            print "<pre>";
                                            print_r($comp_proc);
                                            print_r($comp_proc_fab);
                                            print "<br>";
                                            print_r($comp_memo);
                                            print "<br>";
                                            print_r($comp_os);
                                            print "<br>";
                                            print $select_proc;
                                            print "<br>";
                                            print $select_memo;
                                            print "<br>";
                                            print $select_os;
                                            print "<br>";
                                            print $row_l_memo;
                                            print "</pre>";
                                            
                                            //Insert
                                            $insert_equip = "INSERT INTO equipamentos ".
							"(comp_inv, comp_sn, comp_marca, comp_proc, comp_memo, ".
							"comp_nome, ".
							"comp_local, comp_fornecedor, comp_nf, comp_coment, comp_data, comp_valor, comp_data_compra, ".
							"comp_inst, comp_tipo_equip, ".
							"comp_fab, comp_situac, ".
							"comp_os)".															
						"VALUES (".$sufix.",'".$sufix."','1',".
							"".$row_proc['mdit_cod'].",".$row_memo['mdit_cod'].",".
							"'".$comp_nome."', 2, ".
							"6,'0','".noHtml($comp_comment).$comp_sn_os."', ".
							"'".date("Y-m-d H:i:s")."', '0', '".date("Y-m-d H:i:s")."', ".
							"1, 1, ".
							"125, 10, ".
							"".$row_os['mdit_cod'].")";
                                            
                                               $res = mysql_query($insert_equip) or die ('ERRO 1 -> na Inclusao do Equipamento:<br>'.$insert_equip);
                                                if ($res == 0)
                                                {
                                                    print "ERRO 2 -> Na Inclusão do Equipamento";
                                                } else {
						
                                                    print "Equipamento Incluido -> [OK]";
						
                                                }
                                            
                                        } else {
                                            //Existe.
                                            print "<pre>Computador ja cadastrado no sistema.<br>Saindo...<br></pre>";
                                        }
					
				}
			
			}
		
		}
?>