/*muestra la imagen referenciada en el div de preview de imagen*/
function seleccionarImagen(id){

	setAccion('editar');
	setIdImagen(id);
	ocultarUploader();
	mostrarPreview(id);
	document.getElementById("capaDatosFoto").style.display = "";
	actualizar();
	
}

function mostrarPreview(idImagen){
	
	//recupero src
	document.getElementById("imagenPreview").src = document.getElementById("thb"+idImagen).src;
	
	//muestro el div de preview
	document.getElementById("imagenPreview").style.display = "";	
	
	//oculto el div de upload
	document.getElementById("capaUploadImagenPreview").style.display = "block";
}

function ocultarUploader(){
	document.getElementById("capaUploadImagenesApplet").style.display = "none";
}

function mostrarUploadApplet(){
	
	//cambio a modo carga de nuevas imagenes
	setAccion('editar');
	
	setIdImagen('');
	
	actualizar();
	/*
	//mostrar upload de imagenes
	document.getElementById("capaUploadImagenesApplet").style.display = "block";
	
	//oculto div de imagen grande
	document.getElementById("capaUploadImagenPreview").style.display = "none";
	*/
}

function confirmarEliminarImagen(modulo, propiedad){

	if( document.getElementById('idImagen').value == '' ){
		alert("Debe seleccionar la imagen que desea eliminar!");
	}
	else{

		if(confirm("Esta seguro de borrar la imagen seleccionada?")){

			procesarAccion(modulo,'eliminar',propiedad)
			document.form1.submit();
		}
	}
}


function setIdImagen(id){
	document.getElementById("idImagen").value = id;
}



function actualizar(){
	document.form1.submit();
}