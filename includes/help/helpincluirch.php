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
		print "<li><p>Nesta tela você tem acesso ao formulario de abertura de ocorrências, serve para realizar a abertura de chamados aos usuários.</p></li>";
	    print "</ul>";
	print "<p><b>Abertura de Ocorreências:</b></p>";
	    print "<ul>";
		print"<li><p>Área Responsável: Qual a area responsavel que ira atender o chamado</p></li>";
		print"<li><p>Problema: Descrição do problema a ser resolvido</p></li>";
		print"<li><p>Unidade:Em qual unidade ocorreu o problema</p></li>";
		print"<li><p>Etiqueta do equipamento: Numero do equipamento com problema</p></li>";
		print"<li><p>Contato: Nome de quem solicitou a abertura do chamado</p></li>";
		print"<li><p>Ramal: Numero do ramal ou telefone de onde solicitou a abertura do chamado</p></li>";
		print"<li><p>Local: Qual setor solicitou a abertura do chamado</p></li>";
		print"<li><p>Técnico: Nome da pessoa quem está realizando a abertura do chamado</p></li>";
		print"<li><p>Data de abertura: Data e Hora da abertura do chamado</p></li>";
		print"<li><p>Status: Como está o andamento do chamado se está em atendimento ou está aguardando atendimento</p></li>";
		print"<li><p>Agendar o chamado: Agendar o chamado a ser atendido colocando data e hora para realizar o atendimento</p></li>";
		print"<li><p>Replicar este chamado mais:Fazer uma copia do chamado</p></li>";
		print"<li><p>Prioridade:Nível para o atendimento do chamado</p></li>";
		print"<li><p>Encaminhar o chamado para: Encaminhar chamado aberto para outro tecnico</p></li>";
		print"<li><p>Aberto Por:</p></li>";
		print"<li><p>Anexar arquivo: Anaxar um arquivos para ser analizados por outro tecnicos</p></li>";
		print"<li><p>Enviar e-mail para: Enviar email para as areas</p></li>";
		print "</ul>";
		
	print "<p><b>Botões</b></p>";
		print "<ul>";
		print "<li><p>Botão de Configurações: Tras todas as configurações do aparalho, através do numero atribuido a ele</p></li>";
		print "<li><p>Botão Histórico: Traz todo o Histórico de Chamados abertos do aparelho, através do numero atribuido a ele</p></li>";
		print "</ul>";


print "</body></html>";

?>