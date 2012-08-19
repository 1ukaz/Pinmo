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

	case "ne":
		$oProp = new Propiedad;
		$smarty->assign("tplAction", "./propiedadNew.php?acc=re");
		$smarty->assign("tplToken", session_id());
		$smarty->assign("tplCboProvsABM", Ubicacion::getProvinciasAsOptionArray());
		$smarty->assign("tplCboLocsABM", Ubicacion::getLocalidadesAsOptionArray());
		$smarty->assign("tplCboTisPropABM", Propiedad::getTiposPropiedadAsOptionArray());
		$smarty->assign("tplCboEstPubsABM", Publicacion::getEstadosPublicacionAsOptionArray());
		$smarty->assign("tplCboEstExclABM", Publicacion::getEstadosExclusividadAsOptionArray());
		$smarty->assign("tplCboTisMoneABM", Publicacion::getTiposMonedaAsOptionArray());
		$smarty->assign("tplCboTisAmbABM", Ambiente::getTiposAmbientesAsOptionArray());
		$smarty->assign("tplArrayItems", Item::getItemsFromDBToDisplay());
		$smarty->assign("tplArrayPublicaciones", Publicacion::getPublicacionesFromDBToDisplay());
		$smarty->assign("tplProp", $oProp);
		$smarty->assign("tplEdFlag", false);
		$smarty->display("propiedadInformacionForm.html");
		//echo "<pre style='text-align:left;float:left;'><font size='2'>"; print_r($oProp); echo "</font></pre>";
	break;

	case "re":
		$oProp = new Propiedad;
		if (!$oProp->setAttributes( $_POST ))
			die($oProp->error);

		$oProp->setInmobiliaria( $_SESSION['Pinmo']->getUser()->getInmobiliaria() );
		$oProp->setIdUsuario( $_SESSION['Pinmo']->getUser()->getId() );

		//echo "<pre style='text-align:left;'><font size='2'>"; print_r($oProp); echo "</font></pre>"; die();

		if ( !$oProp->saveToDB( 0 ) )	
			echo($oProp->error);
		else
			echo("La Propiedad se grabo correctamente");
	break;
	
	default :
		echo("<p align='center'><img src='../../css/images/black/404html.png' /></p>");
	break;
	
} // Fin del Switch

?>