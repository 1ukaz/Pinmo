<?php



switch ($modulo){

	case 1:
		include("modulos/estaticos/inicio.php");
	break;
		
	case 2:
		include("modulos/propiedades/propiedades.php");
	break;	
	
	case 3:
		include("modulos/estaticos/servicios.php");
	break;
	
	case 4:
		include("modulos/estaticos/legales.php");
	break;	
	
	case 5:
		include("modulos/estaticos/contacto.php");
	break;	
	
	case 6:
		include("modulos/estaticos/laempresa.php");
	break;		
		
	default:
		include("modulos/estaticos/inicio.php");
	break;
}



?>
