// JavaScript Document

$(".pagLink").bind("click", function(){
	var href = $(this).attr("href");
	var car = '?';
	if (href.indexOf('?') > 0) car = '&';
	href += car + Math.floor(Math.random()*11111111111111111111); //Math.random();
	$.get( href, function(data){ $("#Mostrar_Contenido").empty().html(data); } );
	return false;
});

$(".reMsgLink, .deMsgLink").bind("click", function(evt) {
	var href = $(this).attr("href");
	if ($(evt.target).parent().is(".reMsgLink")) {
		var $elResponse = $("<textarea></textarea>").attr({"id":"msgResponse", "rows":"13", "cols":"55"}).val("En Respuesta a Su Consulta, le comentamos que:");
		var $mod = $("<div />").addClass("msgRespModal").append($elResponse).appendTo("body");
		$mod.dialog({
			title: "Escriba su Respuesta al Mensaje",
			resizable:false,
			width: 400,
			height: 300,
			modal: true,
			closeOnEscape: false,
			close: function(event, ui){ $(this).dialog("destroy").remove(); },
			buttons: {  "Cerrar": function() { $("#jGrowl").children().trigger("jGrowl.close"); $(this).dialog("destroy").remove(); },
						"Enviar": function() { sendResponse(href, $(this));	}
			}
		});
	}
	else if ($(evt.target).parent().is(".deMsgLink")) {
		$legend = $("<p />").html("Esta Seguro de Eliminar El Mensaje?<br /> Esta Accion es irreversible").append($("<span />").addClass("ui-icon ui-icon-alert"));
		var $mod = $("<div />").addClass("msgRespModal").append($legend).appendTo("body");
		$mod.dialog({
			title: "Confirmacion de Eliminacion",
			resizable: false,
			height:150,
			modal: true,
			closeOnEscape: false,
			open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); },
			buttons: {
				"Cancelar": function() { $(this).dialog("destroy").remove(); },
				"Eliminar": function() {
					$.get(href, function(res){ 
						$("#Mostrar_Contenido").empty().html(res); 
						showMsg("El Mensaje fue Eliminado", "highlight-alert", false); 
					});
					$(this).dialog("destroy").remove();
				}
			}
		});
	}
	return false;
});

$('#btnRefresh')
	.hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	)
	.click(function(ev){
		$.get($(this).attr("href"), function(res){ $("#Mostrar_Contenido").empty().html(res); });
		ev.preventDefault();	
	});

var sendResponse = function (href, $modal) {
	var t = $("textarea#msgResponse").val();
	if (t.length > 0) { 
		$.get(href, {b:t}, function(res){ 
				if (res.indexOf("<li>") >= 0) { 
					showMsg(res, "error-alert", true);
				}
				else {
					$("#Mostrar_Contenido").empty().html(res);
					showMsg("Su Respuesta fue Enviada", "succesfull-alert", false);  
				}	
		});
		$("#jGrowl").children().trigger("jGrowl.close");
		$modal.dialog("destroy").remove(); 
	}
	else
		showMsg("<ul style='margin:0px;padding:0px;'><li>Debe escribir una Respuesta</li></ul>", "error-alert", true);
}