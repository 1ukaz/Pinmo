// JavaScript Document

showMsg("Complete los Campos y presione Grabar<br />Los Campos indicados con asterisco son Requeridos<br />", "highlight-alert", false);

var comboContents = ""; var startAddress = ""; var lat = ""; var lon = ""; var errs = [];

$.getJSON("combosManager.php?acc=am", {ajax: "true"}, function(aJSON){ comboContents = aJSON; } );

$("#cboProvincia_ABM").change(function(){
	$("#cboLocalidad_ABM").children().remove();
	if (!$(this).val()) {
		$("#cboLocalidad_ABM").empty().html("<option value=''>Seleccione Localidad</option>");
	}
	else {
		$.getJSON("combosManager.php?acc=l",{idProv: $(this).val(), ajax: 'true'}, function(aJSON){ loadComboContent(aJSON, $("#cboLocalidad_ABM")); });
	}
});

if ($("#cboTipoPropiedad_ABM").val() == 16) {
	$("#cboEmprendimiento_ABM").attr({"disabled":"disabled"});
}

$("#cboTipoPropiedad_ABM").change(function(){
	if ($(this).val() == 16)
		$("#cboEmprendimiento_ABM").attr({"disabled":"disabled"});
	else
		$("#cboEmprendimiento_ABM").attr({"disabled":""});
});

var $editors = $("textarea.ckeditor");
if ($editors.length) {
	$editors.each(function() {
		var editorID = $(this).attr("id");
		if (CKEDITOR.instances[editorID])
			CKEDITOR.remove(CKEDITOR.instances[editorID]);
		CKEDITOR.replace(editorID, { width:500, height:200, uiColor:'#F9F9F9' } );
	});
}

$(".theButton").hover(
	function() { $(this).addClass('ui-state-hover'); }, 
	function() { $(this).removeClass('ui-state-hover'); }
);

$(".delButton").live("click", function(ev){
	$($(this).closest("tr")).fadeOut(function (){ $(this).remove(); });
	return false;
});

$("input:text")
	.focus(function(){ $(this).val("").css({"backgroundColor":"#FFFFF4"}); })
	.blur(function(){ $(this).css({"backgroundColor":""}); });

$("input:text.short, input:text.dec")
	.live("keyup", function(){ $(this).val( $(this).val().replace(/[^0-9\.]/g, "").replace(/^\./, "") ); })
	.live("blur", function(){ $(this).val( $(this).val().replace(/[^0-9\.]/g, "").replace(/^\./, "") ); });

$("input:text.ent")
	.live("keyup", function(){ $(this).val( $(this).val().replace(/[^0-9]/g, "") ); })
	.blur(function(){ $(this).val( $(this).val().replace(/[^0-9]/g, "") ); });

$("div#formStep_3 .montoPubs").each(function(){
	if ($(this).val().indexOf("No Ingresado") != -1) {
		$(this).css({"font-style":"italic", "color":"#999"});
	}
});

$("div#formStep_3 .montoPubs")
	.keyup(function(){
		var $elSelect = $(this).parents("tr").children().eq(3).children("select[name$='idEstPub\]']");
		var $elInput  = $(this);
		var val = $elInput.val();

		if (val != '') {
			$elInput.val(val.replace(/[^0-9\.]/g, "").replace(/^\./, ""));
			$elSelect.val(1);
		}
		else {
			$elInput.val("No Ingresado");
			$elInput.css({"font-style":"italic", "color":"#999"})
			$elSelect.val(7);
		}
	})
	.blur(function(){
		$this = $(this);
		if ($this.val() == "") {
			$this.css({"font-style":"italic", "color":"#999"});
			$this.val("No Ingresado"); 
			$this.parents("tr").children().eq(3).children("select[name$='idEstPub\]']").val(7);
		}
		else {
			$this.val( $this.val().replace(/[^0-9\.]/g, "").replace(/^\./, "") );
			$this.parents("tr").children().eq(3).children("select[name$='idEstPub\]']").val(1);
		}
	})
	.focus(function(){ $(this).css({"font-style":"normal", "color":""}); });

$("#frmPropiedad").validate({
	submitHandler: function(form) {
		var aUrl = $(form).attr("action");
		$.ajax({
			cache: false,
			type: $(form).attr("method"),
			url:  aUrl,
			data: $(form).serialize() + "&txtObservaciones_ABM=" + escape(CKEDITOR.instances.txtObservaciones_ABM.getData()) + "&lat_ABM=" + lat + "&lon_ABM=" + lon,
			success: function(response) {
				//$("#ModalDialog").html(response);
				if (response.indexOf("<li>") > 0) showMsg(response, "error-alert", true);
				else { showMsg(response, "succesfull-alert", false); $("#ModalDialog").dialog("destroy").remove(); }
				if ($("#searchResultsPropio").html() != "") {
					var p = getUrlVars( aUrl )['p']; var o = getUrlVars( aUrl )['o'];
					$.ajax({
						type: "POST",
						url: $("#frmBusquedaPropia").attr("action"),
						data: $("#frmBusquedaPropia").serialize() + "&p=" + p + "&o=" + o,
						success: function(data) {
							$("#searchResultsPropio").empty().html(data);
						},
						error: function(xhr, status) { showMsg(status + ": " + xhr.responseText, "error-alert", true); }
					});
				}
			},
			error: function(xhr, status) { showMsg(status + ": " + xhr.responseText, "error-alert", true); }
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
			showMsg(list.html(), "error-alert", true);
		}
		this.defaultShowErrors();
	},
	rules: { txtNumero_ABM: { maxlength: 5 }, chkDeAcuerdo_ABM: { required: true } },
	messages: {
		cboTipoPropiedad_ABM: { required: "El Tipo de Propiedad es Requerido" },
		txtCalle_ABM: { required: "La Calle es Requerida para establecer el Domicilio" },
		txtNumero_ABM: {
			required: "El Numero de Calle es Requerido para establecer el Domicilio",
			maxlength: "El Numero de Calle no debe contener mas de 5 Digitos"
		},
		cboProvincia_ABM: { required: "La Provincia es Requerida para establecer el Domicilio" },
		cboLocalidad_ABM: { required: "La Localidad es Requerida para establecer el Domicilio" },
		chkDeAcuerdo_ABM: { required: "Debe Aceptar que Verifica la Direccion ingresada" }
	}
});
	
$("input:text.required, #cboProvincia_ABM, #cboLocalidad_ABM").blur(function(){
	var newProvLoc = $("select.required:not([name*='Tipo']) option:selected[value!='']").map(function(){return $(this).text()}).get();
	if (newProvLoc.length == 2) {
		$calle = $("input:text.required:eq(0)"); $num = $("input:text.required:eq(1)");
		if ($calle.val().length > 1 && $num.val().length > 1) {
			var newAddress = $calle.val() + " " + $num.val() + ", " + newProvLoc[0] + ", " + newProvLoc[1] + ", Argentina";
			loadCanvasMap( newAddress, newProvLoc[0], newProvLoc[1] );
		}
	}
});

$("input:checkbox.accept").click(function(){
	$chkBox = $(this);
	if ($chkBox.hasClass("error")) {
		$chkBox.parent().css({"backgroundColor":"", "border":"none"});
		$("#jGrowl").children().trigger('jGrowl.close');
	}
	if ($chkBox.hasClass("valid"))
		$chkBox.parent().css({"backgroundColor":"#FEF1EC", "border":"1px dotted #CD0A0A"});
	else
		return;
});
	
var startProvLoc = $("select.required:not([name*='Tipo']) option:selected[value!='']").map(function(){return $(this).text()}).get();
var startStrtNum = $("input:text.required").map(function(){return $(this).val()}).get();
if (startProvLoc.length == 2 && (startStrtNum[0].length > 0 && startStrtNum[1].length > 0)) {
	startAddress = startStrtNum[0] + " " + startStrtNum[1] + ", " + startProvLoc[0] + ", " + startProvLoc[1] + ", Argentina";
	loadCanvasMap( startAddress, startProvLoc[0], startProvLoc[1] );
}

function loadCanvasMap(address, strProv, strLoc)  {
	$("#canvasMap").empty();

	var map = new google.maps.Map($("#canvasMap").get(0), {mapTypeId:google.maps.MapTypeId.ROADMAP, zoom:15, backgroundColor:'#FDFDE8'});
	var geocoder = new google.maps.Geocoder();
	var infowindow = new google.maps.InfoWindow();

	if (geocoder) {
		geocoder.geocode({'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				lat = results[0].geometry.location.lat(); lon = results[0].geometry.location.lng();
				var marker = new google.maps.Marker({
					icon: '../../css/images/markers/mark4.png',
					map: map, 
					position: results[0].geometry.location
				});
				$("input:text.required:eq(0)").val(results[0].address_components[1].long_name); 
				$("input:text.required:eq(1)").val(results[0].address_components[0].long_name);
				var htmlContent = "<span style=\"font-size:9px;\">" + results[0].address_components[1].long_name + " " + results[0].address_components[0].long_name 
										+ ", " + strLoc + "<br />&middot; " + strProv + " &middot; Argentina</span>";
				infowindow.setContent(htmlContent);
				//console.log(results);
				infowindow.open(map, marker);
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.open(map, marker);
				});
			} 
			else {
				showMsg("La Direcci&oacute;n no se pudo resolver<br />Error de la API de Google Maps: " + status, "error-alert", true);
			}
		});
	}
}

/*
// Devuelve un arreglo con todas las opciones [El texto de las mismas, sino deberia ser val() y no text()]
// de los combos de la clase "required" que tienen un value no vacio
$('select.required option[value!=""]').map(function(){return $(this).text()}).get() 
// Lat y Long de Bs As: -34.62332,-58.373108
// Devuelve un arreglo de los textos de las opciones seleccionadas en todos los combos de la clase 
// "required". Si quisiera devolver los values en lugar del texto de la opcion, seria val() en lugar de text()
$('select.required option:selected[value!=0]').map(function(){return $(this).text()}).get()

// Devuelve un arreglo con el valor que contienen los campos input de la clase "required"
$('input:text.required').map(function(){return $(this).val()}).get()

// Devuelve la opcion seleccionada del combo Provincia [Si la opcion no es "Seleccione Provincia, claro]
$("select.required:not([name*='Tipo']) option:selected[value!='']").eq(0).text();
*/