// JavaScript Document

//<![CDATA[

$(document).ready(function(){
	var errs = [];

	$(":input", $("#frmConsulta")).focus(function(){ $(this).val("").css({"backgroundColor":""}); })
	$("#btnCerrarConsulta").live("click", function(){ $("#overLay").trigger("click"); });
	$("#btnSalirConsulta").click(function(){ location.replace("index.php"); });
	
	$("#frmConsulta").validate({
			submitHandler: function(form) {
				var aUrl = $(form).attr("action");
				var acc  = getUrlVars( aUrl )['acc'];
				$.ajax({
					cache: false,
					type: $(form).attr("method"),
					url:  aUrl,
					data: $(form).serialize(),
					success: function(response) {
						if (acc == "po") {
							if (response.indexOf("<li>") > 0) showAlertBox(response, "error-alert", true);
							else {
								$(form).parent("div").css("opacity",1)
									.animate({opacity: 0, height: 0}, function(){
									$(this).css("display","none");
									var $info = $("<div />");
									$info
										.addClass("ui-state-highlight ui-corner-all")
										.html("<strong>" + response + "</strong>")
										.append($("<span />").addClass("ui-icon ui-icon-check sh"));
									$(this).before($info);
									$info.show(3000);
								});
								showAlertBox("Su Consulta Ha sido Enviada", "succesfull-alert", false);
							}
						}
						else {
							if (response.indexOf("<li>") > 0) showAlertBox(response, "error-alert", true);
							else { showAlertBox(response, "succesfull-alert", false); $("#overLay").trigger("click"); }
						}
					},
					error: function(xhr, status) { showAlertBox(status + ": " + xhr.responseText, "error-alert", true); }
				});
				$("label[generated='true']").remove();
				$("#jGrowl").children().trigger('jGrowl.close');
			},
			errorPlacement: function(error, element) {
				error.appendTo( );				// No tiene a donde apendear; los labels no aparecen :-P
			},
			onfocusout: false,
			focusInvalid: false,
			onkeyup: false,
			focusCleanup: true,
	
			showErrors: function(errorMap, errorList) {
				var list = $("<ul>").css({"margin":"0px", "padding":"5px"});
				$(errorList).each(function(i, err) {
					if (err.message != "" && (errs.containsElement(err.message) === false || (!$(err.element).is("select")) ) ) { 
						errs[i] = err.message;
						if ($(err.element).is(":checkbox")) {
							$(err.element).parent().css({"backgroundColor":"#FEF1EC", "border":"1px dotted #CD0A0A"});
						}
						$("<li>").text(err.message).appendTo(list);
					}
				});
				if (list.children().length > 0) {
					showAlertBox(list.html(), "error-alert", true);
				}
				this.defaultShowErrors();
			},
			rules: { txtConsulta: { minlength: 4, required: true }, txtNombre: { required: true }, txtEmail: {required: true, email: true} },
			messages: {
				txtNombre: { required: "Su Nombre es Requerido para Enviar La Consulta" },
				txtConsulta: {
					required: "Su Consulta es Requerida para poder ser Enviada",
					minlength: "Realmente una Consulta tiene menos de 5 Caracteres?"
				},
				txtEmail: {
					required: "Su E-mail es Requerido para Enviar La Consulta",
					email: "Debe ingresar un E-mail valido para Enviar La Consulta"
				}
			}
	});
});

//]]>