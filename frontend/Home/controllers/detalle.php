<?php

session_start();

header("Content-Type: text/html; charset=UTF-8");

include("../config/conf.smarty.php");
include("../config/functions.inc.php");

function __autoload($class_name) {
   require_once("../../../classes/{$class_name}.class.php");
}

$acc_final = (isset($_REQUEST['acc'])) ? $_REQUEST['acc'] : NULL;

switch ($acc_final) {

	default :
		$aProp = Propiedad::getPropertyToDisplay( intval($_GET["idProp"]) );
		$aProp->registerPropertyView( $_SERVER["REMOTE_ADDR"], Pinmo::getInstance()->getDb() );
		$mostrarCalendario = false;
		foreach ($aProp->getPublicaciones() as $oPublicacion) {
			if ( $oPublicacion->getIdTipoPublicacion() == 3 && $oPublicacion->getIdEstadoPublicacion() == 1 ) {
				$mostrarCalendario = true;
			}
		}
		
		$_SESSION["resHash"] = md5(uniqid(mt_srand((double)microtime()*1000000)));
		$_SESSION["geoHash"] = md5(uniqid(mt_srand((double)microtime()*1000000)));
		$_SESSION["oPropiedad"] = $aProp;
		$smarty->assign("tplPid", $_SESSION["geoHash"].";".$_SESSION["resHash"].";".$mostrarCalendario.";".$aProp->getIdPropiedad());
		$smarty->assign("tplCalendarFlag", $mostrarCalendario);
		$smarty->assign("tplToken", session_id());
		$smarty->assign("tplProp", $aProp);
		$smarty->display("detalle.html");			
	break;

	case "res":
		if($_GET["rk"] != $_SESSION["resHash"] || !isset($_SESSION["resHash"]) || $_SERVER["HTTP_X_REQUESTED_WITH"] != "XMLHttpRequest")
			die("No te hagas el gracioso...<br />Ribbit !!!");
	
		unset($_SESSION["resHash"]);

		$aProp = new Propiedad;
		$aProp->setIdPropiedad( $_GET["idProp"] );
		//$aProp->getAssociatedReservasFromDB(1);	// Asi trae TODAS NO solo a partir de HOY
		$aProp->getAssociatedReservasFromDB();		// Asi trae SOLO a partir de HOY

		$aReservas = array();
		if(count($aProp->getReservas()) > 0) {
			foreach ($aProp->getReservas() as $unaReserva) {
				$aRes["d"] = str_replace("-", "", convertirFechaSql($unaReserva->getFechaDesde()));
				$aRes["h"] = str_replace("-", "", convertirFechaSql($unaReserva->getFechaHasta()));
				$aReservas[] = $aRes;
			}
		}

		echo(json_encode($aReservas));
	break;

	case "geo":
		if($_GET["gk"] != $_SESSION["geoHash"] || !isset($_SESSION["geoHash"]) || $_SERVER["HTTP_X_REQUESTED_WITH"] != "XMLHttpRequest")
			die("No te hagas el gracioso...<br />Ribbit !!!");
	
		unset($_SESSION["geoHash"]);

		$aProp = Propiedad::getPropertyToDisplay( $_GET["idProp"] );
		if ($aProp->getIdPropiedad()) {
			echo(json_encode(array( "lat" => $aProp->getUbicacion()->getLatitud(), 
									"lon" => $aProp->getUbicacion()->getLongitud(), 
									"adr" => $aProp->getUbicacion()->getDomicilioFull(),
									"loc" => $aProp->getUbicacion()->getNomLocalidad(),
									"pro" => $aProp->getUbicacion()->getNomProvincia()
								  )
							)
			);
		}
		else
			echo "<script>showAlertBox(\"".$aProp->error."\", \"error-alert\", true });</script>";
	break;

	case "debug":
		echo("<pre>"); print_r($_SESSION); echo("</pre>");
		echo("<pre>"); print_r($_GET); echo("</pre>");
	break;

} // Fin del Switch

?>