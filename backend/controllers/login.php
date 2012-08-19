<?php

header("Content-Type: text/html; charset=UTF-8");

include("../config/conf.smarty.php");

$acc_final = (isset($_REQUEST['acc'])) ? $_REQUEST['acc'] : NULL;

switch ($acc_final) {

	case "logoff":
		session_start();
		session_unset();
		session_destroy();
		header("Location:index.php");
	break;

	default :
		$smarty->assign("tpl_msg", "Ingrese los Datos");
		$smarty->display("login.html");
	break;

} // Fin del Switch

?>