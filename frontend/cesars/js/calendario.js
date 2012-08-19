function setFechaDesde( fecha ){
	document.getElementById('txtFechaDesde').values = fecha;
}

function setFechaHasta( fecha ){
	document.getElementById('txtFechaHasta').values = fecha;
}

function setCliente( cliente ){
	document.getElementById('txtCliente').values = cliente;
}

function setMesInicia ( mes ){
	document.getElementById('mesInicial').values = mes;
}

function setAnioInicial ( ano ){
	document.getElementById('anoInicial').values = ano;
}



function confirmarEliminarReserva( modulo, propiedad){

		if(confirm("Esta seguro de borrar la reservas seleccionadas?")){
			
			procesarAccion(modulo,'eliminar',propiedad)
			
			document.form1.submit();
		}
	
}

function verTrimestreSiguiente(modulo,propiedad){
	procesarAccion(modulo,'siguiente',propiedad)
	document.form1.submit();	
}

function verTrimestreAnterior(modulo,propiedad){
	procesarAccion(modulo,'anterior',propiedad)
	document.form1.submit();
}

function agregarReserva(modulo, idPropiedad){
	
	
	mostrarElemento('capaCargaReserva');
	mostrarElemento('btnGuardar');
	mostrarElemento('btnEditar');
	
	/*
	ocultarElemento('capaListadoReservas');
	ocultarElemento('btnEliminar');
	ocultarElemento('btnAgregar');
	*/
		
	//procesarAccion(modulo,'agregar',idPropiedad);
}

function editarReserva(modulo,idPropiedad){	
	
	mostrarElemento('capaListadoReservas');
	mostrarElemento('btnEliminar');
	mostrarElemento('btnAgregar');
	
	//ocultarElemento('capaCargaReserva');
	//ocultarElemento('btnEditar');
	//ocultarElemento('btnGuardar');
}
	

function guardarDatosReserva(moduloActual, propiedad){
	
	var error= validarFormsCalendario();
	
	if( error == ''){
		procesarAccion(moduloActual,'guardar', propiedad);
	}
	else{
		alert(error);
	}
}


function validarFormsCalendario(){
	
	var error= '';
	
	var fechaDesde = document.getElementById("txtReservaDesde").value;
	var fechaHasta = document.getElementById("txtReservaHasta").value;
	var idCliente = document.getElementById("txtIdCliente").value;
	
	
	var diad = fechaDesde.substring(0,2);
	var mesd = fechaDesde.substring(3,5);
	var aniod = fechaDesde.substring(7,10);
	
	var diah = fechaDesde.substring(0,2);
	var mesh = fechaDesde.substring(3,5);
	var anioh = fechaDesde.substring(7,10);
	
	
	if(fechaDesde.length !=10){
		error = "El campo Calendario: 'Fecha Desde' debe estar en formato dd-mm-aaaa !\n";
	}

	if(fechaHasta.length !=10){
		error += "El campo Calendario: 'Fecha Hasta' debe estar en formato dd-mm-aaaa !\n";
	}

	if( isNaN(diad) || isNaN(mesd) || isNaN(aniod)){
		error += "El campo Calendario: 'Fecha Desde' no es válido !\n";
	}
	
	if( isNaN(diah) || isNaN(mesh) || isNaN(anioh)){
		error += "El campo Calendario: 'Fecha Hasta' no es válido !\n";
	}
	
	
	
	return error;
}
	
	
