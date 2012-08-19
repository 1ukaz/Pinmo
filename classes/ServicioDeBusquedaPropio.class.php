<?php

class ServicioDeBusquedaPropio extends ServicioDeBusqueda {



	public function searchPropertiesForList( $orden, $pagina, $idLog, $aPostArray ) {
		if ($this->validateParamsOnServerSide( $idLog, $aPostArray )) {
			if ($this->bringPropertiesInfo( $orden, $pagina, $idLog, 1 ))
				return true;
			else
				return false;			
		}
		else {
			return false;
		}
	}

	public function setSearchParams( $idIn, $postArray ) {
		$this->busInmobiliaria = $idIn;
		$this->busCodigo = $postArray["txtCodigoReferencia_P"];
		$this->busTipoInmueble = $postArray["cboTipoPropiedad_P"];
		$this->busProvincia = $postArray["cboProvincia_P"];
		$this->busLocalidad = $postArray["cboLocalidad_P"];
		$this->busTipoPublicacion = $postArray["cboTipoPublicacion_P"];
		$this->busSuperficie = addslashes(trim($postArray["txtSuperficieTotal_P"]));
		$this->busCalle = addslashes(trim($postArray["txtCalle_P"]));
		$this->busNumero = addslashes(trim($postArray["txtNumero_P"]));
		$this->busAntiguedad = addslashes(trim($postArray["txtAntiguedad_P"]));
		$this->busPrecio = addslashes(trim($postArray["txtPrecio_P"]));
		$this->busAmbientes = addslashes(trim($postArray["txtAmbientes_P"]));
	}

	public function validateParamsOnServerSide( $idI, $postArray ) {
		$this->errores = "<ul style=\"margin:0px;padding:5px;\">";
		if (isset($postArray["txtSuperficieTotal_P"]) && $postArray["txtSuperficieTotal_P"] != "") {
			if (!is_numeric($postArray["txtSuperficieTotal_P"])) {
				$this->errores .= "<li>La Superficie se debe expresar en NUMEROS</li>";
			}
		}
		if (isset($postArray["txtNumero_P"]) && $postArray["txtNumero_P"] != "") {
			if (!is_numeric($postArray["txtNumero_P"])) {
				$this->errores .= "<li>El Numero de Calle debe ser un NUMERO</li>";
			}
		}
		if (isset($postArray["txtAntiguedad_P"]) && $postArray["txtAntiguedad_P"] != "") {
			if (!is_numeric($postArray["txtAntiguedad_P"])) {
				$this->errores .= "<li>Los A\u00f1os de Antiguedad se deben expresar en NUMEROS</li>";
			}
		}
		if (isset($postArray["txtPrecio_P"]) && $postArray["txtPrecio_P"] != "") {
			if (!is_numeric($postArray["txtPrecio_P"]) || strpos($postArray["txtPrecio_P"], ".") || strpos($postArray["txtPrecio_P"], ",")) {
				$this->errores .= "<li>El Precio debe ser un valor NUMERICO [Sin signo $, ni puntos o comas]</li>";
			}
		}
		if (isset($postArray["txtAmbientes_P"]) && $postArray["txtAmbientes_P"] != "") {
			if (!is_numeric($postArray["txtAmbientes_P"])) {
				$this->errores .= "<li>La cantidad de Ambientes se debe expresar en NUMEROS</li>";
			}
		}
		if (stristr($this->errores, "<li>") !== false) {
			$this->errores .= "</ul>";
			return false;
		}
		else {
			$this->setSearchParams( $idI, $postArray );
			return true;
		}

	}



} // Fin de Clase

?>