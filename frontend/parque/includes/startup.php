<?php
//session_cache_limiter('public');

//definiciones de la aplicacion
include("includes/defines.php"); 


//manejador de modulo
if (isset($_POST['modulo']) && is_numeric($_POST['modulo'])){
	
	$modulo = $_POST['modulo'];	

	//manejador de accion
	if (isset($_POST['accion'])){	
		
		$accion = addslashes($_POST['accion']);
		
		//manejador de idPropiedad
		if (isset($_POST['propiedad']) && is_numeric($_POST['propiedad'])){
			
			$propiedad = $_POST['propiedad'];		
		}
			
		else{
			
			$propiedad = PROPIEDAD_POR_DEFECTO;	
		}			
	}
	
	else{
		
		$accion =  ACCION_POR_DEFECTO;
	}	
		
}
	
else{
	$modulo = MODULO_POR_DEFECTO;
}


//selector de idiomas
$idioma = (isset($_POST['idioma']))? $_POST['idioma']: IDIOMA_POR_DEFECTO;

include("lenguajes/$idioma.lang.php");


//inicializo sistema
include_once("../../old_classes/Sistema.class.php");

$oSistema = new Sistema($idioma);

//cargo arrays de constantes
$aTiposPublicacion =& $oSistema->getTiposPublicacion();

$aTiposMoneda =& $oSistema->getTiposMoneda();

//seleccion de inmobiliaria 
$idInmobiliaria = 3; //parque propiedades

//si no la encuentra muestro la inmo por defecto ()
//$idInmobiliaria = (is_numeric($idInmobiliaria))? $idInmobiliaria: ID_INMOBILIARIA_POR_DEFECTO;




?>