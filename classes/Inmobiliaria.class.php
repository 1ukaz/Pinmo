<?php

class Inmobiliaria {
	
	private $idInmobiliaria;
	private $nombre;
	private $dominio;
	private $logoPath;
	private $telefono;

	public  $aMensajes = array();
	public  $ubicacion;

	
	public function __construct() { 
		$this->ubicacion = new Ubicacion; 
	}

	public static function getInmobiliariasAsOptionArray( $json = 0, $idInmobiliariaLogueada = 0 ) {
		$pinmo = Pinmo::getInstance();
		$inm = $pinmo->getDb( )->ExecuteTable("Inmobiliarias", "idInmobiliaria != {$idInmobiliariaLogueada}", "descripcion_inmobiliaria");

		$response = "[{\"id\": \"\", \"nombre\": \"Seleccione Inmobiliaria\"},";
		$arreglo['output'][] = "Seleccione Inmobiliaria";
		$arreglo['values'][] = "";
		
		foreach ($inm as $i) {
			$arreglo['output'][] = ucwords(mb_strtolower($i['descripcion_inmobiliaria'], "UTF8"));
			$arreglo['values'][] = $i['idInmobiliaria'];
			$response .= "{\"id\": \"".$i['idInmobiliaria']."\", \"nombre\": \"".ucwords(mb_strtolower($i['descripcion_inmobiliaria'], "UTF8"))."\"},";
		}
		
		$response = substr($response, 0, strlen($response) - 1);
		$response .= "]";

		if ($json)
			return $response;
		else
			return $arreglo;

		$pinmo->getDb( )->close( );
	}

	public function getMensajesFromDB( $idUsuario, $page, $oPaginador ) {
		$this->aMensajes = NULL;
		$oPaginador->setEnabled(1);
		if (count($this->aMensajes = Mensaje::getAssociatedInmobiliariaMensajes( $this->idInmobiliaria, $idUsuario, $page, $oPaginador )) > 0) {
			return true;
		}
		else {
			$oPaginador->setEnabled(0);
			return false;
		}
	}

	// PUBLIC GETTERS

	public function getIdInmobiliaria( )	{ return $this->idInmobiliaria; }
	public function getNombre( )			{ return $this->nombre; }
	public function getDominio( )			{ return $this->dominio; }
	public function getMensajes( )			{ return $this->aMensajes; }
	public function getLogoPath( )			{ return $this->logoPath; }
	public function getPaginador( )			{ return $this->oPaginador; }
	public function getUbicacion( )			{ return $this->ubicacion; }
	public function getTelefono( )			{ return $this->telefono; }
	
	// PUBLIC SETTERS

	public function setIdInmobiliaria( $var )					{ $this->idInmobiliaria = $var; }
	public function setNombre( $var )							{ $this->nombre = $var; }
	public function setDominio( $var )							{ $this->dominio = $var; }
	public function setMensajesArrayMemeber( $pos, $object )	{ $this->aMensajes[$pos] = $var; }
	public function setLogoPath( $var )							{ $this->logoPath = $var; }
	public function setUbicacion( $object )						{ $this->ubicacion = $object; }
	public function setTelefono( $var )							{ $this->telefono = $var; }

} // Fin de Clase

?>