<?php

class Publicacion {

	private $idTipoPublicacion;
	private $sTipoPublicacion;
	private $idTipoMoneda;
	private $simboloMoneda;
	private $monto;
	private $idEstadoPublicacion;
	private $sEstadoPublicacion;
	private $comision;
	
	/* Define por que inmobiliarias y paginas sera posible ver cada publicacion */
	private $idEstadoExclusividad;



	public static function createPublicacion($idTP, $sTP, $idTM, $sTM, $m, $idEP, $sEP, $idEE, $c = '') {
		$aPublicacion = new self;
		$aPublicacion->idTipoPublicacion = $idTP;
		$aPublicacion->sTipoPublicacion = $sTP;
		$aPublicacion->idTipoMoneda = $idTM;
		$aPublicacion->simboloMoneda = $sTM;
		$aPublicacion->monto = $m;
		$aPublicacion->idEstadoPublicacion = $idEP;
		$aPublicacion->sEstadoPublicacion = $sEP;
		$aPublicacion->idEstadoExclusividad = $idEE;
		$aPublicacion->comision = $c;

		return $aPublicacion;
	}

	public static function getPublicacionesFromDBToDisplay( ) {
		$aPublicaciones = array();
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql  = "SELECT TP.idTipoPublicacion, TP.descripcion AS 'desPub', TP.idTipoMonedaPre, TM.descripcion, TM.simbolo ";
		$sql .= "FROM TiposPublicacion TP INNER JOIN TiposMoneda TM ON TM.idTipoMoneda = TP.idTipoMonedaPre ";
		$sql .= "ORDER BY TP.idTipoPublicacion";
		
		$result = $pinmo->getDb( )->Execute( $sql );

		if (is_array($result) && !empty($result)) {
			foreach ($result as $row) {
				$aPublicaciones[] = self::createPublicacion($row['idTipoPublicacion'],$row['desPub'],$row['idTipoMonedaPre'],'','','7','','1');
			}
		}
		return $aPublicaciones;
		
		$pinmo->getDb( )->close( );
	}

	public static function getPropAssociatedPublicacionesFromDB( $idPropiedad, $all = 0 ) {
		$aPublicaciones = array();
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql  = "SELECT T.descripcion AS 'desPub', P.idTipoPublicacion, EP.descripcion AS 'desEstado', P.idEstadoPublicacion, ";
		$sql .= "P.idEstadoExclusividad, P.idTipoMoneda, M.simbolo, P.monto, P.comision FROM TiposPublicacion T ";
		$sql .= "LEFT OUTER JOIN Propiedades_Publicacion P ON P.idTipoPublicacion = T.idTipoPublicacion ";
		$sql .= "INNER JOIN EstadosPublicacion EP ON P.idEstadoPublicacion = EP.idEstadoPublicacion ";
		$sql .= "INNER JOIN TiposMoneda M ON M.idTipoMoneda = P.idTipoMoneda WHERE P.idPropiedad = {$idPropiedad}";
		
		$result = $pinmo->getDb( )->Execute( $sql );

		if (is_array($result) && !empty($result)) {
			foreach ($result as $row) {
				if (!$all) {
					if ($row['monto'] != '' && $row['monto'] != 'No Ingresado' && $row['idEstadoPublicacion'] == 1) {
						$aPublicaciones[] = self::createPublicacion($row['idTipoPublicacion'],$row['desPub'],$row['idTipoMoneda'],$row['simbolo'],$row['monto'],$row['idEstadoPublicacion'],$row['desEstado'],$row['idEstadoExclusividad'],$row['comision']);
						break;	
					}
				}
				else {
					$aPublicaciones[] = self::createPublicacion($row['idTipoPublicacion'],$row['desPub'],$row['idTipoMoneda'],$row['simbolo'],$row['monto'],$row['idEstadoPublicacion'],$row['desEstado'],$row['idEstadoExclusividad'],$row['comision']);	
				}
			}
		}
		return $aPublicaciones;
		
		$pinmo->getDb( )->close( );
	}

	public static function getTiposPublicacionAsOptionArray( $json = 0 ) {
		$pinmo = Pinmo::getInstance();
		$tp = $pinmo->getDb( )->ExecuteTable("TiposPublicacion", NULL, "idTipoPublicacion");
		
		$response = "[{\"id\": \"\", \"nombre\": \"Seleccione Tipo Publ.\"},";
		$arreglo['output'][] = "Seleccione Tipo Publ.";
		$arreglo['values'][] = "";

		foreach ($tp as $t) {
			$arreglo['output'][] = ucwords(mb_strtolower($t['descripcion'], "UTF8"));
			$arreglo['values'][] = $t['idTipoPublicacion'];
			$response .= "{\"id\": \"".$t['idTipoPublicacion']."\", \"nombre\": \"".ucwords(mb_strtolower($t['descripcion'], "UTF8"))."\"},";
		}
		
		$response = substr($response, 0, strlen($response) - 1);
		$response .= "]";

		if ($json)
			return $response;
		else
			return $arreglo;

		$pinmo->getDb( )->close( );
	}

	public function getTiposMonedaAsOptionArray( $json = 0 ) {
		$pinmo = Pinmo::getInstance();
		$tipos = $pinmo->getDb( )->ExecuteTable("TiposMoneda", NULL, "idTipoMoneda");
		
		$response = "[{\"id\": \"\", \"nombre\": \"Seleccione Moneda\"},";
		$arreglo['output'][] = "Seleccione Moneda";
		$arreglo['values'][] = "";
		
		foreach ($tipos as $tm) {
			$arreglo['output'][] = $tm['simbolo'] ." - ". $tm['descripcion'];
			$arreglo['values'][] = $tm['idTipoMoneda'];
			$response .= "{\"id\": \"".$tm['idTipoMoneda']."\", \"nombre\": \"".$tm['simbolo']."\" - \"".$tm['descripcion']."\"},";
		}
		
		$response = substr($response, 0, strlen($response) - 1);
		$response .= "]";

		if ($json)
			return $response;
		else
			return $arreglo;

		$pinmo->getDb( )->close( );
	}

	public function getEstadosPublicacionAsOptionArray( $json = 0 ) {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );
		$estados = $pinmo->getDb( )->ExecuteTable("EstadosPublicacion", NULL, "idEstadoPublicacion");
		
		$response = "[{\"id\": \"\", \"nombre\": \"Seleccione Estado Publ.\"},";
		$arreglo['output'][] = "Seleccione Estado Publ.";
		$arreglo['values'][] = "";
		
		foreach ($estados as $es) {
			$arreglo['output'][] = ucwords(mb_strtolower($es['descripcion'], "UTF8"));
			$arreglo['values'][] = $es['idEstadoPublicacion'];
			$response .= "{id: ".$es['idEstadoPublicacion'].", nombre: '".$es['descripcion']."'},";
			$response .= "{\"id\": \"".$es['idEstadoPublicacion']."\", \"nombre\": \"".ucwords(mb_strtolower($es['descripcion'], "UTF8"))."\"},";
		}
		
		$response = substr($response, 0, strlen($response) - 1);
		$response .= "]";

		if ($json)
			return $response;
		else
			return $arreglo;

		$pinmo->getDb( )->close( );
	}

	public static function getEstadosExclusividadAsOptionArray( $json = 0 ) {
		$pinmo = Pinmo::getInstance();
		$estados = $pinmo->getDb( )->ExecuteTable("EstadosExclusividad", NULL, "idEstadoExclusividad");
		
		$response = "[{\"id\": \"\", \"nombre\": \"Seleccione Estado Excl.\"},";
		$arreglo['output'][] = "Seleccione Estado Excl.";
		$arreglo['values'][] = "";
		
		foreach ($estados as $es) {
			$arreglo['output'][] = ucwords(mb_strtolower($es['estadoExclusividad'], "UTF8"));
			$arreglo['values'][] = $es['idEstadoExclusividad'];
			$response .= "{\"id\": \"".$es['idEstadoExclusividad']."\", \"nombre\": \"".ucwords(mb_strtolower($es['estadoExclusividad'], "UTF8"))."\"},";
		}
		
		$response = substr($response, 0, strlen($response) - 1);
		$response .= "]";

		if ($json)
			return $response;
		else
			return $arreglo;

		$pinmo->getDb( )->close( );
	}
	
	public static function eliminarPublicacionesDePropiedad($idPropiedad) {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql = "DELETE FROM Propiedades_Publicacion WHERE idPropiedad = {$idPropiedad}";

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );	
	}

	public function existePublicacion( $idPropiedad ) {

		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql = "Propiedades_Publicacion WHERE idTipoPublicacion = {$this->idTipoPublicacion} AND idPropiedad = {$idPropiedad}";

		$query = $pinmo->getDb( )->RecordCount( $sql );

		//echo($query."<br />");

		if($query) return true;
		else return false;

		$pinmo->getDb( )->close( );
	}

	public function grabarPublicacion( $idPropiedad ) {

		if($this->existePublicacion( $idPropiedad ))
			return $this->updatePublicacion( $idPropiedad );
		else
			return $this->insertPublicacion( $idPropiedad );
	}

	private function insertPublicacion( $idPropiedad ) {

		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql  = "INSERT INTO Propiedades_Publicacion(idPropiedad, idTipoPublicacion, idTipoMoneda, monto, idEstadoPublicacion, idEstadoExclusividad, comision) ";
		$sql .= "VALUES( '{$idPropiedad}', '{$this->idTipoPublicacion}', '{$this->idTipoMoneda}', '{$this->monto}', '{$this->idEstadoPublicacion}', ";
		$sql .= "'{$this->idEstadoExclusividad}', '{$this->comision}')";
	
		//echo $sql;

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );
	}

	private function updatePublicacion( $idPropiedad ) {

		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql  = "UPDATE Propiedades_Publicacion SET idTipoMoneda = {$this->idTipoMoneda}, monto = '{$this->monto}', ";
		$sql .= "idEstadoPublicacion = '{$this->idEstadoPublicacion}', idEstadoExclusividad = '{$this->idEstadoExclusividad}', ";
		$sql .= "comision = '{$this->comision}' WHERE idPropiedad = {$idPropiedad} AND idTipoPublicacion = {$this->idTipoPublicacion}";

		//echo $sql;

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );

	}

	
	// PUBLIC GETTERS

	public function getIdTipoPublicacion()	  { return $this->idTipoPublicacion; }
	public function getTipoPublicacion()	  { return $this->sTipoPublicacion; }
	public function getIdTipoMoneda()		  { return $this->idTipoMoneda; }
	public function getMoneda()				  { return $this->simboloMoneda; }
	public function getIdEstadoPublicacion()  { return $this->idEstadoPublicacion; }
	public function getEstadoPublicacion()    { return $this->sEstadoPublicacion; }
	public function getIdEstadoExclusividad() { return $this->idEstadoExclusividad; }
	public function getComision()			  { return $this->comision; }

	public function getMonto( $panel = 0 ){
		if ($panel)
			return ($this->monto > 0) ? number_format((double)$this->monto, 0, ",", ".") : "No Ingresado";
		else
			return ($this->monto > 0 && $this->monto <= 300000) ? number_format((double)$this->monto, 0, ",", ".") : "Consultar";
	}


	// PUBLIC SETTERS

	public function setIdTipoPublicacion( $var )	{ $this->idTipoPublicacion = $var; }
	public function setTipoPublicacion( $var )		{ $this->idTipoPublicacion = $var; }
	public function setIdEstadoPublicacion( $var )  { $this->idEstadoPublicacion = $var; }
	public function setEstadoPublicacion( $var )    { $this->sEstadoPublicacion = $var; }
	public function setMonto( $var )				{ $this->monto = $var; }
	public function setIdTipoMoneda( $var )			{ $this->idTipoMoneda = $var; }
	public function setMoneda( $var )				{ $this->simboloMoneda = $var; }
	public function setIdEstadoExclusividad( $var )	{ $this->idEstadoExclusividad = $var; }
	public function setComision( $var )				{ $this->comision = $var; }


} // Fin de Clase

?>