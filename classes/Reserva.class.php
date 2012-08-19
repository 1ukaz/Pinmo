<?php

class Reserva {

	private $idReserva;
	private $fechaDesde;
	private $fechaHasta;
	private $notasReserva;
	private $esMarcada = false;
	
	public  $error = "<ul style=\"margin:0px; padding:5px;\">";

	
	
	public static function createReserva($idR = '', $fD = '', $fH = '', $n = '') {
		$aRes = new self;
		$aRes->notasReserva = $n;
		$aRes->idReserva = $idR;
		$aRes->fechaDesde = $fD;
		$aRes->fechaHasta = $fH;
		
		return $aRes;
	}

	public static function getPropAssociatedReservasFromDB( $idPropiedad, $all ) {
		$aReservas = array();
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql  = "SELECT DATE_FORMAT(fechaDesde,'%d/%m/%Y') as fechaDesde, DATE_FORMAT(fechaHasta, '%d/%m/%Y') as fechaHasta, notasReserva, idReserva ";
		$sql .= "FROM Reservaciones WHERE idPropiedad = {$idPropiedad} ";
		if (!$all) 
			$sql .= "AND (fechaDesde >= NOW() OR fechaHasta BETWEEN NOW() AND fechaHasta)";
		
		$result = $pinmo->getDb( )->Execute( $sql );

		if (is_array($result) && !empty($result)) {
			foreach ($result as $row) {
				$aReservas[] = self::createReserva($row['idReserva'], $row['fechaDesde'], $row['fechaHasta'], $row['notasReserva']);
			}
		}
		return $aReservas;

		$pinmo->getDb( )->close( );

	}
	
	static public function eliminarReservasDePropiedad($idPropiedad) {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql = "DELETE FROM Reservaciones WHERE idPropiedad = {$idPropiedad}";

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );		
	}

	public function setReservaInfo( $idProp, $fd, $fh, $nt ) {
		if ($fd != "" && $fh != "" && compareDates($fd, $fh) != 0) {
			if (compareDates($fd, $fh) == 1) {
				if (!$this->isPeriodBooked( $idProp, $fd, $fh )) {
					$this->notasReserva = ucwords(mb_strtolower($nt, "UTF8"));
					$this->fechaDesde = $fd;
					$this->fechaHasta = $fh;
					return true;
				}
				else {
					$this->error .= "<li>El Periodo Seleccionado se SUPERPONE con uno YA asignado</li>";
					return false;
				}
			}
			else {
				$this->error .= "<li>La Fecha Desde debe ser ANTERIOR a la Fecha Hasta</li>";
				return false;
			}
		}
		else {
			$this->error .= "<li>Las DOS Fechas son Requeridas; y NO pueden ser la misma</li>";
			return false;
		}
		$this->error .= "</ul>";
	}

	public function saveToDB( $idPropiedad ){
		if ($this->esMarcada === true) {
			if (!$this->deleteReserva( $idPropiedad )) {
				return false;
			}
		}
		else {
			if (!$this->existeReserva( $idPropiedad )) {
				if (!$this->insertReserva( $idPropiedad )) {
					return false;
				}				
			}
			else
				return -1;
		}
		return true;
	}
	
	private function insertReserva( $idPropiedad ) {

		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$fde = convertirFechaSql( $this->fechaDesde );
		$fha = convertirFechaSql( $this->fechaHasta );
				
		$sql  = "INSERT INTO Reservaciones(idPropiedad, fechaDesde, fechaHasta, notasReserva) ";
		$sql .= "VALUES('{$idPropiedad}', '{$fde}', '{$fha}', '{$this->notasReserva}')";

		//echo $sql."<br />";

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );
			
	}
	
	private function deleteReserva( $idPropiedad ) {

		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$fd = convertirFechaSql( $this->fechaDesde );
		$fh = convertirFechaSql( $this->fechaHasta );
			
		$sql = "DELETE FROM Reservaciones WHERE idPropiedad = '{$idPropiedad}' AND fechaDesde = '{$fd}' AND fechaHasta = '{$fh}'";

		//echo $sql;

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );
		
	}

	private function isPeriodBooked( $idPropiedad, $fechaDesde, $fechaHasta ){

		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$fechaDesde = convertirFechaSql( $fechaDesde );
		$fechaHasta = convertirFechaSql( $fechaHasta );
				
		$sql  = "Reservaciones WHERE idPropiedad = '{$idPropiedad}' ";
		$sql .= "AND ((fechaDesde <= '{$fechaDesde}' AND fechaHasta >= '{$fechaHasta}' ) ";
		$sql .= "OR (fechaDesde >= '{$fechaDesde}' AND fechaHasta <= '{$fechaHasta}' ) ";
		$sql .= "OR (fechaDesde BETWEEN '{$fechaDesde}' AND '{$fechaHasta}' ) ";
		$sql .= "OR (fechaHasta BETWEEN '{$fechaDesde}' AND '{$fechaHasta}' ))";

		//echo($sql);
		
		$result = $pinmo->getDb( )->RecordCount( $sql );
	
		//echo("<br />Tomado: ".$result." [0:Disponible || 1:Esta Tomado]");

		if($result) return true;
		else return false;

		$pinmo->getDb( )->close( );
	}	

	private function existeReserva( $idPropiedad ){

		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$fd = convertirFechaSql( $this->fechaDesde );
		$fh = convertirFechaSql( $this->fechaHasta );

		$sql = "Reservaciones WHERE idPropiedad = {$idPropiedad} AND fechaDesde = '{$fd}' AND fechaHasta = '{$fh}'";
		//$sql = "Reservaciones WHERE idReserva = {$this->idReserva}";

		//echo($sql."<br />");

		$query = $pinmo->getDb( )->RecordCount( $sql );

		//echo($query."<br />");

		if($query) return true;
		else return false;

		$pinmo->getDb( )->close( );
	}


	// PUBLIC GETTERS

	public function getIdReserva()  { return $this->idReserva; }
	public function getFechaDesde() { return $this->fechaDesde; }
	public function getFechaHasta() { return $this->fechaHasta; }
	public function getNotas()		{ return $this->notasReserva; }
	public function getMarca()		{ return $this->esMarcada; }


	// PUBLIC SETTERS

	public function setIdReserva( $var )  { $this->idReserva = $var; }
	public function setFechaDesde( $var ) { $this->fechaDesde = $var; }
	public function setFechaHasta( $var ) { $this->fechaHasta = $var; }
	public function setNotas( $var )	  { $this->notasReserva = $var; }
	public function setMarca( $var )	  { $this->esMarcada = $var; }
		
	/**
	 * Devuelve un array con las reservaciones de una propiedad en un periodo de tiempo determinado, si se omiten fechas trae todo
	 * NI IDEA SI LA ESTOY USANDO O NO Y SI LA NECESITARE ??
	 * @param integer $idPropiedad
	 * @param string  $desde
	 * @param string $hasta
	 * @return array
	 */
	public function getReservacionesAsAssociativeArray( $idPropiedad ){

		$aReservas = array();
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql  = "SELECT DATE_FORMAT(fechaDesde,'%Y-%m-%d') as fechaDesde, DATE_FORMAT(fechaHasta, '%Y-%m-%d') as fechaHasta, notasReserva ";
		$sql .= "FROM Reservaciones WHERE idPropiedad = '{$idPropiedad}'";
		
		if($this->fechaDesde != '' && $this->fechaHasta != ''){
			$sql .= "AND ((fechaDesde <= '{$this->fechaDesde}' AND fechaHasta >= '{$this->fechaHasta}' ) ";
			$sql .= "OR (fechaDesde >= '{$this->fechaDesde}' AND fechaHasta <= '{$this->fechaHasta}' ) ";
			$sql .= "OR	(fechaDesde BETWEEN '{$this->fechaDesde}' AND '{$this->fechaHasta}' ) ";
			$sql .= "OR (fechaHasta BETWEEN '{$this->fechaDesde}' AND '{$this->fechaHasta}' ))";
		}
		
		$result = $pinmo->getDb( )->Execute( $sql );

		if (is_array($result) && !empty($result)) {
			foreach ($result as $key => $row) {
				$aReservas[] = array($key => $row[$key], $key => $row[$key], $key => $row[$key] );
			}
		}
		return $aReservas;

		$pinmo->getDb( )->close( );
				
	}

}
?>