// JavaScript Document

//<![CDATA[

$(document).ready(function(){

	$("#sp\\.cboProvincia").change(function(){
		$('#sp\\.cboLocalidad').children().remove();
		if (!$(this).val())
			$("#sp\\.cboLocalidad").html("<option value='0'>Seleccione Localidad</option>");
		else
			$.getJSON("index.php?acc=l",{idProv:$(this).val(),ajax:'true'},function(aJSON){loadComboContent(aJSON,$("#sp\\.cboLocalidad"));});
	});
	
	$("#frmBusqueda").submit(function(ev){
		var nMap = $("select option:selected").map(function(){return $(this).text()}).get();
		$("#sp\\.strTipoPropiedad").val(nMap[0]); $("#sp\\.strProvincia").val(nMap[1]); $("#sp\\.strLocalidad").val(nMap[2]);
		return true;	
	});

	$('#lasVistas').bxSlider({
		prev_image: 'css/images/slider/btn_arrow_left.png',
		next_image: 'css/images/slider/btn_arrow_right.png',
		wrapper_class: 'vistasWrapper',
		auto: true,
		auto_hover: true,
		stop_text: 'Detener',
		start_text: 'Reanudar',
		controls: false,
		auto_controls: true
	});

});

//]]>
