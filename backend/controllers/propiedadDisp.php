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
include("../config/functions.inc.php");

function __autoload($class_name) {
   require_once("../../classes/{$class_name}.class.php");
}

$acc_final = (isset($_REQUEST['acc'])) ? $_REQUEST['acc'] : NULL;

switch ($acc_final) {

	case "ld":
		$_SESSION["unaPropiedad"] = clone $_SESSION["Pinmo"]->getSearcher()->getArrayMember( $_GET["i"] );
		$_SESSION["unaPropiedad"]->getAssociatedReservasFromDB();
		$smarty->assign("tplAction", "./propiedadDisp.php?acc=ad");
		$smarty->display("propiedadDisponibilidadForm.html");
	break;

	case "ad":
		$aRes = new Reserva;
		if (!$aRes->setReservaInfo($_SESSION["unaPropiedad"]->getIdPropiedad(), $_POST["aReservaDesde"], $_POST["aReservaHasta"], $_POST["aReservaNotas"]))
			echo "<script>showMsg('".$aRes->error."', 'error-alert', true);</script>";
		else 
			$_SESSION["unaPropiedad"]->setReservasArrayMember( count($_SESSION["unaPropiedad"]->getReservas()), $aRes );
		// $_SESSION["PropDisp"]->aReservas = array_reverse( $_SESSION["PropDisp"]->getReservas() );
			
		$smarty->assign("tplProp", $_SESSION["unaPropiedad"]);
		$smarty->display("propiedadDisponibilidadList.html");
		//echo "<div style='float:left; width:100%;'><pre style='text-align:left;'><font size='2'>"; print_r($_POST); echo "</font></pre></div>";
	break;

	case "ls":
		$smarty->assign("tplProp", $_SESSION["unaPropiedad"]);
		$smarty->display("propiedadDisponibilidadList.html");
	break;

	case "de":
		$_SESSION["unaPropiedad"]->getReservasArrayMember( $_GET["po"] )->setMarca( true );
		$smarty->assign("tplProp", $_SESSION["unaPropiedad"]);
		$smarty->display("propiedadDisponibilidadList.html");
	break;

	case "re":
		if ($_SESSION["unaPropiedad"]->saveReservasToDB()) {
			echo("Los Datos se alamacenaron Correctamente");
		}
		else {
			echo($_SESSION["unaPropiedad"]->error);
			unset($_SESSION["unaPropiedad"]);
		}
	break;

	case "cl":
		echo "Si realizo Cambios, NO se guardaron. Debe Presionar GRABAR";
		unset($_SESSION["unaPropiedad"]);
	break;

	default :
		echo("<p align='center'><img src='../../css/images/black/404html.png' /></p>");
	break;

} // Fin del Switch

?>