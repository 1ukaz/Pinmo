function abrirPopup (url) {
	var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, width=800, height=600";
	window.open(url,"",opciones);
}

function setAccion( accion ){
	document.getElementById('accion').value = accion;
}

function setModulo( modulo ){
	//alert(modulo);
	document.getElementById('modulo').value = modulo;
}

function setPropiedad( idPropiedad ){
	document.getElementById('idPropiedad').value = idPropiedad;
}

function setInmobiliaria( idInmob ) {
	if (idInmob) {
		document.getElementById('idInmobiliaria').value = idInmob;
	}
}

function procesarAccion( modulo, accion, idPropiedad, idInmobiliaria ){
	setModulo(modulo);
	setAccion(accion);
	setPropiedad(idPropiedad);
	setInmobiliaria(idInmobiliaria);
	document.getElementById('form1').submit();
	
}


function mostrarElemento( id ){
	document.getElementById(id).style.display = ''; //es como block pero funciona tamb en ie
}

function ocultarElemento( id ){
	document.getElementById(''+ id +'' ).style.display = 'none';
}

function flipElementById(  id ){
			
	if( document.getElementById(''+ id +'').style.display == ''){
		ocultarElemento('' + id +'')	;
	}
	else{
		mostrarElemento('' + id + '')	;
	}
	
}
	
function limpiarFiltros(){
	document.getElementById("txtAntiguedad").value = '';
	document.getElementById("txtAmbientes").value = '';
	document.getElementById("txtSuperficieTotal").value = '';
	document.getElementById("txtNumero").value = '';
	document.getElementById("txtCalle").value = '';
	document.getElementById("txtCodigoReferencia").value = '';
	document.getElementById("cboTipoPropiedad").value = '';	
	document.getElementById("cboLocalidad").value = '';
	document.getElementById("cboProvincia").value = '';
	document.getElementById("txtPrecio").value = '';
	document.getElementById("cboTipoPublicacion").value = '';
}



function filtrarListado(moduloActual){
	
	var error= validarCriterios();
	
	if( error == '' ){
		procesarAccion(moduloActual,'filtrar','');
	}
	else{
		alert(error);
	}
}

/*validaciones del buscador del panel de control*/
function validarCriterios(){
	
	var error= '';
	
	if( isNaN(document.getElementById("txtPrecio").value)){
		error = "El campo 'Precio' debe ser numérico!\n" ;
	}
	
	if(isNaN(document.getElementById("txtSuperficieTotal").value)){
		error += "El campo 'Superficie Total' debe ser numérico!\n";
	}
	
	if(isNaN(document.getElementById("txtAntiguedad").value)){
		error += "El campo 'Antiguedad' debe ser numérico!\n";
	}
	
	if(isNaN(document.getElementById("txtAmbientes").value)){
		error += "El campo 'Ambientes' debe ser numérico!\n";
	}
	
	return error;
}




function guardarDatosPropiedadYEditar(moduloActual, propiedad){
	
	var error= validarForms();
	
	if( error == ''){
		procesarAccion(moduloActual,'guardar_y_editar', propiedad);
	}
	else{
		alert(error);
	}
}




function guardarDatosPropiedad(moduloActual, propiedad){
	
	var error= validarForms();
	
	if( error == ''){
		procesarAccion(moduloActual,'guardar', propiedad);
	}
	else{
		alert(error);
	}
}


function validarForms(){
	
	var error= '';
	
	if(isNaN(document.getElementById("cboTipoPropiedad_ABM").value)){
		error = "El campo 'Datos Generales: Tipo de propiedad' es obligatorio!\n";
	}

	if(isNaN(document.getElementById("cboProvincia_ABM").value)){
		error += "El campo 'Datos Generales: Provincia' es obligatorio!\n";
	}
	
	if(isNaN(document.getElementById("cboLocalidad_ABM").value)){
		error += "El campo 'Datos Generales: Localidad' es obligatorio!\n";
	}
	
	if( isNaN(document.getElementById("txtPiso_ABM").value) && document.getElementById("txtPiso_ABM").value !='' ){
		error = "El campo 'Datos Generales: Piso' debe ser un valor numérico! Si es PB ingrese cero. \n" ;
	}

	if( isNaN(document.getElementById("txtSuperficieTotal_ABM").value) && document.getElementById("txtSuperficieTotal_ABM").value !=''){
		error += "El campo 'Datos Generales: Superficie Total' debe ser numérico!\n";
	}	
	
	if(isNaN(document.getElementById("txtSuperficieCubierta_ABM").value) && document.getElementById("txtSuperficieCubierta_ABM").value !=''){
		error += "El campo 'Datos Generales: Superficie Cubierta' debe ser numérico!\n";
	}
	
	if(isNaN(document.getElementById("txtSuperficieDesCubierta_ABM").value) && document.getElementById("txtSuperficieDesCubierta_ABM").value !=''){
		error += "El campo 'Datos Generales: Superficie Descubierta' debe ser numérico!\n";
	}	
	
	if(isNaN(document.getElementById("txtSuperficieSemiCubierta_ABM").value) && document.getElementById("txtSuperficieSemiCubierta_ABM").value !=''){
		error += "El campo 'Datos Generales: Superficie Semi-Cubierta' debe ser numérico!\n";
	}	
	
	if(isNaN(document.getElementById("txtAntiguedad_ABM").value) && document.getElementById("txtAntiguedad_ABM").value !=''){
		error += "El campo 'Datos Generales: Antiguedad' debe ser numérico!\n";
	}
	
	if(isNaN(document.getElementById("txtAmbientes_ABM").value) && document.getElementById("txtAmbientes_ABM").value !=''){
		error += "El campo 'Datos Generales: Ambientes' debe ser numérico!\n";
	}
	
	/*publicacion venta*/
	if(isNaN(document.getElementById("txtPrecioPublicacion_ABM[0]").value) && document.getElementById("txtPrecioPublicacion_ABM[0]").value !=''){
		error += "El campo 'Publicación: Precio de Venta' debe ser numérico entero, sin puntos ni comas!\n";
	}
	/*publicacion alquiler*/
	if(isNaN(document.getElementById("txtPrecioPublicacion_ABM[1]").value) && document.getElementById("txtPrecioPublicacion_ABM[0]").value !=''){
		error += "El campo 'Publicación: Precio de Alquiler' debe ser numérico entero, sin puntos ni comas!\n";
	}
	
	/*publicacion alquiler temp*/
	if(isNaN(document.getElementById("txtPrecioPublicacion_ABM[2]").value) && document.getElementById("txtPrecioPublicacion_ABM[0]").value !=''){
		error += "El campo 'Publicación: Precio de Alquiler Temporario' debe ser numérico entero, sin puntos ni comas!\n";
	}
	
	
	
	/*reemplaza comas por puntos*/
	s = document.getElementById("txtComision_ABM[0]").value;
	document.getElementById("txtComision_ABM[0]").value = s.replace(",",".");
	
	s = document.getElementById("txtComision_ABM[1]").value;
	document.getElementById("txtComision_ABM[1]").value = s.replace(",",".");
	
	s = document.getElementById("txtComision_ABM[2]").value;
	document.getElementById("txtComision_ABM[2]").value = s.replace(",",".");
	
	
	
	/*publicacion comision venta*/
	if(isNaN(document.getElementById("txtComision_ABM[0]").value) && document.getElementById("txtComision_ABM[0]").value !=''){
		error += "El campo 'Publicación: Comisión de Venta' debe ser numérico entero!\n";
	}
	/*publicacion comision alquiler*/
	if(isNaN(document.getElementById("txtComision_ABM[1]").value) && document.getElementById("txtComision_ABM[0]").value !=''){
		error += "El campo 'Publicación: Comisión de Alquiler' debe ser numérico !\n";
	}
	
	/*publicacion comision alquiler temp*/
	if(isNaN(document.getElementById("txtComision_ABM[2]").value) && document.getElementById("txtComision_ABM[0]").value !=''){
		error += "El campo 'Publicación: Comisión de Alquiler Temporario' debe ser numérico!\n";
	}
	
	
	
	return error;
}


