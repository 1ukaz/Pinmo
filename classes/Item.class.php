<?php

class Item {

	private $itId;
	private $itDescripcion;
	private $itAgrupamiento;
	private $itAgrupamientoControl;
	private $itIcono;
	private $itEstado;

	
	
	public static function createItem($idItem = '', $descripcion = '', $agrupamiento = '', $control = 'checkbox', $icono = '', $estado = 0) {
		$aItem = new self;
		$aItem->itId = $idItem;
		$aItem->itDescripcion = $descripcion;
		$aItem->itAgrupamiento = $agrupamiento;
		$aItem->itAgrupamientoControl = $control;
		$aItem->itIcono = $icono;
		$aItem->itEstado = $estado;

		return $aItem;
	}

	public static function eliminarItemsDePropiedad( $idPropiedad ){

		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );
		
		$sql = "DELETE FROM Propiedades_Items WHERE idPropiedad = {$idPropiedad}";

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );
	}

	public static function getItemsFromDBToDisplay( ) {
		$aItems = array();
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql  = "SELECT AI.descripcion_agrupamiento, AI.control, I.idItem, I.descripcion, I.imagen, I.orden ";
		$sql .= "FROM Items I INNER JOIN AgrupamientoItems AI ON I.agrupamiento = AI.agrupamiento ";
		$sql .= "ORDER BY AI.orden ASC, I.orden ASC";
		
		$result = $result = $pinmo->getDb( )->Execute( $sql );

		if (is_array($result) && !empty($result)) {
			foreach ($result as $row) {
				$aItems[] = self::createItem($row['idItem'], ucwords(mb_strtolower($row["descripcion"], "UTF8")), ucwords(mb_strtolower($row['descripcion_agrupamiento'], "UTF8")), $row['control'], $row['imagen'], $row['estado']);
			}
		}
		
		return $aItems;

		$pinmo->getDb( )->close( );
	}

	// Si es Editar, traigo todos los items, con los asociados marcados para poder seleccionarlos. Si no, es para verlos, traigo solo los asociados
	public static function getPropAssociatedItemsFromDB( $idPropiedad, $ed = 0 ) {
		$aItems = array();
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		if ($ed) {
			$sql  = "SELECT AgrupamientoItems.descripcion_agrupamiento, AgrupamientoItems.control, Items.*, IF(idPropiedad IS NULL, FALSE, TRUE) AS estado FROM Items ";
			$sql .= "LEFT OUTER JOIN Propiedades_Items ON Propiedades_Items.idItem = Items.idItem AND Propiedades_Items.idPropiedad = '{$idPropiedad}' ";
			$sql .= "INNER JOIN AgrupamientoItems ON Items.agrupamiento = AgrupamientoItems.agrupamiento ORDER BY AgrupamientoItems.orden ASC, Items.orden ASC";
		}
		else {
			$sql  = "SELECT AgrupamientoItems.descripcion_agrupamiento, AgrupamientoItems.control, Items.* FROM Items ";
			$sql .= "INNER JOIN Propiedades_Items ON Propiedades_Items.idItem = Items.idItem AND Propiedades_Items.idPropiedad = '{$idPropiedad}' ";
			$sql .= "INNER JOIN AgrupamientoItems ON Items.agrupamiento = AgrupamientoItems.agrupamiento ORDER BY AgrupamientoItems.orden ASC, Items.orden ASC";
		}
		
		$result = $result = $pinmo->getDb( )->Execute( $sql );

		if (is_array($result) && !empty($result)) {
			foreach ($result as $row) {
				$aItems[] = self::createItem($row['idItem'], ucwords(mb_strtolower($row["descripcion"], "UTF8")), ucwords(mb_strtolower($row['descripcion_agrupamiento'], "UTF8")), $row['control'], $row['imagen'], $row['estado']);
			}
		}
		return $aItems;

		$pinmo->getDb( )->close( );
	}
	
	public function guardarItem( $idPropiedad ){
			return $this->insertItem( $idPropiedad );
	}

	private function insertItem( $idProp ) {

		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql = "INSERT INTO Propiedades_Items(idPropiedad, idItem) VALUES({$idProp}, {$this->itId})";
	
		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );

	}

	
	// PUBLIC GETTERS

	public function getIdItem()				 { return $this->itId; }
	public function getDescripcion()		 { return $this->itDescripcion; }
	public function getAgrupamiento()		 { return $this->itAgrupamiento; }
	public function getAgrupamientoControl() { return $this->itAgrupamientoControl; }
	public function getIcono()				 { return $this->itIcono; }	
	public function getEstado()				 { return $this->itEstado; }


} // Fin de Clase


?>