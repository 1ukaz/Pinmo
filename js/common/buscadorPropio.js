// JavaScript Document

$(document).ready(function(){

	showMsg('Seleccione los Criterios para realizar La B&uacute;squeda', 'highlight-alert', false);

	$(document).bind("keyup", function(event) {
		if(event.keyCode == 13) {
			$("#btnSubmitPropio").trigger("click");
		}
		else if (event.keyCode == 27) {
			$("#btnCancelPropio").trigger("click");
		}
	});

	$("<option>").attr("value", 0).text("Seleccione Localidad").appendTo("#cboLocalidad_P");
	$("#cboLocalidad_P").attr('disabled', 'true');

	$("#cboProvincia_P").change(function(){
		$('#cboLocalidad_P').children().remove();
		if (!$(this).val()) {
			$("#cboLocalidad_P").html("<option value='0'>Seleccione Localidad</option>");
			$("#cboLocalidad_P").attr('disabled', 'true');
		}
		else {
			$.getJSON("combosManager.php?acc=l",{idProv: $(this).val(), ajax: 'true'}, function(aJSON){ loadComboContent(aJSON, $("#cboLocalidad_P")); });
			$("#cboLocalidad_P").removeAttr('disabled');
		}
	});

	$("#btnNuevaProp").click(function(evt){
			var $theDialog = $("<div />").attr("id", "ModalDialog");
			var $theLink = $(this);
			var Url = $theLink.attr("href");
			var newUrl = $theLink.parents($("#tabs")).children("div.searchForm").children("#frmBusquedaPropia").attr("action");
			var p = getUrlVars( Url )['p']; var o = getUrlVars( Url )['o']; var tittle = getUrlVars( Url )['tittle']; 
			newUrl += "&p=" + p + "&o=" + o;
			$theDialog
				.load(Url)
				.dialog({
					resizable:false,
					width: 970,
					height: 590,
					position: "top",
					modal: true,
					closeOnEscape: false,
					open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); },
					title: tittle.replace(/[_]/g, " "),
					buttons: { "Cerrar": function() { 
									$(this).dialog("destroy").remove();
									$("#jGrowl").children().trigger('jGrowl.close');
									}, 
								"Grabar": function() { 
									$("#frmPropiedad").submit();
									return false; 
								}
					}
				}); 
			evt.preventDefault();
	});

	$('#btnSubmitPropio, #btnCancelPropio, #btnNuevaProp').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	).tooltip({track:true, delay:5000, showURL:false, fade:200});
	
	$("#btnSubmitPropio").click(function(ev) {
		$("<span />").addClass("ui-icon ui-icon-search").appendTo($(this).text("Buscando").addClass("ui-state-disabled"));
		$("#frmBusquedaPropia").submit();
		ev.preventDefault();
	});
	
	$("#frmBusquedaPropia").validate({
		submitHandler: function(form) {
			$.ajax({
				type: "POST",
				url: $(form).attr("action"),
				data: $(form).serialize(),
				success: function(data) {
					$("#searchResultsPropio").empty().html(data);
					$("<span />").addClass("ui-icon ui-icon-search").appendTo($("#btnSubmitPropio").text("Buscar").removeClass("ui-state-disabled"));
				},
				error: function(xhr, status) { showMsg(status + ": " + xhr.responseText, "error-alert", true); }
			});
			$("#jGrowl").children().trigger('jGrowl.close');
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
				$("#jGrowl").children().trigger('jGrowl.close');
				showMsg(list.html(), "error-alert", true);			
				$("<span />").addClass("ui-icon ui-icon-search").appendTo($("#btnSubmitPropio").text("Buscar").removeClass("ui-state-disabled"));
			}
		},
		rules: {
			txtNumero_P: {
				digits: true,
				minlength: 1,
				maxlength: 5
			},
			txtAmbientes_P: {
				digits: true,
				minlength: 1,
				maxlength: 2
			},
			txtSuperficieTotal_P: {
				digits: true,
				minlength: 1,
				maxlength: 5
			},
			txtAntiguedad_P: {
				digits: true,
				minlength: 1,
				maxlength: 3
			},
			txtPrecio_P: {
				digits: true,
				minlength: 1,
				maxlength: 8
			}
		},
		messages: {
			txtNumero_P: { 
				digits: "El Numero de Calle debe ser un NUMERO, de no mas de 5 Caracteres", 
				maxlength: "El Numero de Calle debe ser un NUMERO, de no mas de 5 Caracteres" 
			},
			txtAmbientes_P: { 
				digits: "La cantidad de Ambientes se debe expresar en NUMEROS, de no mas de 2 Caracteres", 
				maxlength: "La cantidad de Ambientes se debe expresar en NUMEROS, de no mas de 2 Caracteres"
			},
			txtSuperficieTotal_P: { 
				digits: "La Superficie se debe expresar en NUMEROS, de no mas de 5 Caracteres", 
				maxlength: "La Superficie se debe expresar en NUMEROS, de no mas de 5 Caracteres" 
			},
			txtAntiguedad_P: { 
				digits: "Los A\u00f1os de Antiguedad se deben expresar en NUMEROS [Sin punto ni coma], ni mas de 3 Caracteres", 
				maxlength: "Los A\u00f1os de Antiguedad se deben expresar en NUMEROS [Sin punto ni coma], ni mas de 3 Caracteres" 
			},
			txtPrecio_P: { 
				digits: "El Precio debe ser un valor NUMERICO [Sin signo $ ni puntos o comas], de no mas de 8 Caracteres", 
				maxlength: "El Precio debe ser un valor NUMERICO [Sin signo $ ni puntos o comas], de no mas de 8 Caracteres" 
			}
		}
	});

	$("#btnCancelPropio").click(function(ev) {
		$(":input", "#frmBusquedaPropia").not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected').removeClass('error');
		$("#jGrowl").children().trigger('jGrowl.close');
		$("#cboLocalidad_P").html("<option value='0'>Seleccione Localidad</option>").attr('disabled', 'true');
		showMsg('Seleccione los Criterios para realizar La B&uacute;squeda', 'highlight-alert', false);
		$("#searchResultsPropio").empty();
		ev.preventDefault();
	});	

	$("input:text")
		.focus(function(){ $(this).val("").css({"backgroundColor":"#FFFFF4"}); })
		.blur(function(){ $(this).css({"backgroundColor":""}); });

}); // End of ready() function
