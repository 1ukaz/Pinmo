// Javascript Document

$(document).ready(function(){

	showMsg('Realice las operaciones necesarias con Las Imagenes. Recuerde que el MAXIMO son 18<br />Y PRESIONE GRABAR para Guardar los CAMBIOS', 'highlight-alert', false);

	$("#btnOpenRadApp")
		.hover(
			function() { $(this).addClass('ui-state-hover'); }, 
			function() { $(this).removeClass('ui-state-hover'); }
		)
		.click(function(ev) {
		var Url = $(this).attr("href");
		if (Url.indexOf("#") == -1) {
			var $theDialog = $("<div />").attr("id", "ModalDialogApp");
			$theDialog
				.load(Url)
				.dialog({
					resizable:false,
					width: 400,
					height: 400,
					position: "center",
					modal: true,
					closeOnEscape: false,
					open: function(event, ui) { $("#ModalDialogApp").siblings(".ui-dialog-titlebar").css("display", "none"); },
					buttons: { "Cerrar": function() { $(this).dialog("destroy").remove(); }	}
				});
				$theDialog.dialog('open');
		}
		ev.preventDefault();
	});

	$.get( "./propiedadPict.php?acc=rf", function(res){ 
		$("#galleryContainer").html(res);
		if ($("#galleryContainer > #gallery").children("li").length >= 18) {
			$("<span />").addClass("ui-icon ui-icon-minus").appendTo($("#btnOpenRadApp").text("Galeria Completa").addClass("ui-state-disabled"));
			$("#btnOpenRadApp").attr("href", "#");
		}
		else {
 			$("<span />").addClass("ui-icon ui-icon-plus").appendTo($("#btnOpenRadApp").text("Agregar Imagenes").removeClass("ui-state-disabled"));
			$("#btnOpenRadApp").attr("href", "./propiedadPict.php?acc=ra");
		}
	});

}); // Fin ready function

var upload = 0;

/**
 * the response returned by the server will be passed as a parameter (s) to this
 * function. However in the case of Netscape the parameter will be empty. When using
 * netscape call the getResponse()method of the applet to access this information.
 *
 */

function uploadCompleted(s) {
	upload = $("#rup").get(0).getUploadStatus();
	if(upload == 1) {
		var responseText = $("#rup").get(0).getResponse();
		$("#ModalDialogApp").dialog("destroy").remove();
		$.get( "./propiedadPict.php?acc=rf", function(res){			
			if (responseText.indexOf("<li>") >= 0) showMsg(responseText, "error-alert", true);
			else if (responseText.indexOf("Atencion!") >= 0) showMsg(responseText, "warning-alert", true);
			else showMsg(responseText, "succesfull-alert", false);
			$("#galleryContainer").html(res);
			if ($("#galleryContainer > #gallery").children("li").length >= 18) {
				$("<span />").addClass("ui-icon ui-icon-minus").appendTo($("#btnOpenRadApp").text("Galeria Completa").addClass("ui-state-disabled"));
				$("#btnOpenRadApp").attr("href", "#");
			}
			else {
				$("<span />").addClass("ui-icon ui-icon-plus").appendTo($("#btnOpenRadApp").text("Agregar Imagenes").removeClass("ui-state-disabled"));
				$("#btnOpenRadApp").attr("href", "./propiedadPict.php?acc=ra");
			}
		});
	}
	else {
		showMsg("La subida de Archivos ha FALLADO", "error-alert", true);
	}
	
	return true;
}
