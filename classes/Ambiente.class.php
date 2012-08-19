<?php

class Ambiente {

	private $amTipoId;
	private $amDescripcion;
	private $amAncho;
	private $amLargo;
	public  $error;

	
	
	public static function createAmbiente( $idTH = '', $dHab, $a = '', $l = '') {
		$aRoom = new self;
		$aRoom->amTipoId = $idTH;
		$aRoom->amDescripcion = ucwords(mb_strtolower($dHab, "UTF8"));
		$aRoom->amAncho = $a;
		$aRoom->amLargo = $l;

		return $aRoom;
	}

	public static function getTiposAmbientesAsOptionArray( $json = 0 ) {
		$pinmo = Pinmo::getInstance();
		$ambientes = $pinmo->getDb( )->ExecuteTable("TiposHabitacion", NULL, "descripcion");
		
		$response = "[{\"id\": \"\", \"nombre\": \"Seleccione Tipo Amb.\"},";
		$arreglo['output'][] = "Seleccione Tipo Amb.";
		$arreglo['values'][] = "";
		
		foreach ($ambientes as $am) {
			$arreglo['output'][] = $am['descripcion'];
			$arreglo['values'][] = $am['idTipoHabitacion'];
			$response .= "{\"id\": \"".$am['idTipoHabitacion']."\", \"nombre\": \"".ucwords(mb_strtolower($am['descripcion'], "UTF8"))."\"},";
		}
		
		$response = substr($response, 0, strlen($response) - 1);
		$response .= "]";

		if ($json)
			return $response;
		else
			return $arreglo;

		$pinmo->getDb( )->close( );
	}

	public static function eliminarAmbientesDePropiedad( $idPropiedad ) {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql = "DELETE FROM Propiedades_Habitaciones WHERE idPropiedad = {$idPropiedad}";

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );
	}

	public function getPropAssociatedAmbientesFromDB( $idPropiedad ) {
		$aAmbientes = array();
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql = "SELECT * FROM Propiedades_Habitaciones PH NATURAL JOIN TiposHabitacion TH WHERE PH.idPropiedad = {$idPropiedad} ORDER BY TH.orden ASC /*TH.descripcion ASC*/ ";
		
		$result = $pinmo->getDb( )->Execute( $sql );

		if (is_array($result) && !empty($result)) {
			foreach ($result as $row) {
				$aAmbientes[] = self::createAmbiente($row['idTipoHabitacion'], $row['descripcion'], $row['ancho'], $row['largo']);
			}
		}
		return $aAmbientes;

		$pinmo->getDb( )->close( );
	}

	public function guardarAmbiente( $idPropiedad ){
			return $this->insertAmbiente( $idPropiedad );
	}

	private function insertAmbiente( $idPropiedad ) {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql  = "INSERT INTO Propiedades_Habitaciones(idPropiedad, idTipoHabitacion, ancho, largo) ";
		$sql .= "VALUES({$idPropiedad}, {$this->amTipoId}, '{$this->amAncho}', '{$this->amLargo}')";

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );

	}

	
	// PUBLIC SETTERS

	public function setIdTipoAmbiente( $var ) { $this->amTipoId = $var; }
	public function setDescripcion( $var ) { $this->amDescripcion = $var; }

	
	// PUBLIC GETTERS

	public function getDescripcion() { return $this->amDescripcion; }
	public function getIdTipoAmbiente() { return $this->amTipoId; }
	public function getAncho() { return $this->amAncho; }
	public function getLargo() { return $this->amLargo; }


} // Fin de Clase

?>