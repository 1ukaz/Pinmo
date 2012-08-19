<?php
//session_cache_limiter('public');

//definiciones de la aplicacion
include("includes/defines.php"); 



if($_GET){
	$accion = $_GET['accion'];
	$propiedad = $_GET['propiedad'];
	$modulo = $_GET['modulo'];
	$idioma = $_GET['idioma'];
	$idCriterio = $_GET['idCriterio'];
	

}
else{
	$accion = (isset($_POST['accion'])) ? 			$_POST['accion']: ACCION_POR_DEFECTO;
	$propiedad =  (isset($_POST['propiedad'])) ? 	$_POST['propiedad']: PROPIEDAD_POR_DEFECTO;
	$modulo = (isset($_POST['propiedad'])) ? 		$_POST['modulo'] :MODULO_POR_DEFECTO;
	$idioma = (isset($_POST['idioma']))? 			$_POST['idioma']: IDIOMA_POR_DEFECTO;
	$idCriterio= (isset($_POST['idCriterio']))? 	$_POST['idCriterio']: "";
}


include("lenguajes/$idioma.lang.php");


//inicializo sistema
include_once("../../old_classes/Sistema.class.php");

$oSistema = new Sistema($idioma);

//cargo arrays de constantes
$aTiposPublicacion =& $oSistema->getTiposPublicacion();

$aTiposMoneda =& $oSistema->getTiposMoneda();

//seleccion de inmobiliaria 
$idInmobiliaria = 2; //depinera


?>
