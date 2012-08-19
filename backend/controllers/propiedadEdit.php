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

function __autoload($class_name) {
   require_once("../../classes/{$class_name}.class.php");
}

$acc_final = (isset($_REQUEST['acc'])) ? $_REQUEST['acc'] : NULL;
$listOrden = (isset($_REQUEST['o']) ? $_REQUEST['o'] : 1);
$listPage  = (isset($_REQUEST['p']) ? $_REQUEST['p'] : 1);

switch ($acc_final) {

	// [Despublicarla = 5 Publicarla = 1]

	case "st":
		$oProp = $_SESSION["Pinmo"]->getSearcher()->getArrayMember( $_GET["idz"] );
		$refreshDivID = $_GET["idz"];
		if ($oProp->updateEstadoDePropiedad( $_GET["es"] )) {
			if ($_GET["es"] == 1) {
				echo("El Estado ha sido Modificado Exitosamente,
					  <a id=\"stateLink_{$refreshDivID}\" href=\"./propiedadEdit.php?idz={$refreshDivID}&acc=st&es=5\" class=\"stateLink\">
                      <img src=\"../../css/images/panel/Kok.png\" title=\"Click para Archivar\" /></a>
					  <img src=\"../../css/images/gen/ajax-loader-small.gif\" class=\"stateLoading\" id=\"stateLoading_{$refreshDivID}\" />");		
			}
			else {
				echo("El Estado ha sido Modificado Exitosamente,
					  <a id=\"stateLink_{$refreshDivID}\" href=\"./propiedadEdit.php?idz={$refreshDivID}&acc=st&es=1\" class=\"stateLink\">
                      <img src=\"../../css/images/panel/Kcancel.png\" title=\"Click para Publicar\" /></a>
					  <img src=\"../../css/images/gen/ajax-loader-small.gif\" class=\"stateLoading\" id=\"stateLoading_{$refreshDivID}\" />");
			}
		}
		else {
			if ($oProp->getEstadoPublicacion() == 1) {
				echo("<ul style=\"margin:0px;padding:5px;\"><li>El Estado NO se ha podido Modificar<br />Verifique que al menos una Publicacion esta Activa</li></ul>,
					  <a id=\"stateLink_{$refreshDivID}\" href=\"./propiedadEdit.php?idz={$refreshDivID}&acc=st&es=5\" class=\"stateLink\">
                      <img src=\"../../css/images/panel/Kok.png\" title=\"Click para Archivar\" /></a>
					  <img src=\"../../css/images/gen/ajax-loader-small.gif\" class=\"stateLoading\" id=\"stateLoading_{$refreshDivID}\" />");		
			}
			else {
				echo("<ul style=\"margin:0px;padding:5px;\"><li>El Estado NO se ha podido Modificar<br />Verifique que al menos una Publicacion esta Activa</li></ul>,
					  <a id=\"stateLink_{$refreshDivID}\" href=\"./propiedadEdit.php?idz={$refreshDivID}&acc=st&es=1\" class=\"stateLink\">
                      <img src=\"../../css/images/panel/Kcancel.png\" title=\"Click para Publicar\" /></a>
					  <img src=\"../../css/images/gen/ajax-loader-small.gif\" class=\"stateLoading\" id=\"stateLoading_{$refreshDivID}\" />");
			}
		}
	break;
	
	case "de":
		$oProp = clone $_SESSION["Pinmo"]->getSearcher()->getArrayMember( $_GET["i"] );
		if(!Propiedad::deletePropiedadFromDDBB($oProp->getIdPropiedad()))
			echo("No se pudo Eliminar la Propiedad");
		else
			echo("La Propiedad ha sido Eliminada");		
	break;

	case "ed":
		if (isset($_SESSION["unaPropiedad"])) unset($_SESSION["unaPropiedad"]);
		$oProp = clone $_SESSION["Pinmo"]->getSearcher()->getArrayMember( $_GET["i"] );
		$oProp->getAssociatedAmbientesFromDB();
		$oProp->getAssociatedItemsFromDB(1);
		//$oProp->getAssociatedPublicacionesFromDB(1);
		$oProp->setIdUsuario( $_SESSION['Pinmo']->getUser()->getId() );
		$_SESSION["unaPropiedad"] = $oProp;
		$smarty->assign("tplAction", "./propiedadEdit.php?acc=re&o={$listOrden}&p={$listPage}");
		$smarty->assign("tplToken", session_id());
		$smarty->assign("tplCboProvsABM", Ubicacion::getProvinciasAsOptionArray());
		$smarty->assign("tplCboLocsABM", Ubicacion::getLocalidadesAsOptionArray( 0, $oProp->ubicacion->getIdProvincia() ));
		$smarty->assign("tplCboTisPropABM", Propiedad::getTiposPropiedadAsOptionArray());
		$smarty->assign("tplCboEstPubsABM", Publicacion::getEstadosPublicacionAsOptionArray());
		$smarty->assign("tplCboEstExclABM", Publicacion::getEstadosExclusividadAsOptionArray());
		$smarty->assign("tplCboTisMoneABM", Publicacion::getTiposMonedaAsOptionArray());
		$smarty->assign("tplCboTisAmbABM", Ambiente::getTiposAmbientesAsOptionArray());
		$smarty->assign("tplArrayItems", $oProp->getItems());
		$smarty->assign("tplArrayPublicaciones", $oProp->getPublicaciones());
		$smarty->assign("tplProp", $oProp);
		$smarty->assign("tplEdFlag", true);
		$smarty->display("propiedadInformacionForm.html");
		//echo "<pre style='text-align:left;'><font size='2'>"; print_r($oProp); echo "</font></pre>";
	break;

	case "re":
		//echo "<pre style='text-align:left;'><font size='2'>"; print_r($_SESSION["unaPropiedad"]); echo "</font></pre>"; die();
		$_SESSION["unaPropiedad"]->setEstadoPublicacion(5);
		if (!$_SESSION["unaPropiedad"]->setAttributes( $_POST ))
			die($_SESSION["unaPropiedad"]->error);
		if ( !$_SESSION["unaPropiedad"]->saveToDB( 1 ) )	
			echo($_SESSION["unaPropiedad"]->error);
		else {
			echo("La Propiedad se grabo correctamente");	
			unset($_SESSION["unaPropiedad"]);
		}		
	break;

	default :
		echo("<p align='center'><img src='../../css/images/black/404html.png' /></p>");
	break;

} // Fin del Switch

?>