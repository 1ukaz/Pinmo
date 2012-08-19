<?php

	$exito = false;

	$mensaje = '
	<html>
	
	<head>
	<title>Consulta recibida desde la página Web</title>
	</head>
	
	<body>
	<table>
	
	<tr>
	<td>Ref.: <strong>'.$_POST['txtProp'].'</strong></td>
	</tr>	
	
	<tr>
	<td>Nombre de la persona que realiza la consulta: <strong>'.$_POST['txtNombre'].'</strong></td>
	</tr>
	
	<tr>
	<td>&nbsp;</td>
	</tr>
	
	<tr>
	<td>Email: <strong>'.$_POST['txtEmail'].'</strong></td>
	</tr>
	
	<tr>
	<td>&nbsp;</td>
	</tr>
	
	<tr>
	<td>Consulta: <strong>'.$_POST['txtConsulta'].'</strong></td>
	</tr>
	
	</table>
	</body>
	
	</html>';

	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= "From: " . $_POST['txtEmail'] . "\n" . "Return-Path: " . $_POST['txtEmail'] . "\n" . "Reply-To: " . $_POST['txtEmail'] . "\n";

	/*tracking de consultas*/
	require_once($_SERVER['DOCUMENT_ROOT'].'/includes/staticdb.php');
	require_once('../../../../old_classes/Log.class.php');
	$oLog = new LogConsultaPropiedad($_POST['txtID'], $_POST['txtEmail'], $_POST['txtConsulta']);
	$oLog->saveToDB();	
	
	$exito = true;
		
	$query  = " SELECT email ";
	$query .= " FROM Propiedades ";
	$query .= " NATURAL JOIN Usuarios";
	$query .= " WHERE idPropiedad = ".addslashes($_POST['txtID']);
	
	$result = mysql_query($query);
	
	if( mysql_num_rows($result)>0 ){
		
		$row = mysql_fetch_row($result);
		
		$exito= mail($row[0], "Consulta desde la pagina web", $mensaje,$headers);
		
	}	
	
	
	if($_POST['txtLang']=='es'){
		echo "<div style=\"width:70%;\"><div class=\"ui-state-highlight ui-corner-all\" style=\"margin-top:20px; margin-bottom:15px; padding:1em .3em 0 1.5em;\"> 
          		<p><span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: .3em;\"></span> 
          		<strong>Gracias!</strong> A la brevedad le responderemos su consulta.- </p></div></div>";
	}
	elseif($_POST['txtLang']=='en') {
		echo "<div style=\"width:70%;\"><div class=\"ui-state-highlight ui-corner-all\" style=\"margin-top:20px; margin-bottom:15px; padding:1em .3em 0 1.5em;\"> 
          		<p><span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: .3em;\"></span> 
          		<strong>Thank you!</strong> You will receive an answer as soon as posible.- </p></div></div>";
	}

	
?>
