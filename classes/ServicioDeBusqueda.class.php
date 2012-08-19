<?php

class ServicioDeBusqueda {

	protected $busInmobiliaria;
	protected $busCodigo;
	protected $busSuperficie;
	protected $busCalle;
	protected $busNumero;
	protected $busTipoInmueble;
	protected $busAntiguedad;
	protected $busProvincia;
	protected $busPrecio;
	protected $busLocalidad;
	protected $busAmbientes;
	protected $busTipoPublicacion;

	public  $oPaginador;
	public  $aListado = array();
	public  $errores;

	
	
	public function __construct( ) {
		$this->oPaginador = new Paginador;
		$this->errores = "";
	}

	public function bringPropertiesInfo( $passedOrden, $passedPage, $idLogged, $mines = 0 ) {
		$psedOrd = sacarOrden( $passedOrden );
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql = "SELECT P.idPropiedad, I.idInmobiliaria, I.descripcion_inmobiliaria, P.codigoReferencia, TP.idTipoPropiedad, 
				TP.descripcion, P.ambientes, P.antiguedad, P.superficieCubierta, P.superficieSemiCubierta, P.idUsuario, ESP.descripcion_es,
				P.superficieDesCubierta, P.superficieTotal, P.calle, P.numero, P.piso, P.entreCalle1, P.entreCalle2, P.latitud, 
				P.departamento, P.CPA, P.aliasLocalidad, P.expensas, P.expensasExtraordinarias, P.fechaAlta, P.idEstado, P.longitud,
				L.idLocalidad, L.localidad, PR.idProvincia, PR.provincia, P.observaciones FROM Propiedades_Publicacion PP 
				INNER JOIN Propiedades P ON P.idPropiedad = PP.idPropiedad
				INNER JOIN EstadosPublicacion ESP ON P.idEstado = ESP.idEstadoPublicacion
				INNER JOIN Inmobiliarias I ON I.idInmobiliaria = P.idInmobiliaria
				INNER JOIN TiposPropiedad TP ON P.idTipoPropiedad = TP.idTipoPropiedad
				LEFT OUTER JOIN Localidades L ON P.idLocalidad = L.idLocalidad
				LEFT OUTER JOIN Provincias PR ON P.idProvincia = PR.idProvincia ";

		if (!$this->busInmobiliaria)
			$sql .= "WHERE P.idInmobiliaria != {$idLogged} AND P.idEstado = 1 ";
		else {
			if ($mines) 
				$sql .= "WHERE P.idInmobiliaria = {$this->busInmobiliaria} ";
			else 
				$sql .= "WHERE P.idInmobiliaria = {$this->busInmobiliaria} AND P.idEstado = 1 ";
		}
		if ($this->busCodigo)
			$sql .= "AND P.codigoReferencia LIKE '%{$this->busCodigo}%' ";
		if ($this->busTipoInmueble != 0)
			$sql .= "AND P.idTipoPropiedad = {$this->busTipoInmueble} ";
		if ($this->busProvincia != 0)
			$sql .= "AND P.idProvincia = {$this->busProvincia} ";
		if ($this->busLocalidad != 0)
			$sql .= "AND P.idLocalidad = {$this->busLocalidad} ";
		if ($this->busAmbientes)
			$sql .= "AND P.ambientes >= '{$this->busAmbientes}' ";
		if ($this->busSuperficie)
			$sql .= "AND P.superficieTotal >= '{$this->busSuperficie}' ";
		if ($this->busCalle)
			$sql .= "AND LOWER(P.calle) LIKE LOWER('%{$this->busCalle}%') ";
		if ($this->busNumero)
			$sql .= "AND P.numero LIKE '%{$this->busNumero}%' ";
		if ($this->busAntiguedad)
			$sql .= "AND P.antiguedad <= '{$this->busAntiguedad}' ";
		if ($this->busPrecio)
			$sql .= "AND PP.monto <= {$this->busPrecio} AND PP.monto != '' ";
		if ($this->busTipoPublicacion != 0)
			$sql .= "AND PP.idTipoPublicacion = {$this->busTipoPublicacion} AND PP.idEstadoPublicacion = 1 AND P.idEstado = 1 ";

		$sql .= "GROUP BY P.idPropiedad ORDER BY {$psedOrd} ";
		
		if ($this->oPaginador->getEstado() == 1) {
			$this->oPaginador->doPaginado( count($pinmo->getDb( )->Execute( $sql )), $passedPage );
			$sql .= "LIMIT ".$this->oPaginador->getLimitValue().",".$this->oPaginador->getLimit();
		}

		//echo "<p>" . $sql . "</p>"; //die();

		$res = $pinmo->getDb( )->Execute( $sql );

		if (is_array($res) && !empty($res)) {
			$cont = 0;
			foreach ($res as $row) {
				$oPropiedad = new Propiedad;
				$oPropiedad->setAllAttributesFromDB( $row, $mines );
				$this->setArrayMember( $cont, $oPropiedad );
				$cont++;
			}
			$pinmo->getDb( )->close();
			return true;
		}
		else {
			$this->errores = "<ul style=\"margin:0px;padding:5px;\"><li>No hay Propiedades que cumplan los criterios de su B&uacute;squeda</li></ul>";
			return false;
		}
	}


	// PUBLIC SETTERS

	public function setIdInmobiliaria( $var )		{ $this->busInmobiliaria = $var; }
	public function setCodigo( $var )				{ $this->busCodigo = $var; }
	public function setSuperficie( $var )			{ $this->busSuperficie = $var; }
	public function setCalle( $var )				{ $this->busCalle = $var; }
	public function setNumero( $var )				{ $this->busNumero = $var; }
	public function setTipoInmueble( $var )			{ $this->busTipoInmueble = $var; }
	public function setAntiguedad( $var )			{ $this->busAntiguedad = $var; }
	public function setProvincia( $var )			{ $this->busProvincia = $var; }
	public function setPrecio( $var )				{ $this->busPrecio = $var; }
	public function setLocalidad( $var )			{ $this->busLocalidad = $var; }
	public function setAmbientes( $var )			{ $this->busAmbientes = $var; }
	public function setTipoPublicacion( $var )		{ $this->busTipoPublicacion = $var; }
	public function setArrayMember( $pos, $valor )	{ $this->aListado[$pos] = $valor; }


	// PUBLIC GETTERS

	public function getArrayMember( $pos )	{ return $this->aListado[$pos]; }
	public function getListado( )			{ return $this->aListado; }
	public function getIdInmobiliaria( )	{ return $this->busInmobiliaria; }
	public function getCodigo( )			{ return $this->busCodigo; }
	public function getSuperficie( )		{ return $this->busSuperficie; }
	public function getCalle( )				{ return $this->busCalle; }
	public function getNumero( )			{ return $this->busNumero; }
	public function getTipoInmueble( )		{ return $this->busTipoInmueble; }
	public function getAntiguedad( )		{ return $this->busAntiguedad; }
	public function getProvincia( )			{ return $this->busProvincia; }
	public function getPrecio( )			{ return $this->busPrecio; }
	public function getLocalidad( )			{ return $this->busLocalidad; }
	public function getAmbientes( )			{ return $this->busAmbientes; }
	public function getTipoPublicacion( )	{ return $this->busTipoPublicacion; }
	public function getPaginador( )			{ return $this->oPaginador; }


} // Fin de Clase

?>