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

print "<html><head><title>OcoMon Help: Configuração de Preventiva</title>";

	print "<style type=\"text/css\"><!--";
	print "body.corpo {background-color:#F6F6F6; font-family:helvetica;}";
	print "p{font-size:12px; text-align:justify; }";
	print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:left;
		border: 0px; border-spacing:1 ;background-color:#f6f6f6; padding-top:10px; }";
	print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";
	print "--></STYLE>";
	print "</head><body class='corpo'>";

	print "AJUDA DO OCOMON";
        
?>

        <p><b>Ajuda Tela de configuração de preventiva</b></p>
        
        <p>Você deve configurar em:</p>
        <ul>
            <li><p>Chamado de preventiva: Qual o chamado padrão para abrir quando clica no botão Iniciar Preventiva</p></li>
            <li><p>Tempo aceitável: Tempo Mínimo em dias para iniciar o processo de preventiva, a partir desses dias que o sistema calcula a diferença de data para aparecer na listagem de preventiva.</p></li>
            <li><p>Tempo crítico: Tempo Máximo para a preventiva, a partir desses dias as data ficam em vermelho na listagem, indicando que passou do tempo esperado para fazer a preventiva.</p></li>
            <li><p>Tempo até a 1ª preventiva: Configura a quantidade de dias que um equipamento novo deve fazer a sua primeira preventiva.</p></li>
            <li><p>Data para início da preventiva: Configura a partir de quando começa a funcionar a preventiva geral.</p></li>
        </ul>


<?





print "</body></html>";
?>
