<?php

/********************************************************************************/
/* SE INCLUYE LA CLASE SMARTY SE CREA LA INSTANCIA Y SE INDICAN LOS DIRECTORIOS */
/********************************************************************************/

include('../../smarty/libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->template_dir = '../views/templates';
$smarty->compile_dir  = '../views/templates_c';

?>