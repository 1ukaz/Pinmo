<?php

class Imagen {

	private $idImagen;
	private $idDescripcion;
	private $descripcion;
	private $archivo;
	private $orden;
	private $marca = false;
	
	private $img_dir;
	private $thumb_dir;

	//const IMG_PHYSICAL_PATH = "z:\\Server\\xampp\\htdocs\\devs\\pinmo-2.com.ar\\pictures\\imagenes\\";
	//const THB_PHYSICAL_PATH = "z:\\Server\\xampp\\htdocs\\devs\\pinmo-2.com.ar\\pictures\\thumbnails\\";
	
	const IMG_PHYSICAL_PATH = "/www/pinmo.com.ar/htdocs/pictures/imagenes/";
	const THB_PHYSICAL_PATH = "/www/pinmo.com.ar/htdocs/pictures/thumbnails/";

	
	
	public static function createImagen($idImagen = '', $idDescripcion = 0, $aDescripcion = '', $archivo = '', $orden = '') {

		$objeto = new self;
		$objeto->idImagen = $idImagen;
		$objeto->idDescripcion = $idDescripcion;
		$objeto->descripcion = trim(addslashes($aDescripcion));
		$objeto->archivo = trim(addslashes($archivo));
		$objeto->orden = intval($orden);
		
		$objeto->img_dir   = "http://www.pinmo.com.ar/pictures/imagenes/";					//Ruta absoluta para abrir la imagen
		$objeto->thumb_dir = "http://www.pinmo.com.ar/pictures/thumbnails/";					//Ruta absoluta para abrir la imagen
		
		//$objeto->img_dir   = "http://localhost/devs/pinmo-2.com.ar/pictures/imagenes/";			//Ruta absoluta para abrir la imagen
		//$objeto->thumb_dir = "http://localhost/devs/pinmo-2.com.ar/pictures/thumbnails/";			//Ruta absoluta para abrir la imagen

		return $objeto;
	}

	public static function getPropAssociatedImagesFromDB( $idPropiedad, $all ) {
		$aImagenes = array();
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql  = "SELECT I.idImagen, I.idPropiedad, I.archivo, I.orden, I.descripcion AS ID, TH.descripcion AS DE FROM Imagenes I ";
		$sql .= "LEFT OUTER JOIN TiposHabitacion TH ON I.descripcion = TH.idTipoHabitacion WHERE I.idPropiedad = {$idPropiedad} ORDER BY orden ASC ";
		
		if(!$all) 
			$sql .= "LIMIT 0,1";

		$result = $pinmo->getDb( )->Execute( $sql );
		if (is_array($result) && !empty($result)) {
			foreach ($result as $row) {
				$aImagenes[] = self::createImagen($row['idImagen'], $row['ID'], $row['DE'], $row['archivo'], $row['orden']);
			}
		}
		return $aImagenes;

		$pinmo->getDb( )->close( );
	}




	public static function uploadImagesAndAssign( $oProp, $filesArray ) {
		

		$k = count($filesArray["name"]);

		for($i = 0 ; $i < $k ; $i++) {
			$name = explode('/',$filesArray['name'][$i]); //split no se usa mas esta deprecated!
			$fileNameToSave = "{$oProp->getIdPropiedad()}-".date("YmdGis")."-$i.".substr($name[count($name)-1], strlen($name[count($name)-1])-3,3);
			if (!move_uploaded_file($filesArray['tmp_name'][$i], self::IMG_PHYSICAL_PATH . $fileNameToSave)) {
				$oProp->error .= "<li>Ha ocurrido un Error en la subida de las Imagenes<br /> Intente Nuevamente Luego</li></ul>";
				return false;
			}
			$oImagen = self::createImagen('', '', '', rawurlencode("$fileNameToSave"), $j);
			$oImagen->crearThumbnail(self::IMG_PHYSICAL_PATH . $oImagen->archivo, self::THB_PHYSICAL_PATH . $oImagen->archivo);
			$oProp->setImagenesArrayMember(count($oProp->getImagenes()), $oImagen);
			$j++;
			
		}
		return true;
	}
	
	public static function eliminarImagenesDePropiedad($idPropiedad) {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );
		
		$result = $pinmo->getDb( )->Execute( "SELECT idImagen, archivo FROM Imagenes WHERE idPropiedad = {$idPropiedad}" );
		if (is_array($result) && !empty($result)) {
			foreach ($result as $row) {
				$objeto = new self;
				$objeto->idImagen = $row["idImagen"];
				$objeto->archivo = trim(addslashes($row["archivo"]));
				if(!$objeto->deleteArchivosImagen())
					return false;
				if(!$objeto->deleteImagen())
					return false;
				unset($objeto);
			}
		}
		return true;

		$pinmo->getDb( )->close( );			
	}

	public function saveToDB( $idPropiedad ) {
		if ($this->idImagen) {
			if ($this->marca === false) {
				if (!$this->updateImagen($idPropiedad)) return false;
			}
			else {
				if (!$this->deleteImagen()) return false;
				if (!$this->deleteArchivosImagen()) return false;
			}
			return true;
		}
		else {
			if ($this->marca === false) {
				if (!$this->insertImagen($idPropiedad)) return false;
			}
			else {
				if (!$this->deleteArchivosImagen()) return false;
			}
		}
		return true;
	}

	public function deleteArchivosImagen() {
		$fileToDelete = self::IMG_PHYSICAL_PATH . $this->archivo;
		$thumToDelete = self::THB_PHYSICAL_PATH . $this->archivo;

		if (file_exists("{$fileToDelete}") && file_exists("{$thumToDelete}")) {
			@unlink("{$fileToDelete}"); 
			@unlink("{$thumToDelete}"); 
		}
		return true;
	}

	private function crearThumbnail($archivo_origen, $archivo_destino, $ancho = 150, $alto = 150, $calidad = 80) {
		$original = imagecreatefromjpeg("$archivo_origen");
		$thumb = imagecreatetruecolor($ancho, $alto); 
				
		//Ahora necesitamos saber de que tama�o es la imagen original:
		$oancho = imagesx($original);
		$oalto = imagesy($original);
		
		//A continuacion vamos a copiar la imagen original en la imagen en miniatura:
		imagecopyresampled($thumb, $original, 0, 0, 0, 0, $ancho, $alto, $oancho, $oalto);
		
		//Por ultimo, guardamos la imagen en disco:
		imagejpeg($thumb, "$archivo_destino", $calidad); // 80 es la calidad de compresi�n
	}

	private function updateImagen( $idPropiedad ) {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql  = "UPDATE Imagenes SET descripcion = '{$this->idDescripcion}', orden = {$this->orden} ";
		$sql .= "WHERE idImagen = {$this->idImagen} AND idPropiedad = {$idPropiedad}";

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );
	}

	private function insertImagen( $idPropiedad ) {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql  = "INSERT INTO Imagenes(idPropiedad, descripcion, archivo, orden) VALUES( ";
		$sql .= "{$idPropiedad}, '{$this->idDescripcion}', '{$this->archivo}', {$this->orden})";

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );

	}

	private function deleteImagen() {
		$pinmo = Pinmo::getInstance();
		$pinmo->getDb( )->open( );

		$sql  = "DELETE FROM Imagenes WHERE idImagen = '{$this->idImagen}'";

		$query = $pinmo->getDb( )->ExecuteNotSelection( $sql );

		if ($query) return true;
		else return false;

		$pinmo->getDb( )->close( );
		
	}

	
	// GETTERS
	
	public function getIdDescripcion( )			{ return $this->idDescripcion; }
	public function getDescripcion( )			{ return $this->descripcion; }
	public function getArchivo( )				{ return $this->archivo; }
	public function getIdPropiedad( )			{ return $this->idPropiedad; }
	public function getIdImagen( )				{ return $this->idImagen; }
	public function getOrden( )					{ return $this->orden; }
	public function getMarca( )					{ return $this->marca; }
	public function getPath( )					{ return $this->img_dir; }
	public function getThumbsPath()				{ return $this->thumb_dir; }
	public function getImgPhisicalPath( )		{ return $this->imgPhisicalPath; }
	public function getThbPhisicalPath( )		{ return $this->thbPhisicalPath; }
	public function getImgFullPath( )			{ return ($this->img_dir . $this->archivo); }
	public function getThumbFullPath( )			{ return ($this->thumb_dir . $this->archivo); }
	public function getImgFullPhisicalPath( )	{ return ($this->imgPhisicalPath . $this->archivo); }
	public function getThbFullPhisicalPath( )	{ return ($this->thbPhisicalPath . $this->archivo); }
	
	
	// PUBLIC SETTERS

	public function setIdDescripcion( $var ) { $this->idDescripcion = $var; }
	public function setDescripcion( $var )	 { $this->descripcion = $var; }
	public function setArchivo( $var )		 { $this->archivo = $var; }
	public function setIdPropiedad( $var )	 { $this->idPropiedad = $var; }
	public function setIdImagen( $var )		 { $this->idImagen = $var; }
	public function setOrden( $var )		 { $this->orden = $var; }
	public function setMarca( $var )		 { $this->marca = $var; }

	
} // Fin de Clase

?>