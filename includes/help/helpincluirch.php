<?php 
print "<html><head><title>OcoMon Help: Tela principal</title>";

	print "<style type=\"text/css\"><!--";
	print "body.corpo {background-color:#F6F6F6; font-family:helvetica;}";
	print "p{font-size:12px; text-align:justify; }";
	print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:left;
		border: 0px; border-spacing:1 ;background-color:#f6f6f6; padding-top:10px; }";
	print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";
	print "--></STYLE>";
	print "</head><body class='corpo'>";

	print "AJUDA DO OCOMON";

	print "<p><b>Tela principal para abertura de chamados:</b></p>";
		print "<ul>";
		print "<li><p>Nesta tela voc� tem acesso ao formulario de abertura de ocorr�ncias, serve para realizar a abertura de chamados aos usu�rios.</p></li>";
	    print "</ul>";
	print "<p><b>Abertura de Ocorre�ncias:</b></p>";
	    print "<ul>";
		print"<li><p>�rea Respons�vel: Qual a area responsavel que ira atender o chamado</p></li>";
		print"<li><p>Problema: Descri��o do problema a ser resolvido</p></li>";
		print"<li><p>Unidade:Em qual unidade ocorreu o problema</p></li>";
		print"<li><p>Etiqueta do equipamento: Numero do equipamento com problema</p></li>";
		print"<li><p>Contato: Nome de quem solicitou a abertura do chamado</p></li>";
		print"<li><p>Ramal: Numero do ramal ou telefone de onde solicitou a abertura do chamado</p></li>";
		print"<li><p>Local: Qual setor solicitou a abertura do chamado</p></li>";
		print"<li><p>T�cnico: Nome da pessoa quem est� realizando a abertura do chamado</p></li>";
		print"<li><p>Data de abertura: Data e Hora da abertura do chamado</p></li>";
		print"<li><p>Status: Como est� o andamento do chamado se est� em atendimento ou est� aguardando atendimento</p></li>";
		print"<li><p>Agendar o chamado: Agendar o chamado a ser atendido colocando data e hora para realizar o atendimento</p></li>";
		print"<li><p>Replicar este chamado mais:Fazer uma copia do chamado</p></li>";
		print"<li><p>Prioridade:N�vel para o atendimento do chamado</p></li>";
		print"<li><p>Encaminhar o chamado para: Encaminhar chamado aberto para outro tecnico</p></li>";
		print"<li><p>Aberto Por:</p></li>";
		print"<li><p>Anexar arquivo: Anaxar um arquivos para ser analizados por outro tecnicos</p></li>";
		print"<li><p>Enviar e-mail para: Enviar email para as areas</p></li>";
		print "</ul>";
		
	print "<p><b>Bot�es</b></p>";
		print "<ul>";
		print "<li><p>Bot�o de Configura��es: Tras todas as configura��es do aparalho, atrav�s do numero atribuido a ele</p></li>";
		print "<li><p>Bot�o Hist�rico: Traz todo o Hist�rico de Chamados abertos do aparelho, atrav�s do numero atribuido a ele</p></li>";
		print "</ul>";


print "</body></html>";

?>