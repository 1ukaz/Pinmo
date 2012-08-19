<?php

session_start();

if (!isset($_SESSION["Pinmo"]))
	header("Location:login.php");
else
	if ($_SESSION["Pinmo"]->getUser()->getOid() != session_id())
		header("Location:login.php?acc=logoff");

header("Content-Type: text/html; charset=UTF-8");

include("../config/conf.smarty.php");
include("../config/functions.inc.php");

function __autoload($class_name) {
   require_once("../../classes/{$class_name}.class.php");
}

$acc_final = (isset($_REQUEST['acc'])) ? $_REQUEST['acc'] : NULL;

switch ($acc_final) {

	case "ve":
		if ($_SERVER["HTTP_X_REQUESTED_WITH"] != "XMLHttpRequest")
			die("No se puede acceder directamente al Script");
		$oProp = clone $_SESSION["Pinmo"]->getSearcher()->getArrayMember( $_GET["i"] );
		$oProp->getAssociatedAmbientesFromDB();
		$oProp->getAssociatedItemsFromDB();
		$smarty->assign("tplArrayIndex", $_GET["i"]);
		$smarty->assign("tplProp", $oProp);
		$smarty->display("propiedadDetalle.html");
		//echo "<pre style='text-align:left;'><font size='2'>"; print_r($oProp); echo "</font></pre>";
	break;

	case "pd":
		$oProp = $_SESSION["Pinmo"]->getSearcher()->getArrayMember( $_GET["i"] );
		$oProp->getAssociatedAmbientesFromDB();
		$oProp->getAssociatedItemsFromDB();
		$smarty->assign("tplInmobiliaria", $_SESSION['Pinmo']->getUser()->getInmobiliaria());
		$smarty->assign("tplProp", $oProp);
		//$smarty->display("propiedadDetallePDF.html"); break;
				
		//$tmpfile = tempnam(sys_get_temp_dir(), "dompdf_");
				
		$tmpfile = tempnam("/www/pinmo.com.ar/htdocs/backend/includes/dompdf/tmp", "dompdf_");
		
		if($tmpfile) {
			file_put_contents($tmpfile, $smarty->fetch("propiedadDetallePDF.html"));
			$url = "dompdf.php?input_file=" . rawurlencode($tmpfile) . "&paper=letter&output_file=" . rawurlencode("Propiedad_{$oProp->getIdPropiedad()}.pdf");
			header("Location:http://" . $_SERVER["HTTP_HOST"] . "/backend/includes/dompdf/$url");	
		}
		else
			die("<p align='center'><h2>Ocurrio un ERROR EN EL SERVIDOR. Realice la Tarea nuevamente refrescando (F5) la Pagina del Panel</h2></p>");
	break;

	default :
		echo("<p align='center'><img src='../../css/images/black/404html.png' /></p>");
	break;

} // Fin del Switch

?>