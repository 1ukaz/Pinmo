<?php

session_start();

header("Content-Type: text/html; charset=UTF-8");

include("../config/conf.smarty.php");
include("../config/conf.mailer.php");
include("../config/functions.inc.php");
require_once("../../../backend/includes/PHPMailer/class.phpmailer.php");

function __autoload($class_name) {
   require_once("../../../classes/{$class_name}.class.php");
}

$acc_final = (isset($_REQUEST['acc'])) ? $_REQUEST['acc'] : NULL;

switch ($acc_final) {

	case "pr":
		$aProp = Propiedad::getPropertyToDisplay( $_POST["txtID"] );
		if ($aProp->getIdPropiedad() != 0 && $aProp->getIdPropiedad() != '' && !is_null($aProp->getIdPropiedad())) {
			$anUsr = User::getUserById( $aProp->getIdUsuario() );
			if ($anUsr->getId() != 0) {
				$mensaje = new Mensaje($aUsername, $aPassword, $aHost, $_POST["txtProp"], $aLanguage);
				if (!$mensaje->validateConsultaParamsOnServerSide($_POST, 1)) {
					echo $mensaje->error;
					break;
				}
				$mensaje->setRemitente( $_POST["txtEmail"] );
				if($mensaje->enviarConsulta( $_POST["txtConsulta"], $anUsr->getEmail(), $_POST["txtNombre"], $aProp->getIdPropiedad() ) === true) 
					echo "Su Consulta Ha sido Enviada<br />El Vendedor respondera a la brevedad<br />Gracias por Visitarnos !!";
				else 
					echo $mensaje->error;
			}
			else
				echo "<ul style=\"margin:0px;padding:0px;list-style-type:disc;\"><li>Ha Ocurrido un Error, intente nuevamente luego</li></ul>";
		}
		else
			echo "<ul style=\"margin:0px;padding:0px;list-style-type:disc;\"><li>La Propiedad Referenciada NO Existe</li></ul>";
	break;

	case "po":
		$mensaje = new Mensaje($aUsername, $aPassword, $aHost, "Consulta Desde el Portal Pinmo", $aLanguage);
		if (!$mensaje->validateConsultaParamsOnServerSide($_POST, 0)) {
			echo $mensaje->error;
			break;
		}
		$mensaje->setRemitente( $_POST["txtEmail"] );
		if($mensaje->enviarConsulta( $_POST["txtConsulta"], "lukaz3nole@yahoo.com.ar", $_POST["txtNombre"], 0 ) === true) 
			echo "Muchas Gracias por su Consulta. En breve nos pondremos en contacto con Usted, para brindarle una Respuesta !";
		else 
			echo $mensaje->error;
	break;

	case "co":
		$smarty->assign("tplToken", session_id());
		$smarty->display("contacto.html");
	break;

	default:
		echo "<ul style=\"margin:0px;padding:0px;list-style-type:disc;\"><li>Ha Realizado una Accion Invalida</li></ul>";
	break;

} // Fin Switch

?>