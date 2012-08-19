<?php

$oNodo = new NodoExtra($modulo);

//esta variable la conservo para uso posterior dentro de los modulos
$PATH_MODULO_ACTUAL = $oNodo->getPath();


if( $oNodo->getArchivo()!='' ){
	include( $oNodo->getPath()."/".$oNodo->getArchivo() );
}



?>
