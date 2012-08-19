<?php

class Propiedad {

	private $idPropiedad = 0;
	private $idTipoPropiedad;
	private $tipoPropiedad;
	private $codigoReferencia;
	private $fechaAlta;
	private $idUsuario;
	private $cantAmbientes;
	private $antiguedad;
	private $estadoPublicacion = 5;
	private $superficieCubierta;
	private $superficieSemiCubierta; 	
	private $superficieDesCubierta; 	
	private $superficieTotal;
	private $observaciones;
	private $expensas;
	private $expensasExtraordinarias;
	private $emprendimientoAsociado = 0;
	public  $error = "<ul style=\"margin:0px;padding:5px;\">";

	public  $ubicacion;
	public  $inmobiliaria;

	// Arrays de Objetos

	public  $aPublicaciones = array();		// Coleccion de catgorias en que estara publicada la Propiedad
	public  $aImagenes		= array();		// Coleccion de imagenes de la Propiedad
	public  $aItems			= array();		// Coleccion de Items de propiedad
	public  $aAmbientes		= array();		// Coleccion de Ambientes
	public  $aReservas		= array();		// Coleccion de Reservas en caso que la Propiedad este disponible como Alquiler Temporario


	
	public function __construct( ) {
		$this->ubicacion = new Ubicacion;
		$this->inmobiliaria = new Inmobiliaria;
	}

	public static function getTiposPropiedadAsOptionArray( $json = 0 ) {
		$pinmo = Pinmo::getInstance();
		$ti = $pinmo->getDb( )->ExecuteTable("TiposPropiedad", NULL, "descripcion");

		$response = "[{\"id\": \"\", \"nombre\": \"Seleccione Tipo de Prop.\"},";
		$arreglo['output'][] = "Seleccione Tipo de Prop.";
		$arreglo['values'][] = "";
		
		foreach ($ti as $t) {
			$arreglo['output'][] = ucwords(mb_strtolower($t['descripcion'], "UTF8"));
			$arreglo['values'][] = $t['idTipoPropiedad'];
			$response .= "{\"id\": \"".$t['idTipoPropiedad']."\", \"nombre\": \"".ucwords(mb_strtolower($t['descripcion'], "UTF8"))."\"},";
		}
		
		$response = substr($response, 0, strlen($response) - 1);
		$response .= "]";

		if ($json)
			return $response;
		else
			return $arreglo;

		$pinmo->getDb( )->close( );
	}
	
	public static function getEmprendimientosAsOptionArray( $json = 0 ) {
		$pinmo = Pinmo::getInstance();
		$ti = $pinmo->getDb( )->ExecuteTable("Propiedades", "esEmprendimiento = 1", NULL);

		$response = "[{\"id\": \"\", \"nombre\": \"Seleccione Emprendimiento.\"},";
		$arreglo['output'][] = "Seleccione Emprendimiento";
		$arreglo['values'][] = "";
		
		foreach ($ti as $t) {
			$arreglo['output'][] = ucwords(mb_strtolower($t['calle'], "UTF8")) ." - ". $t['numero'];
			$arreglo['values'][] = $t['idPropiedad'];
			$response .= "{\"id\": \"" . $t['idPropiedad'] . "\", \"nombre\": \"".ucwords(mb_strtolower($t['calle'], "UTF8")) ." - ". $t['numero'] . "\"},";
		}
		
		$response = substr($response, 0, strlen($response) - 1);
		$response .= "]";

		if ($json)
			return $response;
		else
			return $arreglo;

		$pinmo->getDb( )->close( );
	}

	public static function getPropertyToDisplay( $idProp ) {
		$aSearcher = new ServicioDeBusquedaPortal;
		if ($aSearcher->bringPropertiesInfo( 0, 0, $idProp, 1 )) {
			$aSearcher->getArrayMember(0)->getAssociatedAmbientesFromDB();
			$aSearcher->getArrayMember(0)->getAssociatedItemsFromDB();
			return clone $aSearcher->getArrayMember(0);
		}
		else {
			return new self();	
		}
	}
	
	public static function deletePropiedadFromDDBB( $idPropiedad ) {
		Pinmo::getInstance()->getDb( )->open( );
		Pinmo::getInstance()->getDb( )->startTransaction();
		if( !Item::eliminarItemsDePropiedad($idPropiedad) || !Mensaje::eliminarMensajesDePropiedad($idPropiedad) || 
			!Ambiente::eliminarAmbientesDePropiedad($idPropiedad) || !Reserva::eliminarReservasDePropiedad($idPropiedad) ||
			!Publicacion::eliminarPublicacionesDePropiedad($idPropiedad) || !Imagen::eliminarImagenesDePropiedad($idPropiedad) ||
			!self::eliminarPropiedad($idPropiedad) ) {
			Pinmo::getInstance()->getDb( )->rollbackTransaction();
			return false;
		}
		else {
			Pinmo::getInstance()->getDb( )->commitTransaction();
			Pinmo::getInstance()->getDb( )->close( );
			return true;
		}
	}
	
	private static function eliminarPropiedad($idPropiedad) {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql = "DELETE FROM Propiedades WHERE idPropiedad = {$idPropiedad}";

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );			
	}

	public function setAttributes( $post ) {
		if (!$this->validateParamsOnServerSide( $post )) return false;
		$this->setIdTipoPropiedad(intval($post['cboTipoPropiedad_ABM']));
		$this->setEmprendimientoAsociado(intval($post['cboEmprendimiento_ABM']));		
		$this->setCodigoReferencia(strtoupper(strip_tags($post['txtCodRef_ABM'])));
		$this->setCantAmbientes(strip_tags($post['txtAmb_ABM']));
		$this->setAntiguedad(strip_tags($post['txtAntiguedad_ABM']));
		$this->setObservaciones(trim(addslashes($post['txtObservaciones_ABM'])));
		$this->setExpensas(str_replace(",", ".", trim(strip_tags($post['txtExpensas_ABM']))));
		$this->setSuperficieTotal(str_replace(",", ".", trim(strip_tags($post['txtSupTotal_ABM']))));
		$this->setSuperficieCubierta(str_replace(",", ".", trim(strip_tags($post['txtSupCub_ABM']))));
		$this->setSuperficieSemiCubierta(str_replace(",", ".", trim(strip_tags($post['txtSupSemiCub_ABM'])))); 
		$this->setSuperficieDesCubierta(str_replace(",", ".", trim(strip_tags($post['txtSupDesCub_ABM'])))); 
		$this->setExpensasExtraordinarias(str_replace(",", ".", trim(strip_tags($post['txtExpExtra_ABM']))));
		$this->getUbicacion( )->setCalle(trim(strip_tags($post['txtCalle_ABM'])));
		$this->getUbicacion( )->setNumero(strip_tags($post['txtNumero_ABM']));
		$this->getUbicacion( )->setPiso(strip_tags($post['txtPiso_ABM']));
		$this->getUbicacion( )->setDepartamento(trim(strip_tags($post['txtDepartamento_ABM'])));
		$this->getUbicacion( )->setEntreCalle1(trim(strip_tags($post['txtEntreCalle1_ABM'])));
		$this->getUbicacion( )->setEntreCalle2(trim(strip_tags($post['txtEntreCalle2_ABM'])));
		$this->getUbicacion( )->setIdProvincia(intval($post['cboProvincia_ABM']));
		$this->getUbicacion( )->setIdLocalidad(intval($post['cboLocalidad_ABM']));
		$this->getUbicacion( )->setAliasLocalidad(trim(strip_tags($post['txtAliasLoc_ABM'])));
		$this->getUbicacion( )->setLatitud(trim($post['lat_ABM']));
		$this->getUbicacion( )->setLongitud(trim($post['lon_ABM']));

		$this->setPublicaciones( $post['thePublicaciones_ABM'] );
		$this->setItems( $post['chkCaracteristica_ABM'], $post['rdoCaracteristica_ABM'] );
		$this->setAmbientes( $post['theAmbientes_ABM'] );

		return true;
	}

	public function setAllAttributesFromDB( $recordSet, $all = 0 ) {
		$this->setIdPropiedad( $recordSet["idPropiedad"] );
		$this->setFechaAlta( $recordSet["fechaAlta"] );
		$this->setEstadoPublicacion( $recordSet["idEstado"] );
		$this->getInmobiliaria( )->setNombre( $recordSet["descripcion_inmobiliaria"] );
		$this->setTipoPropiedad( $recordSet["descripcion"] );
		$this->setCantAmbientes( $recordSet['ambientes'] );
		$this->getUbicacion( )->setNomLocalidad( $recordSet["localidad"] );
		$this->getUbicacion( )->setNomProvincia( $recordSet["provincia"] );
		$this->getUbicacion( )->setCalle( str_replace(array("á","é","í","ó","ú","ñ"), array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;"), $recordSet["calle"]) );
		$this->getUbicacion( )->setNumero( $recordSet["numero"] );
		$this->getUbicacion( )->setPiso( $recordSet["piso"] );
		$this->getUbicacion( )->setDepartamento( $recordSet["departamento"] );
		$this->getUbicacion( )->setLatitud( $recordSet["latitud"] );
		$this->getUbicacion( )->setLongitud( $recordSet["longitud"] );
		if ($all) {
			$this->setCodigoReferencia( $recordSet["codigoReferencia"] );
			$this->getInmobiliaria( )->setIdInmobiliaria( $recordSet["idInmobiliaria"] );
			$this->setIdTipoPropiedad( $recordSet['idTipoPropiedad'] );
			$this->setIdUsuario( $recordSet['idUsuario'] );
			$this->setAntiguedad( $recordSet['antiguedad'] );
			$this->setSuperficieSemiCubierta( $recordSet['superficieSemiCubierta'] );
			$this->setSuperficieCubierta( $recordSet['superficieCubierta'] );
			$this->setSuperficieDesCubierta( $recordSet['superficieDesCubierta'] );
			$this->setSuperficieTotal( $recordSet['superficieTotal'] );
			$this->setExpensas( $recordSet['expensas'] );
			$this->setExpensasExtraordinarias( $recordSet['expensasExtraordinarias'] );
			$this->setObservaciones( stripslashes($recordSet['observaciones']) );
			$this->getUbicacion( )->setIdLocalidad( $recordSet["idLocalidad"] );
			$this->getUbicacion( )->setIdProvincia( $recordSet["idProvincia"] );
			$this->getUbicacion( )->setEntreCalle1( str_replace(array("á","é","í","ó","ú","ñ"), array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;"), $recordSet['entreCalle1']) );
			$this->getUbicacion( )->setEntreCalle2( str_replace(array("á","é","í","ó","ú","ñ"), array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;"), $recordSet['entreCalle2']) );
			$this->getUbicacion( )->setCPA( $recordSet['CPA'] );
			$this->getUbicacion( )->setAliasLocalidad( $recordSet['aliasLocalidad'] );
		}
		$this->getAssociatedImagesFromDB( $all );
		$this->getAssociatedPublicacionesFromDB( $all );
		
		return true;
	} 


	// PUBLIC COLLABORATIVES SETTERS

	public function setPublicaciones( $lasPublicaciones ) {
		$this->aPublicaciones = NULL;
		foreach ($lasPublicaciones as $k => $p) {
			if ($p["idEstPub"] != 7)
				$this->setEstadoPublicacion( 1 );	
			$p['monto'] = str_replace(".", "", trim(strip_tags($p['monto'])));
			$p['monto'] = str_replace(",", "", trim(strip_tags($p['monto'])));
			$p['comi'] = str_replace(",", ".", trim(strip_tags($p['comi'])));
			$this->aPublicaciones[] = Publicacion::createPublicacion($p['id'], $k, $p['idMoneda'], '', $p['monto'], $p['idEstPub'], '', $p['idEstExcl'], $p['comi']);
		}
		return true;
	}

	public function setItems( $carChecks, $carRadios ) {
		$this->aItems = NULL;
		if(isset($carChecks)){
			foreach ($carChecks as $idItem){
				$this->aItems[] = Item::createItem($idItem);
			}
		}
		if(isset($carRadios)){
			foreach ($carRadios as $idItem){
				$this->aItems[] = Item::createItem($idItem);
			}
		}
		return true;
	}

	public function setAmbientes( $losAmbientes ) {
		$this->aAmbientes = NULL;
		if (isset($losAmbientes)) {
			foreach ($losAmbientes as $a){
				if (is_numeric($a['t'])) {
					$a['a'] = str_replace(",", ".", trim(strip_tags($a['a'])));
					$a['l'] = str_replace(",", ".", trim(strip_tags($a['l'])));
					$this->aAmbientes[] = Ambiente::createAmbiente($a['t'], '', $a['a'], $a['l']);
				}
			}
		}
		return true;
	}
	

	// PUBLIC COLLABORATIVES GETTERS 

	public function getAssociatedImagesFromDB( $flg ) {
		$this->aImagenes = NULL;
		if (count($this->aImagenes = Imagen::getPropAssociatedImagesFromDB( $this->idPropiedad, $flg )) > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	public function getAssociatedPublicacionesFromDB( $f = 0 ) {
		$this->aPublicaciones = NULL;
		if (count($this->aPublicaciones = Publicacion::getPropAssociatedPublicacionesFromDB( $this->idPropiedad, $f )) > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	public function getAssociatedItemsFromDB( $edFlag = 0 ) {
		$this->aItems = NULL;
		if (count($this->aItems = Item::getPropAssociatedItemsFromDB( $this->idPropiedad, $edFlag )) > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	public function getAssociatedAmbientesFromDB( ) {
		$this->aAmbientes = NULL;
		if (count($this->aAmbientes = Ambiente::getPropAssociatedAmbientesFromDB( $this->idPropiedad )) > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	public function getAssociatedReservasFromDB( $all = 0 ) {
		$this->aReservas = NULL;
		if (count($this->aReservas = Reserva::getPropAssociatedReservasFromDB( $this->idPropiedad, $all )) > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	public function updateEstadoDePropiedad( $newState ) {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		if ($newState == 1)
			if ($this->countPublicacionesNoPublicadas() == 3)
				return false;

		$sql = "UPDATE Propiedades SET idEstado = {$newState} WHERE idPropiedad = {$this->idPropiedad}";
		$result = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if (!$result)
			return false;

		$this->setEstadoPublicacion( $newState );
		return true;
		$pinmo->getDb( )->close( );
	}

	public function saveToDB( $flag = 0 ) {

		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		if ($flag) {
			$sql  = "UPDATE Propiedades SET idTipoPropiedad = '{$this->idTipoPropiedad}',codigoReferencia = '{$this->codigoReferencia}',";
			$sql .= "calle = '{$this->ubicacion->getCalle()}',numero = '{$this->ubicacion->getNumero()}',piso = '{$this->ubicacion->getPiso()}',";
			$sql .= "departamento = '{$this->ubicacion->getDepartamento()}',entreCalle1 = '{$this->ubicacion->getEntreCalle1()}',";
			$sql .= "entreCalle2 = '{$this->ubicacion->getEntreCalle2()}',idLocalidad = '{$this->ubicacion->getIdLocalidad()}',idUsuario = {$this->idUsuario},";
			$sql .= "idProvincia = '{$this->ubicacion->getIdProvincia()}',latitud = '{$this->ubicacion->getLatitud()}',longitud = '{$this->ubicacion->getLongitud()}',";
			$sql .= "superficieCubierta = {$this->superficieCubierta}, superficieSemiCubierta = {$this->superficieSemiCubierta},";
			$sql .= "superficieDesCubierta = {$this->superficieDesCubierta}, superficieTotal = {$this->superficieTotal}, ambientes = {$this->cantAmbientes},";
			$sql .= "expensas = {$this->expensas}, expensasExtraordinarias = {$this->expensasExtraordinarias}, idEstado = {$this->estadoPublicacion},";
			$sql .= "observaciones = '{$this->observaciones}',aliasLocalidad = '{$this->ubicacion->getAliasLocalidad()}',";
			$sql .= (is_numeric($this->antiguedad)) ? "antiguedad = {$this->antiguedad} " : "antiguedad = NULL ";
			$sql .= "WHERE idPropiedad = {$this->idPropiedad}";
		}
		else {
			$sql  = "INSERT INTO Propiedades(idTipoPropiedad,codigoReferencia,idInmobiliaria,idUsuario,calle,numero,piso,departamento,entreCalle1,entreCalle2,";
			$sql .= "idLocalidad,idProvincia,latitud,longitud,superficieCubierta,superficieSemiCubierta,superficieDesCubierta,superficieTotal,ambientes,";
			$sql .= "expensas,expensasExtraordinarias,antiguedad,observaciones,aliasLocalidad,idEstado) VALUES ('{$this->idTipoPropiedad}',";
			$sql .= "'{$this->codigoReferencia}','{$this->inmobiliaria->getIdInmobiliaria()}','{$this->idUsuario}','{$this->ubicacion->getCalle()}',";
			$sql .= "'{$this->ubicacion->getNumero()}','{$this->ubicacion->getPiso()}','{$this->ubicacion->getDepartamento()}','{$this->ubicacion->getEntreCalle1()}',";
			$sql .= "'{$this->ubicacion->getEntreCalle2()}','{$this->ubicacion->getIdLocalidad()}','{$this->ubicacion->getIdProvincia()}',";
			$sql .= "'{$this->ubicacion->getLatitud()}','{$this->ubicacion->getLongitud()}',{$this->superficieCubierta},{$this->superficieSemiCubierta},";
			$sql .= "{$this->superficieDesCubierta},{$this->superficieTotal},{$this->cantAmbientes},{$this->expensas},{$this->expensasExtraordinarias},";
			$sql .= (is_numeric($this->antiguedad)) ? "antiguedad = {$this->antiguedad}," : "antiguedad = NULL,";
			$sql .= "'{$this->observaciones}','{$this->ubicacion->getAliasLocalidad()}','{$this->estadoPublicacion}')";			
		}

		//echo("<div style='float:left; width:100%;'><pre style='text-align:left;'><font size='2'>" .$sql. "</font></pre></div>"); //die();

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) {
			// Aca preguntaria si emprendimientoAsociado != 0 llamar a un metodo que haga el grabado [insert/update] de dicha asociacion con el idPropiedad y este valor
			// Que seria el "id del Emprendimiento" [el idPropiedad] que viene del combo de "Emprendimiento Asociado" del ABM ...
			if (!$flag) $this->idPropiedad = mysql_insert_id();
			if (!$this->grabarPublicaciones())
				return false;
			if (!$this->grabarItems( $flag )) 
				return false;
			if (!$this->grabarAmbientes( $flag ))
				return false;
			else
				return true;
		}
		else {
			$this->error = "<li>Ha ocurrido un error en la grabacion de los Datos de la Propiedad</li>";
			return false;
		}

		$this->error .= "</ul>";
		$pinmo->getDb( )->close( );
	}

	public function saveReservasToDB() {
		$cont = 0;
		if (count($this->aReservas) > 0) {
			foreach ($this->aReservas as $res) {
				$r = $res->saveToDB( $this->idPropiedad );
				if ($r === false) {
					$this->error = "<li>Ha ocurrido un error en la grabacion de las Reservas</li>";
					return false;
				}
				elseif ($r === -1) {
					$cont++;
				}
			}
			if ($cont == count($this->aReservas)) {
				$this->error = "<li>Para Grabar debe Agregar y/o Quitar una Reserva</li>";
				return false;
			}
		}
		else {
			$this->error = "<li>No hay Reservas que Grabar y/o Quitar</li>";
			return false;
		}
		
		$this->error .= "</ul>";
		return true;
	}

	public function saveImagesToDB( $ordenPost ) {
		$cant = 0; $cant = count($this->aImagenes);
		if ( $cant > 0) {
			$offset = $this->countActiveImages($cant);
			if ($offset > 18) {
				$this->error .= "<li>S&oacute;lo se permiten hasta 18 Im&aacute;genes<br />Elimine ".($offset - 18)." y vuelva a Grabar</li>";
				return false;							
			}
			if (!is_null($ordenPost)) $this->reordenarImagenesArray( $ordenPost );
			for ($i=0; $i < $cant; $i++) {
				if (!$this->aImagenes[$i]->saveToDB( $this->idPropiedad )) {
					$this->error .= "<li>Ha Ocurrido un Error en la Grabacion de las Imagenes</li>";
					return false;
				}
			}
		}
		else {
			$this->error .= "<li>No hay Imagenes que Grabar y/o Quitar</li>";
			return false;			
		}
		$this->error .= "</ul>";
		return true;
	}

	private function countActiveImages( $k ) {
		$activeCant = 0;
		for ($i = 0; $i < $k; $i++) {
			if ($this->aImagenes[$i]->getMarca( ) == false)
				$activeCount++;
		}
		return $activeCount;
	}

	private function grabarAmbientes( $f = 0 ) {
		if ($f) Ambiente::eliminarAmbientesDePropiedad( $this->idPropiedad );
		if (count($this->aAmbientes) > 0) {
			foreach ($this->aAmbientes as $anAmbiente) {
				if (!$anAmbiente->guardarAmbiente( $this->idPropiedad )) {
					$this->error .= "<li>Ha ocurrido un error en la grabacion de los Ambientes</li>";
					return false;
				}			
			}
		}
		return true;
	}

	private function grabarItems( $f = 0 ) {
		if ($f) Item::eliminarItemsDePropiedad( $this->idPropiedad );
		if (count($this->aItems) > 0) {
			foreach ($this->aItems as $anItem) {
				if (!$anItem->guardarItem( $this->idPropiedad )) {
					$this->error .= "<li>Ha ocurrido un error en la grabacion de las Caracteristicas asignadas</li>";
					return false;
				}			
			}
		}
		return true;
	}

	private function grabarPublicaciones() {
		if (count($this->aPublicaciones) > 0) {
			foreach ($this->aPublicaciones as $aPublicacion) {
				if (!$aPublicacion->grabarPublicacion( $this->idPropiedad )) {
					$this->error .= "<li>Ha ocurrido un error en la grabacion de las Publicaciones asignadas</li>";
					return false;
				}
			}
		}
		return true;
	}

	private function countPublicacionesNoPublicadas() {
		$counter = 0;
		if (count($this->aPublicaciones) > 0) {
			foreach ($this->aPublicaciones as $aPublicacion) {
				if ($aPublicacion->getIdEstadoPublicacion( ) == 7)
					$counter++;
			}
		}
		return $counter;
	}

	private function reordenarImagenesArray( $ordenArray ) {
		foreach ($ordenArray as $orden => $position) {
			$this->aImagenes[$position]->setOrden($orden);
		}
		foreach ($this->aImagenes as $index => $imagen) {
			if ($imagen->getMarca() === true) {
				$this->pushImagenesArrayMemeber( $this->popImagenesArrayMemeber($index) );
			}
		}
	}

	private function popImagenesArrayMemeber( $pos ) {
		$popedArrayEls = array_splice($this->aImagenes, $pos, 1);
		return $popedArrayEls[0];
	}

	private function pushImagenesArrayMemeber( $object ) {
		return array_push($this->aImagenes, $object);
	}

	private function validateParamsOnServerSide( $postArray ) {
		$this->error = "<ul style=\"margin:0px;padding:5px;\">";
		if(empty($postArray['frmToken']) || $postArray['frmToken'] != session_id() ) {
			$this->error .= "<li>Ha Ocurrido un Error en la Validez de los Datos</li></ul>";
			return false;
		}
		if (isset($postArray["cboTipoPropiedad_ABM"])) { 
			if ($postArray["cboTipoPropiedad_ABM"] == "")
					$this->error .= "<li>El Tipo de Propiedad es Requerido</li>";
		}
		if (isset($postArray["cboProvincia_ABM"])) {
			if ($postArray["cboProvincia_ABM"] == "" || $postArray["cboProvincia_ABM"] == 0)
				$this->error .= "<li>La Provincia es Requerida para establecer el Domicilio</li>";
		}
		if (isset($postArray["cboLocalidad_ABM"])) { 
			if ($postArray["cboLocalidad_ABM"] == "" || $postArray["cboLocalidad_ABM"] == 0)
				$this->error .= "<li>La Localidad es Requerida para establecer el Domicilio</li>";
		}
		if (isset($postArray["txtNumero_ABM"])) {
			if ($postArray["txtNumero_ABM"] == "")
				$this->error .= "<li>El Numero de Calle es Requerido para establecer el Domicilio</li>";
			else 
				if (!is_numeric($postArray["txtNumero_ABM"]) || strpos($postArray["txtNumero_ABM"], ".") || strpos($postArray["txtNumero_ABM"], ",") || strlen($postArray["txtNumero_ABM"]) > 5)
				$this->error .= "<li>El Numero debe ser un valor NUMERICO [Sin puntos o comas] de NO mas de 5 Digitos</li>";
		}
		if (isset($postArray["txtCalle_ABM"])) {
			if ($postArray["txtCalle_ABM"] == "")
				$this->error .= "<li>La Calle es Requerida para establecer el Domicilio</li>";
		}
		if (!isset($postArray["chkDeAcuerdo_ABM"])) {
			$this->error .= "<li>Debe Aceptar que Verifica la Direccion ingresada</li>";
		}
		if (stristr($this->error, "<li>") !== false) {
			$this->error .= "</ul>";
			return false;
		}
		else {
			return true;
		}

	}

	public function registerPropertyView( $fromWhere, $dbObject ) {
		if ($this->idPropiedad) {
			$sql = "INSERT INTO LogsVisitaWebs(idPropiedad, ip) VALUES( '{$this->idPropiedad}','{$fromWhere}' )";
			
			$query = $dbObject->ExecuteNotSelection( $sql );

			if ($query) return true;
			else return false;
		}
		return;
	}

	// PUBLIC GETTERS

	public function getPublicacionesArrayMember( $pos ) { return $this->aPublicaciones[$pos]; }	
	public function getImagenesArrayMember( $pos )		{ return $this->aImagenes[$pos]; }
	public function getItemsArrayMember( $pos )			{ return $this->aItems[$pos]; }
	public function getAmbientesArrayMember( $pos )		{ return $this->aAmbientes[$pos]; }
	public function getReservasArrayMember( $pos )		{ return $this->aReservas[$pos]; }		
	public function getPublicaciones()					{ return $this->aPublicaciones; }	
	public function getImagenes()						{ return $this->aImagenes; }
	public function getCantImagenes()					{ return count($this->aImagenes); }
	public function getItems()							{ return $this->aItems; }
	public function getAmbientes()						{ return $this->aAmbientes; }
	public function getReservas()						{ return $this->aReservas; }
	public function getUbicacion()						{ return $this->ubicacion; }
	public function getIdPropiedad()					{ return $this->idPropiedad; }
	public function getIdTipoPropiedad()				{ return $this->idTipoPropiedad; }
	public function getTipoPropiedad()					{ return $this->tipoPropiedad; }
	public function getEmprendimientoAsociado()			{ return $this->emprendimientoAsociado; }
	public function getCodigoReferencia()				{ return $this->codigoReferencia; }
	public function getFechaAlta()						{ return $this->fechaAlta; }
	public function getIdUsuario()						{ return $this->idUsuario; }
	public function getInmobiliaria()					{ return $this->inmobiliaria; }
	public function getEstadoPublicacion()				{ return $this->estadoPublicacion; }
	public function getObservaciones()					{ return $this->observaciones; }
	public function getExpensas()						{ return ($this->expensas) ? $this->expensas : NULL; }
	public function getExpensasExtraordinarias()		{ return ($this->expensasExtraordinarias) ? $this->expensasExtraordinarias : NULL; }
	public function getSuperficieTotal()				{ return ($this->superficieTotal) ? $this->superficieTotal : NULL; }
	public function getSuperficieCubierta()				{ return ($this->superficieCubierta) ? $this->superficieCubierta : NULL; }
	public function getSuperficieSemiCubierta()			{ return ($this->superficieSemiCubierta) ? $this->superficieSemiCubierta : NULL; }
	public function getSuperficieDesCubierta()			{ return ($this->superficieDesCubierta) ? $this->superficieDesCubierta : NULL; }

	public function getAntiguedad( $abm = 0 ) {
		if ($abm)
			return ($this->antiguedad === 0 || $this->antiguedad > 0) ? $this->antiguedad : "";
		else
			return ($this->antiguedad === 0) ? "A Estrenar" : $this->antiguedad;
	}

	public function getCantAmbientes( $abm = 0 ) {
		if ($abm)
			return ($this->cantAmbientes) ? $this->cantAmbientes : NULL;
		else {
			if (strpos($this->cantAmbientes, ".") === false)
				return $this->cantAmbientes;
			else
				return floor($this->cantAmbientes)." &frac12;";
		}
	}

	public function getDomicilio()						{ return $this->ubicacion->getDomicilioFull(); }

	public function getParsedUrlFriendlyString() {
		$strToParse = "";
		$strToParse = utf8_decode($this->getTipoPropiedad()) . " en";
		if (count($this->getPublicaciones()) > 0) 
			foreach ($this->getPublicaciones() as $p) 
				if ($p->getIdEstadoPublicacion() != 7) 
					$strToParse .= " " . $p->getTipoPublicacion() . " ";
		if ($this->getCantAmbientes() > 0) 
			$strToParse .= "de " . $this->getCantAmbientes(). " Ambientes ";
		$strToParse .= utf8_decode($this->ubicacion->getCalle()) . " " . $this->ubicacion->getNumero(1) . " ";
		if (is_numeric($this->ubicacion->getPiso())) 
			$strToParse .= "Piso " . $this->ubicacion->getPiso() . " ";
		else 
			$strToParse .= $this->ubicacion->getPiso() . " ";
		$strToParse .= utf8_decode($this->ubicacion->getNomLocalidad());
		$strToParse .= " ";
		$strToParse .= utf8_decode($this->ubicacion->getNomProvincia());
		
		return str_replace(array(" ","á","é","í","ó","ú","ñ"), array("-","a","e","i","o","u","ni"), ereg_replace("[\,\(\)]", "", strtolower($strToParse)));
	}

	public function getDaysFromAlta() {
		return abs(getDaysLeft($this->fechaAlta, 1));
	}
	
	// PUBLIC SETTERS

	public function setPublicacionesArrayMember( $pos, $objeto ) { $this->aPublicaciones[$pos] = $objeto; }	
	public function setImagenesArrayMember( $pos, $objeto )		 { $this->aImagenes[$pos] = $objeto; }
	public function setItemsArrayMember( $pos, $objeto )		 { $this->aItems[$pos] = $objeto; }
	public function setAmbientesArrayMember( $pos, $objeto )	 { $this->aAmbientes[$pos] = $objeto; }
	public function setReservasArrayMember( $pos, $objeto )		 { $this->aReservas[$pos] = $objeto; }
	public function setUbicacion( $objeto )						 { $this->ubicacion = $objeto; }
	public function setIdPropiedad( $var )						 { $this->idPropiedad = $var; }
	public function setIdTipoPropiedad( $var )					 { $this->idTipoPropiedad = $var; }
	public function setTipoPropiedad( $var )					 { $this->tipoPropiedad = $var; }
	public function setEmprendimientoAsociado( $var )			 { $this->emprendimientoAsociado = $var; }
	public function setCodigoReferencia( $var )					 { $this->codigoReferencia = $var; }
	public function setFechaAlta( $var )						 { $this->fechaAlta = $var; }
	public function setInmobiliaria( $objeto )					 { $this->inmobiliaria = $objeto; }
	public function setIdUsuario( $var )						 { $this->idUsuario = $var; }
	public function setEstadoPublicacion( $var )				 { $this->estadoPublicacion = $var; }
	public function setCantAmbientes( $var )					 { $this->cantAmbientes = (is_numeric($var)) ? $var : 0; }
	public function setAntiguedad( $var )						 { $this->antiguedad = (is_numeric($var)) ? intval($var) : ''; }
	public function setSuperficieTotal( $var )					 { $this->superficieTotal = (is_numeric($var)) ? $var : 0; }
	public function setSuperficieCubierta( $var )				 { $this->superficieCubierta = (is_numeric($var)) ? $var : 0; }
	public function setSuperficieSemiCubierta( $var )			 { $this->superficieSemiCubierta = (is_numeric($var)) ? $var : 0; }
	public function setSuperficieDesCubierta( $var )			 { $this->superficieDesCubierta = (is_numeric($var)) ? $var : 0; }
	public function setExpensas( $var )							 { $this->expensas = (is_numeric($var)) ? $var : 0; }
	public function setExpensasExtraordinarias( $var )			 { $this->expensasExtraordinarias = (is_numeric($var)) ? $var : 0; }	
	public function setObservaciones( $var )					 { $this->observaciones = $var; }


} // Fin de Clase

?>