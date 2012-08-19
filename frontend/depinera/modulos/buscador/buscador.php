<?php

$categoria= (isset($_POST['cboCategoria']))? $_POST['cboCategoria']: "1";
$accion = (isset($_POST['accion']))? $_POST['accion']: "listar";
$propiedad =  (isset($_POST['propiedad']))? $_POST['propiedad']: "";

//inicializo variables usadas por el buscador
$tipoPropiedad = (isset($_POST['cboTipoPropiedad']))? addslashes(trim($_POST['cboTipoPropiedad'])): "T";
$provincia = (isset($_POST['cboProvincia']))? addslashes(trim($_POST['cboProvincia'])): "T";
$localidad = (isset($_POST['cboLocalidad']))? addslashes(trim($_POST['cboLocalidad'])): "T";
$ambientes = (isset($_POST['cboAmbientes']))? addslashes(trim($_POST['cboAmbientes'])): "T";
$superficie = (isset($_POST['cboSuperficieTotal']))? addslashes(trim($_POST['cboSuperficieTotal'])): "T";
$antiguedad = (isset($_POST['cboAntiguedad']))? addslashes(trim($_POST['cboAntiguedad'])): "T";
$codigoReferencia= (isset($_POST['txtCodigoReferencia']))? addslashes(trim($_POST['txtCodigoReferencia'])): "";
$calle = (isset($_POST['txtCalle']))? addslashes(trim($_POST['txtCalle'])): "";
$numero= (isset($_POST['txtNumero']))? addslashes(trim($_POST['txtNumero'])): "";
$precio = (isset($_POST['txtPrecio']))? addslashes(trim($_POST['txtPrecio'])): "";

//cargo combos
$aTiposPropiedad =& $oSistema->getTiposPropiedad();
$aProvincias =& $oSistema->loadProvinciasConPropiedadesFromDB($idInmobiliaria);
$aLocalidades =& $oSistema->loadLocalidadesConPropiedadesFromDB($provincia, $idInmobiliaria);

$oFiltro = new Filtro();

//agrego tabla para join
$oFiltro->agregarJoin("LEFT OUTER JOIN Propiedades_Publicacion ON Propiedades.idPropiedad = Propiedades_Publicacion.idPropiedad");

//SIEMPRE MUESTRO SOLO LAS PROPIEDADES ACTIVAS!
$oFiltro->agregarCriterio("AND idEstadoPublicacion NOT IN(".ESTADO_PUBLICACION_NO_PUBLICADO.",".ESTADO_PUBLICACION_ARCHIVADO.") ");

//siempre filtro por inmobiliaria y tipo publico

//con esta linea muestro las prop publicas del resto 
//$oFiltro->agregarCriterio("AND ( Propiedades.idInmobiliaria='$idInmobiliaria' OR Propiedades_Publicacion.idEstadoExclusividad ='".ESTADO_EXCLUSIVIDAD_PUBLICA."'  ) ");

$oFiltro->agregarCriterio("AND ( Propiedades.idInmobiliaria=$idInmobiliaria ) ");

//siempre filtrar las que estan publicadas en gral
$oFiltro->agregarCriterio("AND Propiedades.idEstado = ".ESTADO_PUBLICACION_PUBLICADO."");


if ($accion == "buscar"){
				
	//separo el posteo de filtros si es que existen
	if(is_numeric($categoria)){
		$oFiltro->agregarCriterio("AND idTipoPublicacion = $categoria");
	}
	
	if(is_numeric($tipoPropiedad)){			
		$oFiltro->agregarCriterio("AND idTipoPropiedad = $tipoPropiedad");
	}

	if($codigoReferencia!=''){			
		$oFiltro->agregarCriterio("AND codigoReferencia = $codigoReferencia ");
	}

	if($calle!=''){			
		$oFiltro->agregarCriterio("AND calle LIKE '%$calle%' ");
	}
	
	if(is_numeric($provincia)){	
		
		$oFiltro->agregarCriterio("AND idProvincia = $provincia");
	}

	if(is_numeric($localidad)){			
		$oFiltro->agregarCriterio("AND idLocalidad = $localidad");
	}

	
	if(is_numeric($ambientes)){	
		
		switch ($ambientes){
			
			case 1: case 2:	case 3:	case 4:
				$oFiltro->agregarCriterio("AND ambientes = $ambientes"); //IGUAL A 1,2,3 o 4 AMB
			break;
			
			case 5:
				$oFiltro->agregarCriterio("AND ambientes > 4"); //IGUAL A 1,2,3 o 4 AMB
			break;
			
			default:
			break;
		}
		
	}

	if( is_numeric($superficie)){		

		
		switch ($superficie){
			
			case 1: //hasta 40 metros
				$oFiltro->agregarCriterio("AND superficieTotal <= 40"); 
			break;
			
			
			case 2:	//mas de 40 metros
				$oFiltro->agregarCriterio("AND superficieTotal > 40"); 
			break;
			
			case 3: //mas de 60 metros
				$oFiltro->agregarCriterio("AND superficieTotal >= 60"); 
			break;
			
			case 4: //mas de 100 metros
				$oFiltro->agregarCriterio("AND superficieTotal >=100"); 
			break;
			
			default:
			break;
		}			
					
	}

	if(is_numeric($antiguedad)){			
					
		switch ($antiguedad){
			
			case 0: //ESTRENAR
				$oFiltro->agregarCriterio("AND antiguedad = 0");
			break;
			
			case 1:	//HASTA 10 AÑOS
				$oFiltro->agregarCriterio("AND antiguedad <= 10");
			break;
			
			case 2: //HASTA 25
				$oFiltro->agregarCriterio("AND antiguedad <= 25");
			break;
			
			case 3: //HASTA 50
				$oFiltro->agregarCriterio("AND antiguedad <= 50");
			break;
			
			case 4: //MAS DE 50
				$oFiltro->agregarCriterio("AND antiguedad > 50");
			break;
			
			default:
			break;
		}	
	}
	
	if(is_numeric($precio)){			
		$oFiltro->agregarCriterio("AND monto <= $precio");
	}
}	


//luego de creado el filtro cargo el form
include("buscador.form.php");
		
	
?>
