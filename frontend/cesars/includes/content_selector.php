<?php

//$oNodo = new NodoExtra($modulo);

//esta variable la conservo para uso posterior dentro de los modulos
//$PATH_MODULO_ACTUAL = $oNodo->getPath();

switch($modulo){
	case 1:
		$PATH_MODULO_ACTUAL = 'modulos/estaticos/inicio.php';
	break;
	
	case 2:
		$PATH_MODULO_ACTUAL = 'modulos/propiedades/propiedades.php';
	break;

	case 3:
		$PATH_MODULO_ACTUAL = 'modulos/estaticos/servicios.php';
	break;

	case 4:
		$PATH_MODULO_ACTUAL = 'modulos/estaticos/contacto.php';
	break;

	case 7:
		$PATH_MODULO_ACTUAL = 'modulos/emprendimientos/emprendimientos.php';
	break;
	
	default:
		$PATH_MODULO_ACTUAL = 'modulos/estaticos/inicio.php';
	break;
	
}

/*
if( $oNodo->getArchivo()!='' ){
	include( $oNodo->getPath()."/".$oNodo->getArchivo() );
}
*/
include($PATH_MODULO_ACTUAL);

?>
