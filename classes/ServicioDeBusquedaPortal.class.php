<?php

class ServicioDeBusquedaPortal extends ServicioDeBusqueda {

	public $busSuperficie = array();
	public $busTipoInmueble = array();
	public $busAntiguedad = array();
	public $busProvincia = array();
	public $busPrecio = array();
	public $busLocalidad = array();
	public $busAmbientes = array();
	public $busTipoPublicacion = array();
	public $filtrosAplicados = 0;
	public $filtrosAplicables = 0;



	public function searchPropertiesForList( $orden, $pagina, $aPostArray ) {
		if ($this->validateParamsOnServerSide( $aPostArray )) {
			if ($this->bringPropertiesInfo( $orden, $pagina, 0, 1 ))
				return true;
			else
				return false;			
		}
		else {
			return false;
		}
	}

	public function setSearchParams( $postArray ) {
		//$this->busInmobiliaria = ($postArray["sp_cboInmobiliaria"]) ? intval($postArray["sp_cboInmobiliaria"]) : "P.idInmobiliaria";
		//$this->busCodigo = $postArray["sp_txtCodigoReferencia"];
		//$this->busCalle = addslashes(trim($postArray["sp_txtCalle"]));
		//$this->busNumero = addslashes(trim($postArray["sp_txtNumero"]));
		if (isset($postArray["sp_cboTipoPropiedad"])) {
			$this->busTipoInmueble["value"] = intval($postArray["sp_cboTipoPropiedad"]);
			$this->busTipoInmueble["display"] = strip_tags($postArray["sp_strTipoPropiedad"]);
			$this->filtrosAplicados++;
		}
		else
			$this->filtrosAplicables++;
		
		if (isset($postArray["sp_cboProvincia"])) {			
			$this->busProvincia["value"] = intval($postArray["sp_cboProvincia"]);
			$this->busProvincia["display"] = strip_tags($postArray["sp_strProvincia"]);
			$this->filtrosAplicados++;
		}
		else
			$this->filtrosAplicables++;
			
		if (isset($postArray["sp_cboLocalidad"])) {
			$this->busLocalidad["value"] = intval($postArray["sp_cboLocalidad"]);
			$this->busLocalidad["display"] = strip_tags($postArray["sp_strLocalidad"]);
			$this->filtrosAplicados++;
		}
		else
			$this->filtrosAplicables++;

		if (isset($postArray["sp_rdoTipoPublicacion"])) {			
			$this->setTipoPublicacion(intval($postArray["sp_rdoTipoPublicacion"]));
			$this->filtrosAplicados++;
		}
		else
			$this->filtrosAplicables++;

		if (isset($postArray["sp_txtAmbientes"])) {			
			$this->setAmbientes(addslashes(trim($postArray["sp_txtAmbientes"])));
			$this->filtrosAplicados++;
		}
		else
			$this->filtrosAplicables++;

		if (isset($postArray["sp_txtAntiguedad"])) {			
			$this->setAntiguedad(intval(trim($postArray["sp_txtAntiguedad"])));
			$this->filtrosAplicados++;
		}
		else
			$this->filtrosAplicables++;

		if (isset($postArray["sp_rdoPrecio"])) {
			$this->setPrecio( $postArray["sp_rdoPrecio"], strip_tags($postArray["sp_txtPrecioDesde"]), strip_tags($postArray["sp_txtPrecioHasta"]) );
			$this->filtrosAplicados++;
		}
		else
			$this->filtrosAplicables++;

		if (isset($postArray["sp_txtSuperficieTotal"])) {
			$this->setSuperficie($postArray["sp_txtSuperficieTotal"], strip_tags(trim($postArray["sp_txtSupExacta"])));
			$this->filtrosAplicados++;
		}
		else
			$this->filtrosAplicables++;		
	}

	public function validateParamsOnServerSide( $postArray ) {
		$this->errores = "<ul style=\"margin:0px;padding:5px;\">";
		if (isset($postArray["sp_txtSupExacta"]) && $postArray["sp_txtSupExacta"] != "") {
			if (!ctype_digit($postArray["sp_txtSupExacta"])) {
				$this->errores .= "<ul style=\"margin:0px;padding:0px;list-style-type:disc;\"><li>La Superficie Exacta debe ser un NUMERO.</li></ul>";
			}
		}
		if ((isset($postArray["sp_txtPrecioDesde"]) && $postArray["sp_txtPrecioDesde"] != "") && (isset($postArray["sp_txtPrecioHasta"]) && $postArray["sp_txtPrecioHasta"] != "")) {
			if ( !ctype_digit($postArray["sp_txtPrecioDesde"]) || !ctype_digit($postArray["sp_txtPrecioHasta"]) ) {
				$this->errores .= "<ul style=\"margin:0px;padding:0px;list-style-type:disc;\"><li>El Rango de Precio debe ser un valor NUMERICO [Sin signo $, ni puntos o comas].</li></ul>";
			}
		}
		if (stristr($this->errores, "<li>") !== false) {
			$this->errores .= "</ul>";
			return false;
		}
		else {
			$this->setSearchParams( $postArray );
			return true;
		}
	}

	public function bringPropertiesInfo( $passedOrden, $passedPage, $idPropiedad = 0, $allInfo = 0 ) {
		$psedOrd = sacarOrden( $passedOrden );
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );
		$sql = "SELECT P.idPropiedad, P.idUsuario, I.idInmobiliaria, I.descripcion_inmobiliaria, P.codigoReferencia, TP.idTipoPropiedad, 
				TP.descripcion, P.ambientes, P.antiguedad, P.superficieCubierta, P.superficieSemiCubierta, P.latitud, P.longitud,
				P.superficieDesCubierta, P.superficieTotal, P.calle, P.numero, P.piso, P.entreCalle1, P.entreCalle2, P.idEstado,
				P.departamento, P.CPA, P.aliasLocalidad, P.expensas, P.expensasExtraordinarias, P.fechaAlta,
				L.idLocalidad, L.localidad, PR.idProvincia, PR.provincia, P.observaciones FROM Propiedades P 
				INNER JOIN Propiedades_Publicacion PP ON P.idPropiedad = PP.idPropiedad
				INNER JOIN Inmobiliarias I ON I.idInmobiliaria = P.idInmobiliaria
				INNER JOIN TiposPropiedad TP ON P.idTipoPropiedad = TP.idTipoPropiedad
				LEFT OUTER JOIN Localidades L ON P.idLocalidad = L.idLocalidad
				LEFT OUTER JOIN Provincias PR ON P.idProvincia = PR.idProvincia
				WHERE P.idEstado = 1 AND PP.idEstadoPublicacion = 1 AND PP.monto != '' ";

		if ($idPropiedad) {
			$sql .= "AND P.idPropiedad = {$idPropiedad} ";
		}
		else {
			if ($this->busTipoInmueble["value"])
				$sql .= "AND P.idTipoPropiedad = {$this->busTipoInmueble['value']} ";
			if ($this->busProvincia["value"])
				$sql .= "AND P.idProvincia = {$this->busProvincia['value']} ";
			if ($this->busLocalidad["value"])
				$sql .= "AND P.idLocalidad = {$this->busLocalidad['value']} ";					
			if ($this->busAmbientes["value"])
				if(ctype_digit($this->busAmbientes["value"])) 
					$sql .= "AND P.ambientes = '{$this->busAmbientes['value']}' ";
				else
					$sql .= "AND P.ambientes > '6' ";
			
			if ($this->busSuperficie["urlFlg"])
				if($this->busSuperficie["minLimit"] || $this->busSuperficie["maxLimit"]) {
					if($this->busSuperficie["minLimit"]) 
						$sql .= "AND P.superficieTotal >= '{$this->busSuperficie['minLimit']}' ";
					if($this->busSuperficie["maxLimit"]) 
						$sql .= "AND P.superficieTotal <= '{$this->busSuperficie['maxLimit']}' ";
				}
				else
					$sql .= "AND P.superficieTotal = '{$this->busSuperficie['value']}' ";
			
			if ($this->busAntiguedad["urlFlg"])
				if($this->busAntiguedad["minLimit"] || $this->busAntiguedad["maxLimit"]) {
					if($this->busAntiguedad["minLimit"]) 
						$sql .= "AND P.antiguedad >= '{$this->busAntiguedad['minLimit']}' ";
					if($this->busAntiguedad["maxLimit"]) 
						$sql .= "AND P.antiguedad <= '{$this->busAntiguedad['maxLimit']}' ";
				}
				else
					$sql .= "AND P.antiguedad = '{$this->busAntiguedad['value']}' ";
			
			if ($this->busPrecio["urlFlg"] != '')
				if ($this->busPrecio["minLimit"]) 
					$sql .= "AND PP.monto >= {$this->busPrecio['minLimit']} ";
				if ($this->busPrecio["maxLimit"]) 
					$sql .= "AND PP.monto <= {$this->busPrecio['maxLimit']} ";
						
			if ($this->busTipoPublicacion["value"])
				$sql .= "AND PP.idTipoPublicacion = {$this->busTipoPublicacion['value']} ";

		}

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
				$oPropiedad->setAllAttributesFromDB( $row, $allInfo );
				$oPropiedad->getAssociatedItemsFromDB();
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

	static public function getPropiedadesDestacadas() {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );
		$sql = "SELECT DISTINCT P.idPropiedad, P.idUsuario, I.idInmobiliaria, I.descripcion_inmobiliaria, P.codigoReferencia, TP.idTipoPropiedad, 
				TP.descripcion, P.ambientes, P.antiguedad, P.superficieCubierta, P.superficieSemiCubierta, P.latitud, P.longitud,
				P.superficieDesCubierta, P.superficieTotal, P.calle, P.numero, P.piso, P.entreCalle1, P.entreCalle2, P.idEstado,
				P.departamento, P.CPA, P.aliasLocalidad, P.expensas, P.expensasExtraordinarias, P.fechaAlta,
				L.idLocalidad, L.localidad, PR.idProvincia, PR.provincia, P.observaciones FROM Propiedades P 
				INNER JOIN Propiedades_Publicacion PP ON P.idPropiedad = PP.idPropiedad
				INNER JOIN Inmobiliarias I ON I.idInmobiliaria = P.idInmobiliaria
				INNER JOIN TiposPropiedad TP ON P.idTipoPropiedad = TP.idTipoPropiedad
				LEFT OUTER JOIN Localidades L ON P.idLocalidad = L.idLocalidad
				LEFT OUTER JOIN Provincias PR ON P.idProvincia = PR.idProvincia
				WHERE P.idEstado = 1 AND PP.idEstadoPublicacion = 1 AND PP.monto != '' AND destacada = 1 ORDER BY RAND() LIMIT 0,4 ";

		$res = $pinmo->getDb( )->Execute( $sql );
		$array = array();
		if (is_array($res) && !empty($res)) {
			foreach ($res as $row) {
				$oPropiedad = new Propiedad;
				$oPropiedad->setAllAttributesFromDB( $row );
				$array[] = $oPropiedad;
			}
			$pinmo->getDb( )->close();
		}
		return $array;
	}

	static public function getPropiedadesMasVistas() {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );
		$sql  = "SELECT LW.idPropiedad, P.idUsuario, I.idInmobiliaria, I.descripcion_inmobiliaria, P.codigoReferencia, TP.idTipoPropiedad, 
				TP.descripcion, P.ambientes, P.antiguedad, P.superficieCubierta, P.superficieSemiCubierta, P.latitud, P.longitud,
				P.superficieDesCubierta, P.superficieTotal, P.calle, P.numero, P.piso, P.entreCalle1, P.entreCalle2, P.idEstado,
				P.departamento, P.CPA, P.aliasLocalidad, P.expensas, P.expensasExtraordinarias, P.fechaAlta,
				L.idLocalidad, L.localidad, PR.idProvincia, PR.provincia, P.observaciones FROM LogsVisitaWebs LW NATURAL JOIN Propiedades P
				INNER JOIN Propiedades_Publicacion PP ON P.idPropiedad = PP.idPropiedad
				INNER JOIN Inmobiliarias I ON I.idInmobiliaria = P.idInmobiliaria
				INNER JOIN TiposPropiedad TP ON P.idTipoPropiedad = TP.idTipoPropiedad
				LEFT OUTER JOIN Localidades L ON P.idLocalidad = L.idLocalidad
				LEFT OUTER JOIN Provincias PR ON P.idProvincia = PR.idProvincia
				WHERE fecha > DATE_SUB( NOW(), INTERVAL 7 DAY) GROUP BY idPropiedad
				HAVING COUNT(fecha) > 0 ORDER BY COUNT(fecha) DESC LIMIT 0,4 ";	
		
		$res = $pinmo->getDb( )->Execute( $sql );
		$array = array();
		if (is_array($res) && !empty($res)) {
			foreach ($res as $row) {
				$oPropiedad = new Propiedad;
				$oPropiedad->setAllAttributesFromDB( $row );
				$array[] = $oPropiedad;
			}
			$pinmo->getDb( )->close();
		}
		return $array;
	}

	// PUBLIC SETTERS

	public function setStrTipoInmueble( $var )		{ $this->busStrTipoInmueble = $var; }
	public function setStrProvincia( $var )			{ $this->busStrProvincia = $var; }
	public function setStrTipoPublicacion( $var )	{ $this->busStrTipoPublicacion = $var; }
	public function setStrLocalidad( $var )			{ $this->busStrLocalidad = $var; }
	public function setPrecioDesde( $var )			{ $this->busPrecioDesde = $var; }
	public function setPrecioHasta( $var )			{ $this->busPrecioHasta = $var; }
	public function setSuperficieDesde( $var )		{ $this->busSuperficieDesde = $var; }
	public function setSuperficieHasta( $var )		{ $this->busSuperficieHasta = $var; }
	public function setAntiguedadDesde( $var )		{ $this->busAntiguedadDesde = $var; }
	public function setAntiguedadHasta( $var )		{ $this->busAntiguedadHasta = $var; }
	
	public function setTipoPublicacion( $var ) { 
		$this->busTipoPublicacion["value"] = $var;
		switch ($var) {
			case '2':
				$this->busTipoPublicacion["display"] = "Alquiler";
			break;

			case '3':
				$this->busTipoPublicacion["display"] = "Alquiler Temp.";
			break;
		
			default :
				$this->busTipoPublicacion["display"] = "Venta";
			break;
		}
	}
	
	public function setAntiguedad( $var ) {
		$this->busAntiguedad["urlFlg"] = $var;
		switch($var) {
			case '2':
				$this->busAntiguedad["maxLimit"] = 10; $this->busAntiguedad["display"] = "Hasta 10 A&ntilde;os";
			break;

			case '3':
				$this->busAntiguedad["minLimit"] = 11; $this->busAntiguedad["maxLimit"] = 25; $this->busAntiguedad["display"] = "De 11 a 25 A&ntilde;os";
			break;

			case '4':
				$this->busAntiguedad["minLimit"] = 26; $this->busAntiguedad["maxLimit"] = 40; $this->busAntiguedad["display"] = "De 26 a 40 A&ntilde;os";
			break;

			case '5':
				$this->busAntiguedad["minLimit"] = 41; $this->busAntiguedad["display"] = "Mas de 40 A&ntilde;os";
			break;

			default:
				$this->busAntiguedad["value"] = 0;  $this->busAntiguedad["display"] = "A Estrenar";
			break;
		} 
	}

	public function setSuperficie( $flg, $val = 0 ) { 
		$this->busSuperficie["urlFlg"] = $flg;
		switch ($flg) {
			case '1':
				$this->busSuperficie["maxLimit"] = 50; $this->busSuperficie["display"] = "Hasta 50 M2";
			break;

			case '2':
				$this->busSuperficie["minLimit"] = 51; $this->busSuperficie["maxLimit"] = 100; $this->busSuperficie["display"] = "Desde 50 a 100 M2";
			break;

			case '3':
				$this->busSuperficie["minLimit"] = 101; $this->busSuperficie["maxLimit"] = 300; $this->busSuperficie["display"] = "Desde 100 a 300 M2";
			break;

			case '4':
				$this->busSuperficie["minLimit"] = 301; $this->busSuperficie["display"] = "Mas de 300 M2";
			break;
		
			case 'vga_243' :
				$this->busSuperficie["value"] = $val; $this->busSuperficie["display"] = "Exactamente de {$val} M2";
			break;
		}		
	}

	public function setPrecio( $flg, $fr = 0, $to = 0 ) { 
		$this->busPrecio["urlFlg"] = $flg; 
		switch ($flg) {
			case '1':
				$this->busPrecio["minLimit"] = 0; $this->busPrecio["maxLimit"] = 50000; $this->busPrecio["display"] = "Hasta U\$S 50000";
			break;

			case '2':
				$this->busPrecio["minLimit"] = 50001; $this->busPrecio["maxLimit"] = 100000; $this->busPrecio["display"] = "Dde. U\$S 50001 Hasta U\$S 100000";
			break;

			case '3':
				$this->busPrecio["minLimit"] = 100001; $this->busPrecio["maxLimit"] = 150000; $this->busPrecio["display"] = "Dde. U\$S 100001 Hasta U\$S 150000";
			break;

			case '4':
				$this->busPrecio["minLimit"] = 150001; $this->busPrecio["display"] = "Dde. U\$S 150001";
			break;

			default :
				$this->busPrecio["minLimit"] = ($fr) ? $fr : 0;
				$this->busPrecio["maxLimit"] = ($to) ? $to : 0;
				$this->busPrecio["display"] = "Dde. U\$S" . $fr . " Hasta U\$S " . $to;
			break;
		}	
	}

	public function setAmbientes( $var ) {
		$this->busAmbientes["value"] = $var;
		switch ($var) {
			case "1":
				$this->busAmbientes["display"] = "1 Ambiente";
			break;
			
			case "2":
				$this->busAmbientes["display"] = "2 Ambientes";
			break;	
			
			case "3":
				$this->busAmbientes["display"] = "3 Ambientes";
			break;	
			
			case "4":
				$this->busAmbientes["display"] = "4 Ambientes";
			break;	
			
			case "5":
				$this->busAmbientes["display"] = "5 Ambientes";
			break;	
			
			case "6":
				$this->busAmbientes["display"] = "6 Ambientes";
			break;	
			
			default:
				$this->busAmbientes["display"] = "Mas de 6 Ambientes";
			break;				
		}
	}

} // Fin de Clase

?>