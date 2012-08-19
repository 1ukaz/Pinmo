<?php

	include($_SERVER['DOCUMENT_ROOT']."/includes/defines.php");
		
	$exito = false;
	
	$mensaje = '
	<html>
	
	<head>
	<title>Consulta recibida desde la pagina web</title>
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
	
	
	//$exito = mail(CONTACTO_EMAIL, CONTACTO_ASUNTO_EMAIL, $email);
	///$exito = mail(CONTACTO_EMAIL, CONTACTO_ASUNTO, $mensaje, $headers);
	$exito = mail("info@parque-propiedades.com.ar", "Consulta desde la pagina web", $mensaje,$headers);
	//$exito = mail("alencas@gmail.com", "Consulta desde la pagina web", $mensaje,$headers);
	
	
	if($_POST['txtLang']=='es'){
		echo "<h4 style='color: #cc3333;' >Gracias! Le responderemos a la brevedad.- </h4>";
	}
	elseif($_POST['txtLang']=='en') {
		echo "<h4 style='color: #cc3333;' >Thank you! You will receive an answer as soon as posible.- </h4>";
	}

	
?>
