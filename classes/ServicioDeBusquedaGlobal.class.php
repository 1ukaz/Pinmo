<?php

class ServicioDeBusquedaGlobal extends ServicioDeBusqueda {

	
	
	public function searchPropertiesForList( $orden, $pagina, $idInmo, $idLog, $aPostArray ) {
		if ($this->validateParamsOnServerSide( $idInmo, $aPostArray )) {	
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
		$this->busCodigo = $postArray["txtCodigoReferencia_G"];
		$this->busTipoInmueble = $postArray["cboTipoPropiedad_G"];
		$this->busProvincia = $postArray["cboProvincia_G"];
		$this->busLocalidad = $postArray["cboLocalidad_G"];
		$this->busTipoPublicacion = $postArray["cboTipoPublicacion_G"];
		$this->busSuperficie = addslashes(trim($postArray["txtSuperficieTotal_G"]));
		$this->busCalle = addslashes(trim($postArray["txtCalle_G"]));
		$this->busNumero = addslashes(trim($postArray["txtNumero_G"]));
		$this->busAntiguedad = addslashes(trim($postArray["txtAntiguedad_G"]));
		$this->busPrecio = addslashes(trim($postArray["txtPrecio_G"]));
		$this->busAmbientes = addslashes(trim($postArray["txtAmbientes_G"]));
	}

	public function validateParamsOnServerSide( $idI, $postArray ) {
		$this->errores = "<ul style=\"margin:0px;padding:5px;\">";
		if (isset($postArray["txtSuperficieTotal_G"]) && $postArray["txtSuperficieTotal_G"] != "") {
			if (!is_numeric($postArray["txtSuperficieTotal_G"])) {
				$this->errores .= "<li>La Superficie se debe expresar en NUMEROS</li>";
			}
		}
		if (isset($postArray["txtNumero_G"]) && $postArray["txtNumero_G"] != "") {
			if (!is_numeric($postArray["txtNumero_G"])) {
				$this->errores .= "<li>El Numero de Calle debe ser un NUMERO</li>";
			}
		}
		if (isset($postArray["txtAntiguedad_G"]) && $postArray["txtAntiguedad_G"] != "") {
			if (!is_numeric($postArray["txtAntiguedad_G"])) {
				$this->errores .= "<li>Los Años de Antiguedad se deben expresar en NUMEROS</li>";
			}
		}
		if (isset($postArray["txtPrecio_G"]) && $postArray["txtPrecio_G"] != "") {
			if (!is_numeric($postArray["txtPrecio_G"]) || strpos($postArray["txtPrecio_G"], ".") || strpos($postArray["txtPrecio_G"], ",")) {
				$this->errores .= "<li>El Precio debe ser un valor NUMERICO [Sin signo $, ni puntos o comas]</li>";
			}
		}
		if (isset($postArray["txtAmbientes_G"]) && $postArray["txtAmbientes_G"] != "") {
			if (!is_numeric($postArray["txtAmbientes_G"])) {
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