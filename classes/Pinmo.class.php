<?php

class Pinmo {

	static private $instance;
	private $db;
	private $user;
	private $searcher;
	private $idioma = "es";

	
	
	private function __construct() { $this->db = Database::getInstance(); }
		
    static public function getInstance() {
       if ( !self::$instance instanceof self ) {
          self::$instance = new self();
       }
       return self::$instance;
    }

	public function loginService( $usr, $psw, $ip, $lat, $lng ) {
		$this->db->open( );
		$res = $this->db->ExecuteRecord("Usuarios NATURAL JOIN Inmobiliarias INNER JOIN Provincias ON idProvincia = inmoIdProvincia INNER JOIN Localidades ON idLocalidad = inmoIdLocalidad","documento = '".mysql_real_escape_string(trim($usr))."' AND password = '".mysql_real_escape_string(trim(md5($psw)))."'");
		$usr = new User;
		if (is_array($res) && !empty($res)) {
			session_start();
			$usr->setId( $res['idUsuario'] );
			$usr->getInmobiliaria()->setIdInmobiliaria( $res['idInmobiliaria'] );
			$usr->getInmobiliaria()->setNombre( $res['descripcion_inmobiliaria'] );
			$usr->getInmobiliaria()->setDominio( $res['dominio_asignado'] );
			$usr->getInmobiliaria()->setLogoPath( $res['logo'] );
			$usr->getInmobiliaria()->setTelefono( $res["inmoTelefono"] );
			$usr->getInmobiliaria()->getUbicacion()->setCalle( $res["inmoCalle"] );
			$usr->getInmobiliaria()->getUbicacion()->setNumero( $res["inmoNumero"] );
			$usr->getInmobiliaria()->getUbicacion()->setPiso( $res["inmoPiso"] );
			$usr->getInmobiliaria()->getUbicacion()->setDepartamento( $res["inmoDepartamento"] );
			$usr->getInmobiliaria()->getUbicacion()->setNomLocalidad( $res["localidad"] );
			$usr->getInmobiliaria()->getUbicacion()->setNomProvincia( $res["provincia"] );
			$usr->getInmobiliaria()->getUbicacion()->setIdLocalidad( $res["idLocalidad"] );
			$usr->getInmobiliaria()->getUbicacion()->setIdProvincia( $res["idProvincia"] );
			$usr->getInmobiliaria()->getUbicacion()->setCPA( $res["inmoCp"] );
			$usr->setDocumento( $res['documento'] );
			$usr->setPassword( $res['password'] );
			$usr->setNombre( $res['nombre'] );
			$usr->setApellido( $res['apellido'] );
			$usr->setEmail( $res['email'] );
			$usr->setOid( session_id() );
			$usr->registerUsrLogin( $ip, $this->getDb(), $lat, $lng );
			$this->user = $usr;
			return true;
		}
		else
			return false;

		$this->db->close( );
	}


	// PUBLIC GETTERS

	public function getUser( )		{ return $this->user; }
	public function getDb( )		{ return $this->db; } 
	public function getSearcher( )	{ return $this->searcher; }
	public function getIdioma( )	{ return $this->idioma; }


	// PUBLIC SETTERS

	public function setSearcher( $objeto )	{ $this->searcher = $objeto; }
	public function setIdioma( $var )		{ $this->idioma = $var; }


} // Fin de Clase

?>