<?php

class Database {

	private $host = "190.228.29.58";
	private $user = "pinmoremoto";
	private $pass = "wh4tp4ss!";
	private $bbdd = "pinmocom";
	
	//private $host = "localhost";
	//private $user = "root";
	//private $pass = "root";
	//private $bbdd = "pinmocom_dev";

	private $conn;
	static private $instance = NULL;

	const OPENED = 1;
	const CLOSED = 0;

	public $status = CLOSED;

	
	
	private function __construct() {}

    static public function getInstance() {
       if (self::$instance == NULL) {
          self::$instance = new Database();
       }
       return self::$instance;
    }
	
	public function open( ) {
		$con = mysql_connect( $this->host, $this->user, $this->pass );
		try {
			if (!$con) {
				$exceptionstring  = "<ul style=\"margin:0px; padding:5px;\">";
				$exceptionstring .= "<li>No se pudo conectar al servidor: {$this->host}<br>";
				$exceptionstring .= "ERROR: ".mysql_errno().": ".mysql_error()."</li>";
				throw new exception($exceptionstring);
			}
			else {
				$this->conn = $con;
				mysql_query("SET NAMES 'utf8'");
				$this->select_db( );
			}
		}
		catch (exception $e){
			echo($e->getmessage( ));
			die();
		}
	}

	private function select_db( ) {
		mysql_select_db ( $this->bbdd, $this->conn ) or die ( "No pudo seleccionarse la Base de Datos: " . mysql_error() );
	}	

	public function close( ) {
		mysql_close( $this->conn );
	}

	public function ExecuteNonQuery( $sql ) {
		if ($this->status == self::CLOSED)
			$this->open();
		$rs = mysql_query( $sql, $this->conn );
		settype($rs, "null");
	}

	public function Execute( $aSQL ) {
		if ($this->status == self::CLOSED)
			$this->open();
		//echo $aSQL . "<br />";
		$query = mysql_query($aSQL, $this->conn);
		
		try {
			if (!$query) {
				$exceptionstring  = "<ul style=\"margin:0px; padding:5px;\">";
				$exceptionstring .= "<li>MySQL Ha Lanzado el Siguiente Mensaje:<br>";
				$exceptionstring .= "ERROR: " . mysql_errno() . ": " . mysql_error() . "<br />En Query: " . $aSQL  . "</li>";
				throw new exception($exceptionstring);
			}
			else {
				$regs = array();
				while ($res = mysql_fetch_array($query)) {
					$regs[] = $res;
				}
				return $regs;
			}
		}
		catch (exception $e){
			echo($e->getmessage( ));
			die();
		}
	}

	public function ExecuteRecord( $tableName, $filter ) {
		$rec = $this->Execute("SELECT * FROM $tableName WHERE $filter");
		return $rec[0];
	}

	public function ExecuteField( $tableName, $field, $filter ) {
		$rec = $this->Execute("SELECT $field FROM $tableName WHERE $filter");
		$aux = array();
		foreach ($rec as $one) {
			$aux[] = $one[$field];
		}

		return $aux;
	}

	public function ExecuteTable( $tableName, $filter = 0, $orden = 0 ) {
		(!$filter || $filter === NULL) ? $filter = "1" : $filter = $filter;
		(!$orden  || $orden === NULL)  ? $orden = "1"  : $orden = $orden;
		return $this->Execute("SELECT * FROM $tableName WHERE $filter ORDER BY $orden");
	}
	
	public function ExecuteScalar( $aSQL ) {
		if ($this->status == Database::CLOSED)
			$this->open();
		
		$query = mysql_query($aSQL, $this->conn);
	
		try {
			if (!$query) {
				$exceptionstring  = "<ul style=\"margin:0px; padding:5px;\">";
				$exceptionstring .= "<li>MySQL Ha Lanzado el Siguiente Mensaje:<br>";
				$exceptionstring .= "ERROR: " . mysql_errno() . ": " . mysql_error() . "<br />En Query: " . $aSQL  . "</li>";
				throw new exception($exceptionstring);
			}
			else {
				$reg = mysql_fetch_array($query);
				return $reg[0];
			}
		}
		catch (exception $e){
			echo($e->getmessage( ));
			die();
		}		
	}

	public function RecordCount( $tableName ) {
		return $this->ExecuteScalar("SELECT COUNT(*) FROM ".$tableName);
	}

	public function ExecuteNotSelection( $aSQL ) {
		if ($this->status == Database::CLOSED)
			$this->open();
		
		$query = mysql_query($aSQL, $this->conn);

		try {
			if (!$query) {
				$exceptionstring  = "<ul style=\"margin:0px; padding:5px;\">";
				$exceptionstring .= "<li>MySQL Ha Lanzado el Siguiente Mensaje:<br>";
				$exceptionstring .= "ERROR: " . mysql_errno() . ": " . mysql_error() . "<br />En Query: " . $aSQL  . "</li>";
				throw new exception($exceptionstring);
			}
			else {
				return $query;
			}
		}
		catch (exception $e){
			echo($e->getmessage( ));
			die();
		}
	}
	
	public function startTransaction() {
		return mysql_query("START TRANSACTION;");	
	}
	
	public function rollbackTransaction() {
		return mysql_query("ROLLBACK;");	
	}
	
	public function commitTransaction() {
		return mysql_query("COMMIT;");	
	}

} // Fin de Clase

?>