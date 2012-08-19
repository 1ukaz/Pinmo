<?php

session_start();

if ($_SERVER["HTTP_X_REQUESTED_WITH"] != "XMLHttpRequest")
	die("No se puede acceder directamente al Script");

header("Content-type:text/xml");

include("../config/conf.smarty.php");
include("../config/functions.inc.php");

function __autoload($class_name) {
   require_once("../../../classes/{$class_name}.class.php");
}

$listOrden = (isset($_REQUEST['o']) ? $_REQUEST['o'] : 1);
$listPage  = (isset($_REQUEST['p']) ? $_REQUEST['p'] : 1);

$aSearch = new ServicioDeBusquedaPortal;
$aSearch->searchPropertiesForList( $listOrden, $listPage, $_GET );

$domObject = new DOMDocument("1.0");
$elNodo = $domObject->createElement("marcas");
$nodoPadre = $domObject->appendChild($elNodo); 

if (is_array($aSearch->getListado()) && count($aSearch->getListado())) {
	foreach ($aSearch->getListado() as $oProp) {
		$imgPath = (count($oProp->aImagenes) > 0) ? $oProp->aImagenes[0]->getThumbFullPath() : "../../../pictures/no-image.png";
		$elNodo = $domObject->createElement("marca");  
		$nuevoNodo = $nodoPadre->appendChild($elNodo);   
		$nuevoNodo->setAttribute("tipo", $oProp->getTipoPropiedad());
		$nuevoNodo->setAttribute("calle", $oProp->ubicacion->getCalle());
		$nuevoNodo->setAttribute("num", $oProp->ubicacion->getNumero(1));
		$nuevoNodo->setAttribute("loc", $oProp->ubicacion->getNomLocalidad());
		$nuevoNodo->setAttribute("prov", $oProp->ubicacion->getNomProvincia());  
		$nuevoNodo->setAttribute("lat", $oProp->ubicacion->getLatitud());  
		$nuevoNodo->setAttribute("lng", $oProp->ubicacion->getLongitud());
		$nuevoNodo->setAttribute("lnk", "detalles/{$oProp->getIdPropiedad()}/{$oProp->getParsedUrlFriendlyString()}.html");
		$nuevoNodo->setAttribute("thb", $imgPath);
	}
	echo utf8_decode($domObject->saveXML());
}

?>