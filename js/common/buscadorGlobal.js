// JavaScript Document

$(document).ready(function() {

	showMsg('Seleccione los Criterios para realizar La B&uacute;squeda', 'highlight-alert', false);

	$(document).keyup(function(event) {
		if(event.keyCode == 13) {
			$("#btnSubmitGlobal").trigger("click");
		}
		else if (event.keyCode == 27) {
			$("#btnCancelGlobal").trigger("click");
		}
	});

	$("<option>").attr("value", 0).text("Seleccione Localidad").appendTo("#cboLocalidad_G");
	$("#cboLocalidad_G").attr('disabled', 'true');

	$("#cboProvincia_G").change(function(){
		$('#cboLocalidad_G').children().remove();
		if (!$(this).val()) {
			$('#cboLocalidad_G').html("<option value='0'>Seleccione Localidad</option>");
			$("#cboLocalidad_G").attr('disabled', 'true');
		}
		else {
			$.getJSON( "combosManager.php?acc=l",{idProv: $(this).val(), ajax: 'true'}, function(aJSON) { loadComboContent(aJSON, $("#cboLocalidad_G")); } );
			$("#cboLocalidad_G").removeAttr("disabled");
		}
	});

	$('#btnSubmitGlobal, #btnCancelGlobal').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	).tooltip({track:true, delay:5000, showURL:false, fade:200});

	$("#btnSubmitGlobal").click(function(ev) {
		$("<span />").addClass("ui-icon ui-icon-search").appendTo($(this).text("Buscando").addClass("ui-state-disabled"));
		$(this).submit();
		ev.preventDefault();
	});	

	$("#frmBusquedaGlobal").validate({
		ignore: ".ignore",
		submitHandler: function(form) {
			$.ajax({
				type: "POST",
				url: $(form).attr("action"),
				data: $(form).serialize(),
				success: function(data) {
					$("#searchResultsGlobal").empty().html(data);
					$("<span />").addClass("ui-icon ui-icon-search").appendTo($("#btnSubmitGlobal").text("Buscar").removeClass("ui-state-disabled"));
				},
				error: function(xhr, status) { showMsg(status + ": " + xhr.responseText, "error-alert", true); }
			});
			$("*[generated='true']").remove();
			$("#jGrowl").children().trigger('jGrowl.close');
		},
		onfocusout: false,
		focusInvalid: false,
		onkeyup: false,
		focusCleanup: true,
		showErrors: function(errorMap, errorList) {		
			var list = $("<ul>").css({"margin":"0px", "padding":"5px"});			
			$(errorList).each(function(i, err) {
				if (err.message != "") {
					$(err.element).addClass("error");
					$("<li>").text(err.message).appendTo(list);
				}
			});
					
			if (list.children().length > 0) {
				$("#jGrowl").children().trigger('jGrowl.close');
				showMsg(list.html(), "error-alert", true);			
				$("<span />").addClass("ui-icon ui-icon-search").appendTo($("#btnSubmitGlobal").text("Buscar").removeClass("ui-state-disabled"));
			}
		},
		rules: {
			txtNumero_G: {
				digits: true,
				minlength: 1,
				maxlength: 5
			},
			txtAmbientes_G: {
				digits: true,
				minlength: 1,
				maxlength: 2
			},
			txtSuperficieTotal_G: {
				digits: true,
				minlength: 1,
				maxlength: 5
			},
			txtAntiguedad_G: {
				digits: true,
				minlength: 1,
				maxlength: 3
			},
			txtPrecio_G: {
				digits: true,
				minlength: 1,
				maxlength: 8
			}
		},
		messages: {
			txtNumero_G: { 
				digits: "El Numero de Calle debe ser un NUMERO, de no mas de 5 Caracteres", 
				maxlength: "El Numero de Calle debe ser un NUMERO, de no mas de 5 Caracteres" 
			},
			txtAmbientes_G: { 
				digits: "La cantidad de Ambientes se debe expresar en NUMEROS, de no mas de 2 Caracteres", 
				maxlength: "La cantidad de Ambientes se debe expresar en NUMEROS, de no mas de 2 Caracteres"
			},
			txtSuperficieTotal_G: { 
				digits: "La Superficie se debe expresar en NUMEROS, de no mas de 5 Caracteres", 
				maxlength: "La Superficie se debe expresar en NUMEROS, de no mas de 5 Caracteres" 
			},
			txtAntiguedad_G: { 
				digits: "Los A\u00f1os de Antiguedad se deben expresar en NUMEROS [Sin punto ni coma], ni mas de 3 Caracteres", 
				maxlength: "Los A\u00f1os de Antiguedad se deben expresar en NUMEROS [Sin punto ni coma], ni mas de 3 Caracteres" 
			},
			txtPrecio_G: { 
				digits: "El Precio debe ser un valor NUMERICO [Sin signo $ ni puntos o comas], de no mas de 8 Caracteres", 
				maxlength: "El Precio debe ser un valor NUMERICO [Sin signo $ ni puntos o comas], de no mas de 8 Caracteres" 
			}
		}
	});

	$("#btnCancelGlobal").click(function(ev) {
		$(":input", "#frmBusquedaGlobal").not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected').removeClass('error');
		$("#jGrowl").children().trigger('jGrowl.close');
		$("#cboLocalidad_G").html("<option value='0'>Seleccione Localidad</option>").attr('disabled', 'true');
		showMsg('Seleccione los Criterios para realizar La B&uacute;squeda', 'highlight-alert', false);
		$("#searchResultsGlobal").empty();
		ev.preventDefault();
	});	

	$("input:text")
		.focus(function(){ $(this).val("").css({"backgroundColor":"#FFFFF4"}); })
		.blur(function(){ $(this).css({"backgroundColor":""}); });
	
}); // End of ready() function
