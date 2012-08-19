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

include("../config/functions.inc.php");

function __autoload($class_name) {
   require_once("../../classes/{$class_name}.class.php");
}

$acc_final = (isset($_REQUEST['acc'])) ? $_REQUEST['acc'] : NULL;

$_SESSION['Pinmo']->getDb( )->open();

switch ($acc_final) {

	case "pr":		// Provincias para Combo en formato JSON
		echo Ubicacion::getProvinciasAsOptionArray( 1 );
	break;

	case "l":		// Localidades para Combo en formato JSON determinadas por el ID de la Prov seleccionada previamente
		echo Ubicacion::getLocalidadesAsOptionArray( 1, $_GET['idProv'] );
	break;

	case "ti":		// Tipos de Inmubles en formato JSON
		echo Propiedad::getTiposPropiedadAsOptionArray( 1 );
	break;

	case "tp":		// Tipos de Publicaciones en formato JSON
		echo Publicacion::getTiposPublicacionAsOptionArray( 1 );
	break;

	case "in":		// Inmobiliarias en formato JSON
		echo Inmobiliaria::getInmobiliariasAsOptionArray( 1,  $_SESSION['Pinmo']->getUser()->getInmobiliaria()->getIdInmobiliaria() );
	break;

	case "am":		// Tipos de Ambientes en formato JSON
		echo Ambiente::getTiposAmbientesAsOptionArray( 1 );
	break;

	default :
		echo "Sin Valores";
	break;

} // Fin del Switch

$_SESSION['Pinmo']->getDb( )->close();

?>