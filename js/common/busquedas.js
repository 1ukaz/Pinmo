// JavaScript Document

//<![CDATA[

$(".accLink").hide();
$(".itm").tooltip({track:true, delay:250, showURL:false, fade:200});
$("#sidebar .subOpciones").each(function(){
	$(this).find("ul").children("li:gt(2)").hide();
});
$("#content ul.items > li").each(function(){
	$(this).hover(
		function() { $(this).find(".accLink").show(); },
		function() { $(this).find(".accLink").hide(); }		
	);
});

$("input:text")
	.css({"font-size":"11px", "width":"60px", "height":"20px"})
	.focus(function(){
		$(this).css({"backgroundColor":""}); 
		$("#jGrowl").children().trigger('jGrowl.close');
	});
	
$(".toogleButton").click(function(){
	if ($(this).siblings().css("display") == "none") 
		$(this).find("p").css({'background-image':'url(css/images/portal/bt_ToogleFiltroUp.png)'}).end().siblings().fadeIn(1000);
	else 
		$(this).find("p").css({'background-image':'url(css/images/portal/bt_ToogleFiltroDown.png)'}).end().siblings().hide(250);
});

$(".subOpciones > a.subExpander").click(function(){
	if($(this).hasClass("subExpander")) 
		$(this).siblings("ul").children("li:gt(2)").show(500).end().end().text("Ver Menos");	
	else 
		$(this).siblings("ul").children("li:gt(2)").hide(250).end().end().text("Ver Mas");	
	$(this).toggleClass( "subCollapser subExpander" )
});

$("#mapCloser").live("click", function(){
	$('#mapWrapper').hide(500);
	$('#overLay').fadeOut(1250);
});
	
$("#mapLink").live("click", function(ev){
	var href = $(this).attr("href");
	var newHref = href + "?" + queryString + "&acc=mp";
	$('#mapWrapper').load(newHref).fadeIn(2000);
	$('#overLay').fadeIn(100);
	return false;
});

$.get("busquedas.php?acc=tr", function(r){
	if (parseInt(r) > 0) {
		//$("#paginatorTop, #paginatorBottom").paginate({
		$("#paginatorTop").paginate({
			count					: r,
			start 					: 1,
			display     			: 11,
			border					: true,
			border_color			: '#FFCCCC',
			text_color  			: '#FFFFFF',
			background_color    	: '#A31319',	
			border_hover_color		: '#A31319',
			text_hover_color  		: '#444444',
			background_hover_color	: '#FFE0E0', 
			images					: false,
			mouse					: 'press',
			onChange				: changePage
		});
	}
});

$("#sp\\.txtSupExacta, #sp\\.txtPrecioDesde, #sp\\.txtPrecioHasta").numInt();
	
$(".addFiltro").click(function(){ 
	var qs = queryString + $(this).attr('href');
	$.get("busquedas.php?" + qs, function(res){
		var $holder = $('<div/>').html(res);
		$('#resultsList').html($('#resultsList', $holder).html());
		$('#FiltrosAplicados').html($('#FiltrosAplicados', $holder).html());
		$('#FiltrosAplicables').html($('#FiltrosAplicables', $holder).html());
		$.getScript("js/common/busquedas.js");
		queryString = qs;
	});
	return false;
});

$(".delFiltro").click(function(){
	var qs = queryString.replace($(this).attr('href'), "");
	$(this).closest("div").fadeOut(500, function(){
		$.get("busquedas.php?" + qs, function(res){
			var $holder = $('<div/>').html(res);
			$('#resultsList').html($('#resultsList', $holder).html());
			$('#FiltrosAplicados').html($('#FiltrosAplicados', $holder).html());
			$('#FiltrosAplicables').html($('#FiltrosAplicables', $holder).html());
			$.getScript("js/common/busquedas.js");
			queryString = qs;
		});
	});
	return false;
});

$("#filterSuperficie").click(function(){
	if ($("#sp\\.txtSupExacta").val() != "" && !isNaN($('#sp\\.txtSupExacta').val())) {
		var qs = queryString + $(this).attr("href") + "&sp.txtSupExacta=" +	$("#sp\\.txtSupExacta").val();
		$.get("busquedas.php?" + qs, function(res){
			var $holder = $('<div/>').html(res);
			$('#resultsList').html($('#resultsList', $holder).html());
			$('#FiltrosAplicados').html($('#FiltrosAplicados', $holder).html());
			$('#FiltrosAplicables').html($('#FiltrosAplicables', $holder).html());
			$.getScript("js/common/busquedas.js");
			queryString = qs;
		});
	}
	else {
		$("#sp\\.txtSupExacta").css("background-color", "#FFE0E0");
		$.jGrowl("<ul style=\"margin:0px;padding:0px;list-style-type:disc;\"><li>Debe ingresar una Superficie Exacta Valida a Buscar.</li></ul>", { theme : "error-alert", sticky : true });
	}
	return false;
});

$("#filterPrecio").click(function(){
	var desdeVal = $("#sp\\.txtPrecioDesde").val(); var hastaVal = $("#sp\\.txtPrecioHasta").val();
	if (desdeVal != "" && !isNaN(desdeVal) && hastaVal != "" && !isNaN(hastaVal) && parseInt(desdeVal) < parseInt(hastaVal)) {
		var qs = queryString + $(this).attr("href") + "&sp.txtPrecioDesde=" + desdeVal + "&sp.txtPrecioHasta=" + hastaVal;
		$.get("busquedas.php?" + qs, function(res){
			var $holder = $('<div/>').html(res);
			$('#resultsList').html($('#resultsList', $holder).html());
			$('#FiltrosAplicados').html($('#FiltrosAplicados', $holder).html());
			$('#FiltrosAplicables').html($('#FiltrosAplicables', $holder).html());
			$.getScript("js/common/busquedas.js");
			queryString = qs;
		});
	}
	else {
		$("#sp\\.txtPrecioDesde, #sp\\.txtPrecioHasta").css("backgroundColor", "#FFE0E0");
		$.jGrowl("<ul style=\"margin:0px;padding:0px;list-style-type:disc;\"><li>Debe ingresar un Rango de Precio Valido a Buscar.</li></ul>", { theme : "error-alert", sticky : true });
	}
	return false;
});

$("#selOrden").change(function(){
	if ($(this).val()) {
		qs = queryString.replace(/&pg=([0-9]{29})/g, "").replace(/&p=([0-9]{1,})/g, "").replace(/&o=([0-9]{1})/g, "") + "&o=" + $(this).val();
		$.get("busquedas.php?" + qs, function(res){
			var $holder = $('<div/>').html(res);
			$('#resultsList').html($('#resultsList', $holder).html());
			$.getScript("js/common/busquedas.js");
			queryString = qs;
		});
	}
})

var changePage = function(page) {
	var qs = queryString + "&pg=" + (new String(Math.random())).substring(2, 18) + (((new Date()).getTime())) + "&p=" + page;
	$.get("busquedas.php?" + qs, function(res){ 
		$("#itemsList").empty().html(res); 
	});
}

//]]>