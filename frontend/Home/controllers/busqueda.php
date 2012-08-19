<?php

session_start();

include("../config/conf.smarty.php");
include("../config/functions.inc.php");

header("Content-Type: text/html; charset=UTF-8");

function __autoload($class_name) {
   require_once("../../../classes/{$class_name}.class.php");
}

$acc_final = (isset($_REQUEST['acc'])) ? $_REQUEST['acc'] : NULL;
$listOrden = (isset($_REQUEST['o']) ? $_REQUEST['o'] : 1);
$listPage  = (isset($_REQUEST['p']) ? $_REQUEST['p'] : 1);

switch ($acc_final) {

	default :
		if (isset($_SESSION["tr"])) unset($_SESSION["tr"]);
		$aSearch = new ServicioDeBusquedaPortal;
		$aSearch->searchPropertiesForList( $listOrden, $listPage, $_GET );
		$_SESSION["tr"] = $aSearch->oPaginador->getNumPages();
		$smarty->assign("tplSearchObject", $aSearch);
		$smarty->assign("tplPages", $aSearch->oPaginador->getNumPages());
		$smarty->assign("tplOrden", $listOrden);
		(isset($_GET['pg'])) ? $smarty->display("busqueda.list.html") : $smarty->display("busqueda.html");			
	break;

	case "tr":
		if ($_SERVER["HTTP_X_REQUESTED_WITH"] != "XMLHttpRequest")
			die("No se puede acceder directamente al Script");
		echo(intval($_SESSION["tr"]));
		unset($_SESSION["tr"]);
	break;

	case "mp":
		if ($_SERVER["HTTP_X_REQUESTED_WITH"] != "XMLHttpRequest")
			die("No se puede acceder directamente al Script");
		$smarty->display("busqueda.map.html");
	break;

} // Fin del Switch

?>