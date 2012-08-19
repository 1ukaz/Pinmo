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

$acc_final = (isset($_REQUEST['acc'])) ? $_REQUEST['acc'] : NULL;

function __autoload($class_name) {
   require_once("../../classes/{$class_name}.class.php");
}


switch ($acc_final) {

	case "fo":
		$_SESSION["unaPropiedad"] = clone $_SESSION["Pinmo"]->getSearcher()->getArrayMember( $_GET["i"] );
		$_SESSION["unaPropiedad"]->aImagenes = NULL;
		foreach ($_SESSION["Pinmo"]->getSearcher()->getArrayMember($_GET["i"])->aImagenes as $imageObject) {
			$_SESSION["unaPropiedad"]->aImagenes[] = clone $imageObject;
		}
		$smarty->assign("tplProp", $_SESSION["unaPropiedad"]);
		$smarty->display("propiedadImagenes.html");
		
	break;

	case "re":
		if (!$_SESSION["unaPropiedad"]->saveImagesToDB($_POST["img"])){
			echo($_SESSION["unaPropiedad"]->error);			
		}
		else {
			echo("Galeria de Imagenes Actualizada");
			unset($_SESSION["unaPropiedad"]);
		}
		//echo "<pre style='text-align:left;'><font size='2'>"; print_r($_POST); echo "</font></pre><br />";
		//echo "<pre style='text-align:left;'><font size='2'>"; print_r($_SESSION["unaPropiedad"]); echo "</font></pre>";
	break;

	//asigna descripcion 
	case "ds":
		$_SESSION["unaPropiedad"]->getImagenesArrayMember( $_GET["po"] )->setIdDescripcion( $_GET["ide"] );
		$_SESSION["unaPropiedad"]->getImagenesArrayMember( $_GET["po"] )->setDescripcion( $_GET["sde"] );
		echo("La Descripcion se Asignara al GRABAR");
	break;

	//muestra el appleto para agregar
	case "ra":
		set_time_limit(120);
		$nav = getBrowserType($_SERVER['HTTP_USER_AGENT']);
		if(strstr($nav, "Internet Explorer"))
			$smarty->assign("tplIEFlag", 1);
		$smarty->assign("tplSID", session_id());		
		$smarty->assign("tplPid", $_SESSION["unaPropiedad"]->getIdPropiedad() ); //prueba por ale - sab 21/7 - hago esto porque hay algun problema para pasar la sesion y necesito que funque!		
		$smarty->display("propiedadImagenesApp.html");
		
	break;

	//deshacer
	case "rs":
		$_SESSION["unaPropiedad"]->getImagenesArrayMember( $_GET["po"] )->setMarca( false );
		//print_r($_SESSION["unaPropiedad"]->aImagenes);
		echo("La Imagen NO se Eliminara al GRABAR");
	break;

	//elimina imagen
	case "de":
		$_SESSION["unaPropiedad"]->getImagenesArrayMember( $_GET["po"] )->setMarca( true );
		//print_r($_SESSION["unaPropiedad"]->aImagenes);
		echo("La Imagen se Eliminara al GRABAR");
	break;

	//muestra abm de imagenes 
	case "rf":
		
		//print_r($_SESSION["unaPropiedad"]);		
		$smarty->assign("tplProp", $_SESSION["unaPropiedad"]);
		$smarty->display("propiedadImagenesGal.html");
	break;

	//procesa los archivos a subir
	case "up": 
				
		$file = $_FILES["userfile"];		
		
		//no tengo idea de porque pero se pierde la sesion al llamar al applet.. pase el id de prop con intencion de regenerar el objeto el id pasa
		//pero igual luego de hacer el upload la galeria no se actualizaaa!!
		$_SESSION["unaPropiedad"] = Propiedad::getPropertyToDisplay($_GET['pid']); //prueba por ale sab 21/7
		
		 				
		if (Imagen::uploadImagesAndAssign( $_SESSION["unaPropiedad"], $file ))		
			echo("Las Imagenes fueron Subidas con Exito");
		else
			echo($_SESSION["unaPropiedad"]->error);			
		
	break;
	
	case "cl":
		if (count($_SESSION["unaPropiedad"]->getImagenes()) > 0) {
			foreach ($_SESSION["unaPropiedad"]->getImagenes() as $imgObject) {
				if (!$imgObject->getIdImagen())
					$imgObject->deleteArchivosImagen();
			}
		}
		echo "Si realizo Cambios, NO se guardaron. Debe Presionar GRABAR";
		unset($_SESSION["unaPropiedad"]);
	break;

	default :
		echo("<p align='center'><img src='../../css/images/black/404html.png' /></p>");
	break;

} // Fin del Switch

?>