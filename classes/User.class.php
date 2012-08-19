<?php

class User {
	
	private $id = 0;
	private $oid;
	private $ipAddress;
	private $documento;
	private $password;
	private $nombre;
	private $apellido;
	private $email;

	public  $inmobiliaria;


	
	public function __construct() { 
		$this->inmobiliaria = new Inmobiliaria; 
	}

	public static function getUserById( $idUsuario ) {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql = "SELECT * FROM Usuarios NATURAL JOIN Inmobiliarias WHERE idUsuario = {$idUsuario}";
		$result = $pinmo->getDb( )->Execute( $sql );
		
		$usr = new self;
		if (is_array($result) && !empty($result)) {
			foreach ($result as $row) {
				$usr->setId( $row['idUsuario'] );
				$usr->getInmobiliaria()->setIdInmobiliaria( $row['idInmobiliaria'] );
				$usr->getInmobiliaria()->setNombre( $row['descripcion_inmobiliaria'] );
				$usr->getInmobiliaria()->setDominio( $row['dominio_asignado'] );
				$usr->getInmobiliaria()->setLogoPath( $row['logo'] );
				$usr->setDocumento( $row['documento'] );
				$usr->setPassword( $row['password'] );
				$usr->setNombre( $row['nombre'] );
				$usr->setApellido( $row['apellido'] );
				$usr->setEmail( $row['email'] );
			}
		}
		return $usr;
	}

	public function registerUsrLogin( $fromWhere, $dbObject, $lat, $lon ) {
		$this->ipAddress = $fromWhere;
		$sql = "INSERT INTO LogsPanel(idUsuario, ip, latitud, longitud) VALUES( '{$this->id}','{$this->ipAddress}','{$lat}','{$lon}' )";
		
		$query = $dbObject->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;
	}
	
	
	// PUBLIC SETTERS

	public function setId( $var )				{ $this->id = $var; }
	public function setOid( $var )				{ $this->oid = $var; }
	public function setInmobiliaria( $objeto )	{ $this->inmobiliaria = $objeto; }
	public function setDocumento( $var )		{ $this->documento = $var; }
	public function setPassword( $var )			{ $this->password = $var; }
	public function setNombre( $var )			{ $this->nombre = $var; }
	public function setApellido( $var )			{ $this->apellido = $var; } 
	public function setEmail( $var )			{ $this->email = $var; }
	public function setLoginAddress( $var )		{ $this->ipAddress = $var; }


	// PUBLIC GETTERS

	public function getId( )				{ return $this->id; }
	public function getOid( )				{ return $this->oid; }
	public function getInmobiliaria( )		{ return $this->inmobiliaria; } 
	public function getDocumento( )			{ return $this->documento; }
	public function getPassword( )			{ return $this->password; }
	public function getNombre( )			{ return $this->nombre; }
	public function getApellido( )			{ return $this->apellido; }
	public function getEmail( )				{ return $this->email; }
	public function getLoginAddress( )		{ return $this->ipAddress; }


} // Fin de Clase

?>