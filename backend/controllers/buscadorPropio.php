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
$listOrden = (isset($_REQUEST['o']) ? $_REQUEST['o'] : 1);
$listPage  = (isset($_REQUEST['p']) ? $_REQUEST['p'] : 1);

$idInmobiliariaLogueada = $_SESSION['Pinmo']->getUser()->getInmobiliaria()->getIdInmobiliaria();
$idUsuarioLogueado = $_SESSION['Pinmo']->getUser()->getId();

switch ($acc_final) {

	case "search":
		if (is_object($_SESSION["Pinmo"]->getSearcher())) $_SESSION["Pinmo"]->setSearcher( null );
		$aSearch = new ServicioDeBusquedaPropio;
		if ($aSearch->searchPropertiesForList( $listOrden, $listPage, $idInmobiliariaLogueada, $_POST )) {
			$_SESSION['Pinmo']->setSearcher( $aSearch );
			$smarty->assign("tplPaginatorCombo", $aSearch->getPaginador()->generateComboValues());
			$smarty->assign("tplOrden", $listOrden);
			$smarty->assign("tplSearchObject", $aSearch);
			$smarty->assign("tplLoggedUsrId", $idUsuarioLogueado);
			$smarty->display("buscadorPropioResultados.html");
		}
		else 
			echo "<script>showMsg('".$aSearch->errores."', 'error-alert', true);</script>";
	break;
	
	default :
		$smarty->assign("tplCboProvsP", Ubicacion::getProvinciasAsOptionArray());
		$smarty->assign("tplCboTisPropP", Propiedad::getTiposPropiedadAsOptionArray());
		$smarty->assign("tplCboTisPubsP", Publicacion::getTiposPublicacionAsOptionArray());
		$smarty->display("buscadorPropio.html");
	break;

} // Fin del Switch

?>