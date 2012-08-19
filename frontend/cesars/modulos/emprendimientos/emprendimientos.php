<?php

$accion = (isset($_POST['accion']))? $_POST['accion']: "";

$propiedad =  (isset($_POST['propiedad']))? $_POST['propiedad']: "";

$idCriterio= (isset($_POST['idCriterio']))? $_POST['idCriterio']: "";

$categoria= (isset($_POST['cboCategoria']))? $_POST['cboCategoria']: "1";

$propiedad =  (isset($_POST['propiedad']))? $_POST['propiedad']: "";

//inicializo variables usadas por el buscador
/*
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
*/

//cargo combos
/*
$aTiposPropiedad =& $oSistema->getTiposPropiedad();
$aProvincias =& $oSistema->loadProvinciasConPropiedadesFromDB($idInmobiliaria);
$aLocalidades =& $oSistema->loadLocalidadesConPropiedadesFromDB($provincia, $idInmobiliaria);
*/

$oFiltro = new Filtro();

//agrego tabla para join
$oFiltro->agregarJoin("LEFT OUTER JOIN Propiedades_Publicacion ON Propiedades.idPropiedad = Propiedades_Publicacion.idPropiedad");

//SIEMPRE MUESTRO SOLO LAS PROPIEDADES ACTIVAS!
$oFiltro->agregarCriterio("AND idEstadoPublicacion NOT IN(".ESTADO_PUBLICACION_NO_PUBLICADO.",".ESTADO_PUBLICACION_ARCHIVADO.") ");

//siempre filtro por inmobiliaria y tipo publico

//con esta linea muestro las prop publicas del resto 
//$oFiltro->agregarCriterio("AND ( Propiedades.idInmobiliaria='$idInmobiliaria' OR Propiedades_Publicacion.idEstadoExclusividad ='".ESTADO_EXCLUSIVIDAD_PUBLICA."'  ) ");

$oFiltro->agregarCriterio("AND ( Propiedades.idInmobiliaria IN ('$idInmobiliaria','4')  ) ");

//siempre filtrar las que estan publicadas en gral
$oFiltro->agregarCriterio("AND Propiedades.idEstado = ".ESTADO_PUBLICACION_PUBLICADO."");

$oFiltro->agregarCriterio("AND idTipoPropiedad = 16");



switch ($accion){
		
	case 'ver_detalle':
		include("emprendimientos.detalle.php");
	break;
	
	default:			
		include("emprendimientos.lista.php");	
	break;		
}


?>