// Javascript Document

$(document).ready(function(){

	cont = 0;

	showMsg("Realice las operaciones necesarias con Las Reservas<br />Y PRESIONE GRABAR para Guardar los CAMBIOS", "highlight-alert", false);

	$.datepicker.setDefaults($.datepicker.regional["es"]);

	$("#btnAddReserva")
		.hover(
			function() { $(this).addClass("ui-state-hover"); }, 
			function() { $(this).removeClass("ui-state-hover"); }
		)
		.click(function(ev) {
			$("#frmDisponibilidad").submit();
			ev.preventDefault();
	});

	$("#reservasGuardadas").fadeIn("slow").load("./propiedadDisp.php?acc=ls", function() {
		$(".delButton")
			.hover(
				function() { $(this).addClass("ui-state-hover"); }, 
				function() { $(this).removeClass("ui-state-hover"); }
			)
			.live("click", function(ev){
				$($(this).closest("tr")).fadeOut(function (){ $(this).remove(); });
				$.get( $(this).attr("href"), function(response){ $("#reservasGuardadas").empty().html(response); cont++; } );
				return false;			
			});
		});

	$("#frmDisponibilidad").validate({
		submitHandler: function(form) {
			$.ajax({
				type: "POST",
				url: $(form).attr("action"),
				data: $(form).serialize(),
				success: function(response) {
					$("#reservasGuardadas").empty().html(response);
					$(".delButton", "#reservasGuardadas").hover(
						function() { $(this).addClass("ui-state-hover"); }, 
						function() { $(this).removeClass("ui-state-hover"); }
					);
					if (response.indexOf("<li>") == -1) {
						cont++;
						$(":input", "#frmDisponibilidad").not(":button, :submit, :reset, :hidden").val("").removeClass("error");
					}
				},
				error: function(xhr, status) { showMsg(status + ": " + xhr.responseText, "error-alert", true); }
			});
			$("#jGrowl").children().trigger("jGrowl.close");
			$("#aReservaDesde, #aReservaHasta").datepicker("option", {minDate: null, maxDate: null});
		},
		onfocusout: false,
		focusInvalid: false,
		onkeyup: false,
		focusCleanup: true,
		showErrors: function(errorMap, errorList) {		
			var list = $("<ul>").css({"margin":"0px", "padding":"5px"});
			$.each(errorList, function(i, err) {
				if (err.message != "") {		
					$(err.element).addClass("error");
					$("<li>").text(err.message).appendTo(list);
				}
			});
					
			if (list.children().length > 0) {
				$("#jGrowl").children().trigger("jGrowl.close");
				showMsg(list.html(), "error-alert", true);			
			}
		},
		rules: {
			aReservaDesde: { required:true },
			aReservaHasta: { required:true }
		},
		messages: {
			aReservaDesde: { required: "La Fecha Desde para la Reserva es Requerida" },
			aReservaHasta: { required: "La Fecha Hasta para la Reserva es Requerida" }
		}
	});
	
    $("#aReservaDesde, #aReservaHasta").datepicker({
        showOn: "both",
		beforeShow:	customPickRules,
        buttonImage: "../../css/images/calendar/calendar.png",
        buttonImageOnly: true, 
		changeMonth: true,
		changeYear: true,
		showAnim: 'show',
		buttonText: "Seleccione Fecha"
    });
	
	$("input:text")
		.focus(function(){ $(this).val("").css({"backgroundColor":"#FFFFF4"}); })
		.blur(function(){ $(this).css({"backgroundColor":""}); });

}) // End ready function

function customPickRules(input) { 
	//var min = new Date(); // Asi impedis que se selccionen dias anteriores a hoy :)
    //var dateMin = min;
	var dateMin = null;
    var dateMax = null;
    var dayRange = 1;

	if (input.id == "aReservaDesde") {
		if ($("#aReservaHasta").datepicker("getDate") != null) {
			dateMax = $("#aReservaHasta").datepicker("getDate");
			dateMax.setDate(dateMax.getDate() - dayRange);
		 }
	}
	else if (input.id == "aReservaHasta") {
		if ($("#aReservaDesde").datepicker("getDate") != null) {
			dateMin = $("#aReservaDesde").datepicker("getDate");
			dateMin.setDate(dateMin.getDate() + dayRange);
		}
	}
	/*
	if (dateMin < min) {
		dateMin = min;
	}
	*/
    return { minDate: dateMin, maxDate: dateMax }; 
}
