<?php

session_start();

if (!isset($_SESSION["Pinmo"]))
	header("Location:login.php");
else
	if ($_SESSION["Pinmo"]->getUser()->getOid() != session_id())
		header("Location:login.php?acc=logoff");
	elseif ($_SERVER["HTTP_X_REQUESTED_WITH"] != "XMLHttpRequest")
		header("Location:login.php?acc=logoff");

header("Content-Type: text/html; charset=UTF-8");

include("../config/conf.smarty.php");
include("../config/conf.mailer.php");
include("../config/functions.inc.php");
require_once("../includes/PHPMailer/class.phpmailer.php");

$acc_final = (isset($_REQUEST['acc'])) ? $_REQUEST['acc'] : NULL;
$listPage  = (isset($_REQUEST['p']) ? $_REQUEST['p'] : 1);

function __autoload($class_name) {
   require_once("../../classes/{$class_name}.class.php");
}

switch ($acc_final) {

	case "le":
		$mensaje = new Mensaje;
		$mensaje->setIdMensaje( $_GET["id"] );
		if($mensaje->marcarComoLeido()) 
			echo "El Mensaje se marco como leido";
		//echo "<div style='float:left; width:100%;'><pre style='text-align:left;'><font size='2'>"; print_r($_GET); echo "</font></pre></div>";
	break;

	case "re":
		$mensaje = new Mensaje($aUsername, $aPassword, $aHost, $aSubject, $aLanguage);
		$mensaje->setIdMensaje( $_GET["id"] );
		$mensaje->setRemitente( $_SESSION['Pinmo']->getUser()->getEmail() );
		if($mensaje->enviarRespuesta( $_GET["b"], $_GET["m"], $_SESSION['Pinmo']->getUser()->getInmobiliaria()->getNombre() ) === true) 
			header("Location:centroMensajes.php?p=".$listPage);
		else
			echo $mensaje->error;
			
	break;

	case "de":
		$mensaje = new Mensaje;
		$mensaje->setIdMensaje( $_GET["id"] );
		if($mensaje->eliminarMensaje()) {
			header("Location:centroMensajes.php?p=".$listPage);
		}
	break;

	default :
		$oPaginador = new Paginador(8);
		$_SESSION["Pinmo"]->getUser()->getInmobiliaria()->getMensajesFromDB( $_SESSION["Pinmo"]->getUser()->getId(), $listPage, $oPaginador );
		$smarty->assign("tplInmobiliaria", $_SESSION["Pinmo"]->getUser()->getInmobiliaria());
		$smarty->assign("tplPaginador", $oPaginador);
		$smarty->assign("tplPaginatorCombo", $oPaginador->generateComboValues());
		$smarty->display("centroMensajes.html");
	break;

} // Fin del Switch

?>