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
$listPage  = (isset($_REQUEST['p']) ? $_REQUEST['p'] : 1);

function __autoload($class_name) {
   require_once("../../classes/{$class_name}.class.php");
}

switch ($acc_final) {

	default :
		echo("<br /><p align='center'><img src='../../css/images/gen/construct.png' /></p><p align='center'>En Desarrollo...</p>");
	break;

} //Fin del Switch

?>