<?php

class Mensaje {

	private $idMensaje;
	private $idPropiedad;
	private $fecha;
	private $remitente;
	private $cabecera;
	private $cuerpo;
	private $leido;
	private $respondido;
	private $eliminado;
	private $mailer;
	public  $error = "";



	public function __construct($usr = null, $psw = null, $hst = null, $sbj = null, $lng = "es") {
		if (!is_null($hst)) {
			$aMailer = new PHPMailer;
			$aMailer->Username = $usr;
			$aMailer->Password = $psw;
			$aMailer->Host = $hst;
			$aMailer->Subject = $sbj;
			//$aMailer->Subject = str_replace(array("á","é","í","ó","ú","ñ"), array("a","e","i","o","u","ni"), utf8_decode($sbj));
			$aMailer->SetLanguage( $lng );
			$this->mailer = $aMailer;
		}
	}

	public static function getAssociatedInmobiliariaMensajes( $idInmobiliaria, $idUsuario, $passedPage, $oPaginador ) {
		$aMensajes = array();
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );
		
		$sql  = "SELECT DISTINCT LC.*, P.codigoReferencia, P.idPropiedad, P.calle, P.numero FROM LogsConsultaWebs LC ";
		$sql .= "NATURAL JOIN Propiedades P INNER JOIN Inmobiliarias I ON P.idInmobiliaria = I.idInmobiliaria ";
		$sql .= "INNER JOIN Usuarios U ON I.idInmobiliaria = U.idInmobiliaria WHERE P.idInmobiliaria = {$idInmobiliaria} ";
		$sql .= "AND borrado = 0 AND P.idUsuario = {$idUsuario} ORDER BY LC.fecha DESC, LC.idPropiedad ";

		if ($oPaginador->getEstado() == 1) {
			$oPaginador->doPaginado( count($pinmo->getDb( )->Execute( $sql )), $passedPage );
			$sql .= "LIMIT ".$oPaginador->getLimitValue().",".$oPaginador->getLimit();
		}

		$result = $pinmo->getDb( )->Execute( $sql );

		if (is_array($result) && !empty($result)) {
			foreach ($result as $row) {
				$msg = new self;
				$msg->idMensaje = $row["idMensaje"];
				$msg->fecha = convertirTimestampLetras( $row["fecha"] );
				$msg->remitente = $row["email"];
				$cabecera = "Consulta Sobre la Propiedad de: " . ucwords(mb_strtolower($row["calle"], "UTF8")) ." ". $row["numero"];
				$msg->cuerpo = $row["mensaje"];
				$msg->leido = $row["leido"];
				$msg->respondido = $row["respondido"];
				if($msg->leido == true) $cabecera .= " - Leido";
				if($msg->respondido == true) $cabecera .= " [Respondido]";
				$msg->cabecera = $cabecera;
				$aMensajes[] = $msg;
			}
		}
		
		return $aMensajes;

		$pinmo->getDb( )->close( );
	}
	
	public static function eliminarMensajesDePropiedad($idPropiedad) {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql = "DELETE FROM LogsConsultaWebs WHERE idPropiedad = {$idPropiedad}";

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );	
	}

	public function marcarComoLeido() {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql = "UPDATE LogsConsultaWebs SET leido = 1 WHERE idMensaje = {$this->idMensaje}";

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );
	}

	public function enviarRespuesta( $aResponse, $aDestinationAddress, $inmobiliariaName ) {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		if(!$this->sendMail( $aResponse, $aDestinationAddress, $inmobiliariaName, "Respuesta a Su Consulta" )) 
			return false;

		$sql = "UPDATE LogsConsultaWebs SET respondido = 1 WHERE idMensaje = {$this->idMensaje}";

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if (!$query) {
			$this->error = "<ul style=\"margin:0px;padding:0px;\"><li>El Mensaje no se pudo marcar como Respondido; pero la Respuesta fue Enviada</li>";
			return false;
		}

		$pinmo->getDb( )->close( );

		return true;
	}

	public function enviarConsulta( $laConsulta, $aDestinationAddress, $visitorName, $idPropiedad = 0 ) {

		if(!$this->sendMail( $laConsulta, $aDestinationAddress, $visitorName, $this->mailer->Subject )) 
			return false;
		
		if ($idPropiedad != 0 && $idPropiedad != '' && !is_null($idPropiedad) && (Propiedad::getPropertyToDisplay($idPropiedad)->getIdPropiedad() != 0)) {
			$pinmo = Pinmo::getInstance();
			$pinmo->getDb( )->open( );
			$sql = "INSERT INTO LogsConsultaWebs(idPropiedad, email, mensaje) VALUES( '$idPropiedad', '{$this->remitente}', '{$laConsulta}' )";
			$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );
			$pinmo->getDb( )->close( );
		}

		return true;
	}

	private function sendMail( $res, $des, $fromName, $tittle ) {

		$this->mailer->IsSMTP();
		$this->mailer->SMTPAuth = true;
		$this->mailer->From = "mailer@pinmo.com.ar";
		//$this->mailer->AddReplyTo("mailer@pinmo.com.ar", "Pinmo Mailing Service"); // Asi lo obligamos a entrar al Panel para responder
		$this->mailer->AddReplyTo($this->remitente, ucwords(mb_strtolower($fromName)));	// Con esto dejamos que le de al Responder de su mail ...
		$this->mailer->FromName = ucwords(mb_strtolower($fromName));
		$this->mailer->isHTML(true);
	 
		$this->mailer->AddAddress($des);
		//$this->mailer->AddBCC("alencas@gmail.com");
		$this->mailer->AddBCC("lukaz3nole@yahoo.com.ar");

		//$this->mailer->Body = utf8_decode(file_get_contents("http://localhost/devs/pinmo-2.com.ar/backend/includes/PHPMailer/messageTemplate.php?msg=".urlencode($res)."&ttl=".urlencode($tittle)));
		$this->mailer->Body = utf8_decode(file_get_contents("http://www.pinmo.com.ar/backend/includes/PHPMailer/messageTemplate.php?msg=".urlencode($res)."&ttl=".urlencode($tittle)));
		$this->mailer->AltBody = $res;
	 
		if(!$this->mailer->Send()) {
			$this->error  = "<ul style=\"margin:0px;padding:0px;\"><li>El mensaje no pudo ser enviado. El Detalle Es:";
			$this->error .= "<br />" . $this->mailer->ErrorInfo . "</li></ul>";
			return false;
		}
		else {
			return true;
		}
		$this->mailer->ClearAddresses();
	}

	public function eliminarMensaje() {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql = "UPDATE LogsConsultaWebs SET borrado = 1 WHERE idMensaje = {$this->idMensaje}";

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );
	}

	public function validateConsultaParamsOnServerSide( $postArray, $all = 0 ) {
		$this->error = "<ul style=\"margin:0px;padding:5px;list-style-type:disc;\">";
		if(empty($postArray['frmToken']) || $postArray['frmToken'] != session_id() ) {
			$this->error .= "<li>Ha Ocurrido un Error en la Veracidad de los Datos</li>";		
		}	
		if (!isset($postArray["txtConsulta"]) || $postArray["txtConsulta"] == '') {
			$this->error .= "<li>Su Consulta es Requerida para poder ser Enviada</li>";	
		}
		if (!isset($postArray["txtNombre"]) || $postArray["txtNombre"] == '') {
			$this->error .= "<li>Su Nombre es Requerido para Enviar La Consulta</li>";	
		}
		if (!isset($postArray["txtEmail"]) || $postArray["txtEmail"] == '') {
			$this->error .= "<li>Su E-mail es Requerido para Enviar La Consulta</li>";	
		}
		else {
			if (!strpos($postArray["txtEmail"], "@") || !strpos($postArray["txtEmail"], ".")) {
				$this->error .= "<li>Debe ingresar un E-mail valido para Enviar La Consulta</li>";
			}
		}
		if ($all) {
			if (!isset($postArray["txtProp"]) || $postArray["txtProp"] == '') {
				$this->error .= "<li>Ha Ocurrido un Error relacionando La Conulta a La Propiedad</li>";	
			}
			if (!isset($postArray["txtID"]) || $postArray["txtID"] == '') {
				$this->error .= "<li>Ha Ocurrido un Error relacionando La Conulta a La Propiedad</li>";	
			}			
		}
		if (stristr($this->error, "<li>") !== false) {
			$this->error .= "</ul>";
			return false;
		}
		return true;	
	}


	/* PUBLIC GETTERS */

	public function getIdMensaje() {
		return $this->idMensaje;
	}
	
	public function getIdPropiedad() {
		return $this->idPropiedad;
	}

	public function getFecha() {
		return $this->fecha;
	}

	public function getRemitente() {
		return $this->remitente;
	}

	public function getCabecera() {
		return $this->cabecera;
	}

	public function getCuerpo() {
		return $this->cuerpo;
	}

	public function getLeido() {
		return $this->leido;
	}

	public function getRespondido() {
		return $this->respondido;
	}

	public function getEliminado() {
		return $this->eliminado;
	}


	/* PUBLIC SETTERS */

	public function setIdMensaje( $var ) {
		$this->idMensaje = $var;
	}
	
	public function setIdPropiedad( $var ) {
		$this->idPropiedad = $var;
	}

	public function setFecha( $var ) {
		$this->fecha = $var;
	}

	public function setRemitente( $var ) {
		$this->remitente = $var;
	}

	public function setCabecera( $var ) {
		$this->cabecera = $var;
	}

	public function setCuerpo( $var ) {
		$this->cuerpo = $var;
	}

	public function setLeido( $var ) {
		$this->leido = $var;
	}

	public function setRespondido( $var ) {
		$this->respondido = $var;
	}

	public function setEliminado( $var ) {
		$this->eliminado = $var;
	}


} // Fin de Clase

?>