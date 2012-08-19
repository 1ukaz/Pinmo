<?php

header("Content-Type: text/html; charset=UTF-8");

include("../config/conf.smarty.php");
include("../config/functions.inc.php");

function __autoload($class_name) {
   require_once("../../../classes/{$class_name}.class.php");
}

$acc_final = (isset($_REQUEST['acc'])) ? $_REQUEST['acc'] : NULL;

switch ($acc_final) {

	case "l":		// Localidades para Combo en formato JSON determinadas por el ID de la Prov seleccionada previamente
		if ($_SERVER["HTTP_X_REQUESTED_WITH"] != "XMLHttpRequest")
			die("No se puede acceder directamente al Script");
		echo Ubicacion::getLocalidadesAsOptionArray( 1, $_GET['idProv'] );
	break;
	
	case "info":
		echo(phpinfo());
	break;
	
	default :
		$smarty->assign("tplCboProvs", Ubicacion::getProvinciasAsOptionArray());
		$smarty->assign("tplCboLocs", Ubicacion::getLocalidadesAsOptionArray(0, 1));
		$smarty->assign("tplCboInmob", Inmobiliaria::getInmobiliariasAsOptionArray());
		$smarty->assign("tplCboTisProp", Propiedad::getTiposPropiedadAsOptionArray());
		$smarty->assign("tplCboTisPubs", Publicacion::getTiposPublicacionAsOptionArray());
		$smarty->assign("tplVistas", ServicioDeBusquedaPortal::getPropiedadesMasVistas());
		$smarty->assign("tplDestacadas", ServicioDeBusquedaPortal::getPropiedadesDestacadas());
		$smarty->display("index.html");
	break;

} // Fin del Switch

?>