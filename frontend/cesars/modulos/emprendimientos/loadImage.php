<?php

include ("../../includes/staticdb.php");
include ("../../includes/defines.php");
include ("../../../../old_classes/Imagen.class.php");

$idImagen  = $_GET['id'];
$idPropiedad = $_GET['idp'];

if(!is_numeric($idImagen) || !is_numeric($idPropiedad) ){
	header("Content-Type: image/png");
	
	echo file_get_contents("../../../../pictures/no-image.png");
}

$oImagen = new Imagen($idImagen,$idPropiedad);
$oTmp =& $oImagen->loadFromDB($idPropiedad,$idImagen);


//si el archivo existe en fs
if(file_exists("../../../../pictures/imagenes/".$oTmp->getArchivo())){
	
	//si no existe el thumbnail lo creo
	if(!file_exists("../../../../pictures/thumbnails/".$oTmp->getArchivo())){
			
		$oTmp->crearThumbnail("../../../../pictures/imagenes/".$oTmp->getArchivo(), "../../../../pictures/thumbnails/".$oTmp->getArchivo());
	}
	
	//luego de crear lo muestro
	header("Content-Type: image/jpeg");
	
	echo file_get_contents("../../../../pictures/thumbnails/".$oTmp->getArchivo());	
						
}


?>