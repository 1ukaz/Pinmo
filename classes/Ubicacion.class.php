<?php

class Ubicacion {

	private $idLocalidad;
	private $nomLocalidad;
	private $aliasLocalidad;
	private $idProvincia;
	private $nomProvincia;
	private $calle;
	private $numero;
	private $piso;
	private $departamento;
	private $entreCalle1;
	private $entreCalle2;
	private $CPA;
	private $latitud;
	private $longitud;

	
	
	public static function getProvinciasAsOptionArray( $json = 0 ) {
		$pinmo = Pinmo::getInstance();
		$provs = $pinmo->getDb( )->ExecuteTable("Provincias", NULL, "provincia");
		
		$response = "[{\"id\": \"\", \"nombre\": \"Seleccione Provincia\"},";
		$arreglo['output'][] = "Seleccione Provincia";
		$arreglo['values'][] = "";

		foreach ($provs as $prov) {
			$arreglo['output'][] = ucwords(mb_strtolower($prov['provincia'], "UTF8"));
			$arreglo['values'][] = $prov['idProvincia'];
			$response .= "{\"id\": \"".$prov['idProvincia']."\", \"nombre\": \"".ucwords(mb_strtolower($prov['provincia'], "UTF8"))."\"},";
		}
		
		$response = substr($response, 0, strlen($response) - 1);
		$response .= "]";

		if ($json)
			return $response;
		else
			return $arreglo;

		$pinmo->getDb( )->close( );
	}

	public static function getLocalidadesAsOptionArray( $json = 0, $idProv = 0 ) {
		$pinmo = Pinmo::getInstance();
		$locs = $pinmo->getDb( )->ExecuteTable("Localidades", "idProvincia = {$idProv}", "localidad ASC");

		$response = "[{\"id\": \"\", \"nombre\": \"Seleccione Localidad\"},";
		$arreglo['output'][] = "Seleccione Localidad";
		$arreglo['values'][] = "";
		
		if (!empty($locs)) {
			foreach ($locs as $loc) {
				$arreglo['output'][] = ucwords(mb_strtolower($loc['localidad'], "UTF8"));
				$arreglo['values'][] = $loc['idLocalidad'];
				$response .= "{\"id\": \"".$loc['idLocalidad']."\", \"nombre\": \"".ucwords(mb_strtolower($loc['localidad'], "UTF8"))."\"},";
			}
		}
				
		$response = substr($response, 0, strlen($response) - 1);
		$response .= "]";

		if ($json)
			return $response;
		else
			return $arreglo;

		$pinmo->getDb( )->close( );
	}


	// PUBLIC SETTERS

	public function setIdLocalidad( $var )	 { $this->idLocalidad = $var;	}
	public function setNomLocalidad( $var )  { $this->nomLocalidad = ucwords(mb_strtolower($var, "UTF8"));	}
	public function setIdProvincia( $var )	 { $this->idProvincia = $var;	}
	public function setNomProvincia( $var )  { $this->nomProvincia = ucwords(mb_strtolower($var, "UTF8"));	}
	public function setCalle( $var )		 { $this->calle = ucwords(mb_strtolower($var, "UTF8"));			}
	public function setNumero( $var )		 { $this->numero = $var;		}
	public function setPiso( $var )			 { $this->piso = $var;			}
	public function setDepartamento( $var )	 { $this->departamento = strtoupper($var);	}
	public function setEntreCalle1( $var )	 { $this->entreCalle1 = ucwords(mb_strtolower($var, "UTF8"));	}
	public function setEntreCalle2( $var )	 { $this->entreCalle2 = ucwords(mb_strtolower($var, "UTF8"));	}
	public function setCPA( $var )			 { $this->CPA = $var;			}
	public function setLatitud( $var )		 { $this->latitud = $var;		}
	public function setLongitud( $var )		 { $this->longitud = $var;		}
	public function setAliasLocalidad( $var ){$this->aliasLocalidad = ucwords(mb_strtolower($var, "UTF8")); }

	
	// PUBLIC GETTERS

	public function getIdLocalidad( )	 { return $this->idLocalidad;	}
	public function getNomLocalidad( )	 { return $this->nomLocalidad;	}
	public function getIdProvincia( )	 { return $this->idProvincia;	}
	public function getNomProvincia( )	 { return $this->nomProvincia;	}
	public function getCalle( )			 { return $this->calle;			}
	public function getDepartamento( )	 { return $this->departamento;	}
	public function getEntreCalle1( )	 { return $this->entreCalle1;	}
	public function getEntreCalle2( )	 { return $this->entreCalle2;	}
	public function getCPA( )			 { return $this->CPA;			}
	public function getLatitud( )		 { return $this->latitud;		}
	public function getLongitud( )		 { return $this->longitud;		}
	public function getAliasLocalidad( ) { return $this->aliasLocalidad; }
	public function getDomicilioFull( )  { return $this->calle." al ".$this->getNumero(1)." ".$this->getPiso(1)." ".$this->departamento; }


	public function getNumero( $readOnly = 0 )   {
		if ($readOnly == 1)
			return ($this->numero) ? intval( $this->numero / 100 ) * 100 : 100;
		else
			return ($this->numero) ? $this->numero : "";
	}

	public function getPiso( $readOnly = 0 )	{
		if ($readOnly == 1) 
			return ($this->piso) ? ($this->piso . "&deg; Piso") : "PB";	
		else
			return ($this->piso) ? $this->piso : "";	
	}


} // Fin de Clase

?>