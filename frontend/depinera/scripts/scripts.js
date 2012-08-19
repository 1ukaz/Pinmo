function abrirPopup (url) {
	var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes,  top=85, left=140";
	window.open(url,"",opciones);
}

function setAccion( accion ){
	document.getElementById("accion").value = accion;
}

function setModulo( modulo ){
	document.getElementById("modulo").value = modulo;
}

function setPropiedad( idPropiedad ){
	document.getElementById("propiedad").value = idPropiedad;
}


function buscar( modulo, accion ){
	document.getElementById("b_modulo").value = modulo;
	document.getElementById("b_accion").value = accion;
	document.getElementById("formBuscador").submit();
}

function procesarAccion( modulo, accion, propiedad ){

	setModulo(modulo);
	setAccion(accion);
	setPropiedad(propiedad);
	document.getElementById("formNav").submit();
}


function cambiarIdioma( objSelect ){
	document.getElementById("idioma").value = objSelect.value;
	document.getElementById("formNav").submit();
}

function cambiarNodo( valor ){

	document.getElementById("accion").value = '';
	document.getElementById("modulo").value = valor;
	document.getElementById("formNav").submit();
}

function mostrarElemento( id ){
	document.getElementById(id).style.display = ''; //es como block pero funciona tamb en ie
}

function ocultarElemento( id ){
	document.getElementById(id).style.display = 'none';
}

function flipElementById(  id ){
			
	if( document.getElementById(id).style.display == ''){
		ocultarElemento(id)	;
	}
	else{
		mostrarElemento(id)	;
	}
}


function switchCapa( capa ){

	var strCapa ="";

	//oculto las 4 capas
	for(i=1; i<5; i++){
		strCapa = "capaPaso"+i;
		ocultarElemento(strCapa);

	}

	//muestro la capa seleccionada
	strCapa = "capaPaso" + capa;
	mostrarElemento( strCapa );

}

function setCategoria(categoria){
	
	document.getElementById("categoria").value = categoria;
}

function setIdCriterio(idCriterio){
	
	document.getElementById("idCriterio").value = idCriterio;
}

function cambiarCategoria(categoria, modulo, accion){
	
	setCategoria(categoria);
	procesarAccion( modulo, accion, '' );
}

function eliminarFiltro(modulo, accion, idElemento, valor){
	
	document.getElementById(""+idElemento+"").value = valor;
	procesarAccion( modulo, accion, '' );
}