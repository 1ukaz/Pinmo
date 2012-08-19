<?php

switch ($accion){
		
	case "buscar":
		
		include("modulos/buscador/buscador.php");
		
		//verifico que existe al menos un filtro que se crea al buscar
		if(isset($oFiltro)){
			
			if(is_a($oFiltro,"Filtro")){
				
				include("propiedades.lista.php");
			}
		}

	break;
		
	case 'ver_detalle':
		include("propiedades.detalle.php");
	break;
	
	default:			
		include("modulos/buscador/buscador.php"); 	
	break;		
}


?>
