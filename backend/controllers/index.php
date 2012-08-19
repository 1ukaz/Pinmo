<?php

header("Content-Type: text/html; charset=UTF-8");

include("../config/conf.smarty.php");
include("../config/functions.inc.php");

function __autoload($class_name) {
   require_once("../../classes/{$class_name}.class.php");
}

if (!empty($_POST)) {
	$pinmo = Pinmo::getInstance();
	if (!$pinmo->loginService( $_POST['user_LGN'], $_POST['pass_LGN'], $_SERVER["REMOTE_ADDR"], $_POST["lat_LGN"], $_POST["lon_LGN"] )) {
		$smarty->assign("tpl_error", "Datos incorrectos");
		$smarty->display("login.html");
	}
	else {
		foreach( glob( "/www/pinmo.com.ar/htdocs/backend/includes/dompdf/tmp/dompdf_*" ) as $file )
			@unlink($file);			
		$_SESSION['Pinmo'] = $pinmo;
		$smarty->assign("tplUsr", $pinmo->getUser());
		$smarty->assign("tplDecoy", md5(mt_rand()));
		$smarty->display("index.html");
	}
}
else {
	$smarty->assign("tpl_error", "Su Sesion Finalizo Correctamente");
	$smarty->display("login.html");
}

?>