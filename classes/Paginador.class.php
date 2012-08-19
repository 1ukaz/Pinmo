<?php

class Paginador {

	private $limit;
	private $totalRows = 0;
	private $pageRows = 0;
	private $fillRows = 0;
	private $numPages = 0;
	private $limitValue = 0;
	private $page;
	private $enabled = 1;			// Permite deshabilitar/habilitar el Paginador [0 = Deshabilitado / 1 = Habilitado]
	

	
	public function __construct( $limit = 0 ) {
		$this->limit = ($limit) ? $limit : 20;
	}
	
	public function doPaginado( $filas, $pagina ) {
		$this->setPage( $pagina );
		$this->setTotalRows( $filas );
		$this->setNumPages( );
		$this->setLimitValue( );
	}

	 public function generateComboValues( ) {
		for ($i=1; $i<=$this->numPages; $i++) {
			$vector["output"][] = $i;
			$vector["values"][] = $i;
		}
		return $vector;
	}

	
	// PUBLIC SETTERS 

	public function setTotalRows( $valor ) {
		$this->totalRows = $valor;
	}

	public function setNumPages( ) {
		$this->numPages = (int)($this->totalRows / $this->limit) + (($this->totalRows % $this->limit == 0) ? 0 : 1);
	}

	public function setLimitValue( ) {
		$this->limitValue = $this->limit * ($this->page - 1);
	}
	
	public function setPage( $page = 0 ) {
		if ($page == 0) 
			$this->page = 1;
		else
			$this->page = $page;
	}

	public function setPageRows( $fp ) {
		$this->pageRows = $fp;
		$this->setFillRows( );
	}

	public function setFillRows( ) {
		$this->fillRows = $this->limit - $this->pageRows;
		if ($this->fillRows != 0) {
			$this->fillRows += 1;
		}
	}

	public function setEnabled( $val ) {
		$this->enabled = $val;
	}

	
	// PUBLIC GETTERS

	public function getTotalRows( )					{ return $this->totalRows; }	
	public function getNumPages( )					{ return $this->numPages; }
	public function getLimitValue( )				{ return $this->limitValue; }
	public function getPage( )						{ return $this->page; }
	public function getLimit( )						{ return $this->limit; }
	public function getPageRows( )					{ return $this->pageRows; }
	public function getFillRows( )					{ return $this->fillRows; }
	public function getEstado( )					{ return $this->enabled; }

} // Fin de Clase

?>