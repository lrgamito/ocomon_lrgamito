<?php 
/*
GERA LOG PARA SISTEMAS.
ID, HORA, IP, LOGIN, MENSAGEM
adaptado do site http://www.adaptinsite.com/blog/criando-logs-com-php-e-mysql

LRG

*/
	function setLog ($mensagem,$etiqueta,$login) {
		$ip 	= $_SERVER['REMOTE_ADDR'];
		$hora 	= date('Y-m-d H:i:s'); 
		$mensagem = mysql_escape_string($mensagem);
		
		$sql = "INSERT INTO logs VALUES (NULL, '".$hora."','".$ip."','".$login."','".$etiqueta."','".$mensagem."')";
			
			if(mysql_query($sql)) {
				return true;
			}else{
				return false;
			}
	}
?>