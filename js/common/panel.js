// JavaScript Document

$.maxZIndex = $.fn.maxZIndex = function(opt) {
    /// <summary>
    /// Returns the max zOrder in the document (no parameter)
    /// Sets max zOrder by passing a non-zero number
    /// which gets added to the highest zOrder.
    /// </summary>    
    /// <param name="opt" type="object">
    /// inc: increment value, 
    /// group: selector for zIndex elements to find max for
    /// </param>
    /// <returns type="jQuery" />
    var def = { inc: 10, group: "*" };
    $.extend(def, opt);
    var zmax = 0;
    $(def.group).each(function() {
        var cur = parseInt($(this).css("z-index"));
        zmax = cur > zmax ? cur : zmax;
    });
    if (!this.jquery)
        return zmax;

    return this.each(function() {
        zmax += def.inc;
        $(this).css("z-index", zmax);
    });
}

// Center an object on the screen
jQuery.fn.center = function() {
	var w = $(window);
	this.css("position","absolute");
	this.css("top",(w.height()-this.height())/2+w.scrollTop() + "px");
	this.css("left",(w.width()-this.width())/2+w.scrollLeft() + "px");
	return this;
}

// Toggle text within an element using regular expressions.
// Useful for changing "show" to "hide" and back when toggling element displays, for example
jQuery.fn.toggleText = function(a,b) {
	return this.html(this.html().replace(new RegExp("("+a+"|"+b+")"),function(x){return(x==a)?b:a;}));
}

$(document).ready(function() {

	$("#mainLoader")
		.hide()
		.bind("ajaxStart", function(){
				var sizes = getBrowserWindowHeightAndWidth();
				$(this).show();
				$(this).css("top", parseInt(((sizes.height - $(this).outerHeight())/2)-20) + "px");		// El -20 es que lo quiero un poquito mas arriba del centro ;)
				//$(this).css("left", parseInt((sizes.width - $(this).outerWidth()())/2) + "px");
			}
		)
		.bind("ajaxStop", function(){ 
				$(this).fadeOut(500); 
			}
		);
	
	$("#tabs").tabs({
		disabled: [4],
		spinner: "<em>Cargando&#8230;</em>",
		ajaxOptions: {
			error: function(xhr, status, index, anchor) {
				$(anchor.hash).html("<p align='center'><img src='../../css/images/black/404html.png' /></p>");
			}
		}
	});

	if (!$.support.opacity) {
		$("#tabs > ul > li").each(function(i){
			if (i < 4) $(this).removeClass("ui-state-disabled");
		});
	}

	SexyLightbox.initialize({
		find			: "lb",
		imagesdir		: "../../css/images",
		OverlayStyles	: { "background-color":"#000000", "opacity":0.8 }
	});

	$('#loGoff').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	).tooltip({track:true, delay:500, showURL:false, fade:200});

	$('.adv:eq(1)').hover(
		function() { $(this).attr("src", "../../css/images/gen/p-jq.png"); }, 
		function() { $(this).attr("src", "../../css/images/gen/p-jq-off.png"); }
	);

	$('.adv:eq(0)').hover(
		function() { $(this).attr("src", "../../css/images/gen/p-w3c.png"); }, 
		function() { $(this).attr("src", "../../css/images/gen/p-w3c-off.png"); }
	);

	$(".orderLink, .pagLink").live("click", function(ev){
		var href = $(this).attr("href");
		var car = '?';
		if (href.indexOf('?') > 0) car = '&';
		href += car + Math.floor(Math.random()*11111111111111111111);
		var theFormID = "#" + getUrlVars( href )['idf'];
		var theDivID  = "#" + getUrlVars( href )['idr']
		$.ajax({
			type: "POST",
			url: href,
			data: $(this).parents($("#tabs")).children("div.searchForm").children(theFormID).serialize(),
			timeout: 15000,
			error: function(xhr, status) { showMsg(status + ": " + xhr.responseText, "error-alert", true); },
			success: function(data){ $(theDivID).empty().html(data); }
		});
		if ($(ev.target).is(".orderLink")) {
			$(this).toggleClass("orderLinkActive").text("Ordenando..");
		}
		return false;
	});

	$("#searchResultsGlobal").live("click", function(evt) {
		$("#resultadosGlobales > tbody > tr").each(function(){$(this).removeClass("activeRow");});
		if ($(evt.target).parent().is(".accLink")) {
			var $theDialog = $("<div />");
			var $theLink = $(evt.target).parent();
			var Url = $theLink.attr("href");
			var tittle = getUrlVars( Url )['tittle']; var rowID = getUrlVars( Url )['i']; 
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
							$("#resultadosGlobales tr#Row_"+rowID).effect("highlight", {}, 2000, function(){ $(this).addClass("activeRow"); });
						} 
					}
				}); 
			return false;
		}
	});

	$("#searchResultsPropio").live("click", function(evt){
		$("#resultadosPropios > tbody > tr").each(function(){$(this).removeClass("activeRow");});
		if ($(evt.target).parent().is(".stateLink")) {
			var href = $(evt.target).parent().attr("href")
			var rowID = getUrlVars( href )['idz'];
			var car = '?';
			if (href.indexOf("?") > 0) car = '&';
			href += car + Math.floor(Math.random()*11111111111111111111);
			if ($("#stateLoading_" + rowID).css('display') != 'none') 
				$("#stateLoading_" + rowID).hide();
			$.ajax({
				type: "GET",
				url: href,
				timeout: 15000,
				beforeSend: function(){ $("#stateLink_" + rowID).hide(); $("#stateLoading_" + rowID).show(); },
				error: function(xhr, status) { showMsg(status + ": " + xhr.responseText, "error-alert", true); },
				success: function(data){
					var results = data.split(",");
					$("#zonaEstadoPublicacion_" + rowID).html(results[1]);
					$("#stateLoading_" + rowID).hide();
					$("#resultadosPropios tr#Row_"+rowID).effect("highlight", {}, 2000, function(){ $(this).addClass("activeRow"); });
					if (results[0].indexOf("<li>") == -1) 
						showMsg(results[0], "succesfull-alert", false);
					else 
						showMsg(results[0], "error-alert", true);
				}
			});
			return false;
		}
		else if ($(evt.target).parent().is(".accLink")) {
			var $theLink = $(evt.target).parent();
			var Url = $theLink.attr("href");
			var newUrl = $theLink.parents($("#tabs")).children("div.searchForm").children("#frmBusquedaPropia").attr("action");
			var serialData = $theLink.parents($("#tabs")).children("div.searchForm").children("#frmBusquedaPropia").serialize();
			var p = getUrlVars( Url )['p']; var o = getUrlVars( Url )['o']; var acc = getUrlVars( Url )['acc'];
			var rowID = getUrlVars( Url )['i']; var tittle = getUrlVars( Url )['tittle']; 
			serialData += "&p=" + p + "&o=" + o;
			if (acc == "ve") {
				var $theDialog = $("<div />");
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
							$("#resultadosPropios tr#Row_"+rowID).effect("highlight", {}, 2000, function(){ $(this).addClass("activeRow"); });
							}
						}
					});
			}
			else if (acc == "de") {
				var href = $(evt.target).parent().attr("href")
				$legend = $("<p />").html("Esta seguro de Eliminar la Propiedad?<br />Esta Acci\u00f3n es irreversible!").append($("<span />").addClass("ui-icon ui-icon-alert"));
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
								$(this).dialog("destroy").remove();
								$.ajax({
									type: "GET",
									url: href,
									timeout: 15000,
									success: function(response) {
										if (response.indexOf("No") == -1) {
											$("#Row_"+rowID, $("#resultadosPropios")).fadeOut(500,function(){
												$(this).next().remove().end().remove(); 
												showMsg(response, "succesfull-alert", false);
												$("#resultadosPropios > tbody > tr").each(function(){$(this).removeClass("unpairRow");$(this).removeClass("pairRow");$(this).removeClass("activeRow");});										
												$("#resultadosPropios > tbody > tr:not('.sepa'):even").addClass("pairRow");
												$("#resultadosPropios > tbody > tr:not('.sepa'):odd").addClass("unpairRow");	
											});
										}
										else
											showMsg(response, "error-alert", true);
									},
									error: function(xhr, status) { showMsg(status + ": " + xhr.responseText, "error-alert", true); }
								});
						}
					}
				});
			}
			else {
				var $theDialog = $("<div />").attr("id", "ModalDialog");
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
										if (acc == "fo")
											$.get( "./propiedadPict.php?acc=cl", function(res){ if (res.length > 0) showMsg(res, "warning-alert", false); } );	
										if (acc == "ld")
											$.get( "./propiedadDisp.php?acc=cl", function(res){ if (res.length > 0) showMsg(res, "warning-alert", false); } );	
										$(this).dialog("destroy").remove();
										$("#jGrowl").children().trigger('jGrowl.close');
										$(".imgDescription").each(function(){ $(this).dialog("destroy").remove(); });
									},
								   "Grabar": function() { 
										if (acc == "ed") $("#frmPropiedad").submit();
										if (acc == "ld") closeResModalOnRec(newUrl, serialData);
										if (acc == "fo") closePicModalOnRec(newUrl, serialData); 
										return false; 
									}
						}
					});
			}
			return false;
		}
	});

	$("#btnAddAmbiente").live("click", function(ev){
		var elAmbienteSel = $("<select>");
		loadComboContent(comboContents, elAmbienteSel);
		var elAnchoInput = $("<input type='text' />").addClass("short");
		var elLargoInput = $("<input type='text' />").addClass("short");
		var elAddButton  = $("#btnAddAmbiente").removeClass("ui-state-hover");

		$("#roomsTable tbody")
			.append($('<tr>')
				.append( $('<td>').attr("align", "left").append($(elAmbienteSel)) )
				.append( $('<td>').append($(elAnchoInput)) )
				.append( $('<td>').append($(elLargoInput)) )
				.append( $('<td>').append($(elAddButton)) )
			);

		$("#roomsTable tbody tr").each(function(j){
			var theRowClass = (j%2 == 0) ? "pairRow" : "unpairRow";
			$aRow = $(this);
			$aRow.attr("id", j).attr("class", theRowClass);
			($aRow.children()).each(function(i){
				$aCell = $(this);
				$aChild = $aCell.children();
				if (i == 0) $aChild.attr({"id":"theAmbientes_ABM["+j+"][t]", "name":"theAmbientes_ABM["+j+"][t]"});
				else if (i == 1) $aChild.attr({"id":"theAmbientes_ABM["+j+"][a]", "name":"theAmbientes_ABM["+j+"][a]"});
				else if (i == 2) $aChild.attr({"id":"theAmbientes_ABM["+j+"][l]", "name":"theAmbientes_ABM["+j+"][l]"});
			});
		});
		
		return false;
	});

	var closePicModalOnRec = function(aUrl, info) {
		var serial = $('#gallery').sortable('serialize');
		var count  = $('#gallery').children("li").length;
		if (count > 18) {
			var offset = parseInt(count, 10) - 18;
			showMsg("<ul style=\"margin:0px;padding:0px;\"><li>S\u00f3lo se permiten hasta 18 Im\u00e1genes<br />Elimine "+ offset +" y vuelva a Grabar</li></ul>", "error-alert", true); 
			return false;
		} 
		$.ajax({
			url: "./propiedadPict.php?acc=re",
			type: "POST",
			data: serial,
			success: function(res) {
				if (res.indexOf("<li>") == -1) {
					showMsg(res, "succesfull-alert", false);
					if ($("#searchResultsPropio").html() != "") { 
						$.ajax({
							type: "POST",
							url: aUrl,
							data: info,
							success: function(data) {
								$("#searchResultsPropio").empty().html(data);
							},
							error: function(xhr, status) { showMsg(status + ": " + xhr.responseText, "error-alert", true); }
						});
					}
					$("#ModalDialog").dialog("destroy").remove();
					$(".imgDescription").each(function(){ $(this).dialog("destroy").remove(); });
				}
				else
					showMsg(res, "error-alert", true);
			},
			error: function(xhr, status) { showMsg(status + ": " + xhr.responseText, "error-alert", true); }
		});
	}

	var closeResModalOnRec = function(aUrl, info) {
		if (cont > 0) {
			$.ajax({
				type: "GET",
				url: "./propiedadDisp.php?acc=re",
				success: function(response) {
					if (response.indexOf("<li>") == -1) {
						showMsg(response, "succesfull-alert", false);
						if ($("#searchResultsPropio").html() != "") { 
							$.ajax({
								type: "POST",
								url: aUrl,
								data: info,
								success: function(data) {
									$("#searchResultsPropio").empty().html(data);
								},
								error: function(xhr, status) { showMsg(status + ": " + xhr.responseText, "error-alert", true); }
							});
						}
						$("#ModalDialog").dialog("destroy").remove();
					}
					else
						showMsg(response, "error-alert", true);
				},
				error: function(xhr, status) { showMsg(status + ": " + xhr.responseText, "error-alert", true); }
			});
		}
		else
			showMsg("<ul style=\"margin:0px; padding:5px;\"><li>Para Grabar debe Agregar y/o Quitar una Reserva</li></ul>", "error-alert", true);
	}

}); // End ready() function


function showMsg( aMsg, style, bool ) {
	$.jGrowl(aMsg, { theme:style, sticky:bool });
}

function getBrowserWindowHeightAndWidth() {
	var intH = 0;
	var intW = 0;

	if(typeof window.innerWidth  == "number") {
		intH = window.innerHeight;
		intW = window.innerWidth;
	} 
	else if(document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
		intH = document.documentElement.clientHeight;
		intW = document.documentElement.clientWidth;
	}
	else if(document.body && (document.body.clientWidth || document.body.clientHeight)) {
		intH = document.body.clientHeight;
		intW = document.body.clientWidth;
	}

	return {width: parseInt(intW), height: parseInt(intH)};
}

function getUrlVars( aUrlString ) {
	var map = {};
	var parts = aUrlString.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) { map[key] = value; });
	return map;
}

function changePaginatorPage( aUrl, flg ) {
	$("#mainLoader").show();
	var page = $("#comboPag").val();
	var idr = getUrlVars( aUrl )["idr"];
	aUrl += "&p=" + page;
	if (!flg) {
		var idf = getUrlVars( aUrl )["idf"];
		$.ajax({
				type: "POST",
				url: aUrl,
				data: $("#"+idf).serialize(),
				timeout: 5000,
				error: function(xhr, status) { showMsg(status + ": " + xhr.responseText, "error-alert", true); },
				success: function(data){ $("#"+idr).empty().html(data); $("#mainLoader").hide(); }
		});
	}
	else {
		$.ajax({
				type: "GET",
				url: aUrl,
				timeout: 5000,
				error: function(xhr, status) { showMsg(status + ": " + xhr.responseText, "error-alert", true); },
				success: function(data){ $("#"+idr).empty().html(data); $("#mainLoader").hide(); }
		});
	}
}

function removeJsCssFile(fileName, fileType){
	var targetElement = (fileType == "js") ? "script" : (fileType == "css") ? "link" : "none"	//determine element type to create nodelist from
	var targetAttr = (fileType == "js") ? "src" : (fileType == "css") ? "href" : "none"			//determine corresponding attribute to test for
	var allSuspects = document.getElementsByTagName(targetElement)
	for (var i = allSuspects.length; i >= 0; i--) {												//search backwards within nodelist for matching elements to remove
		if (allSuspects[i] && allSuspects[i].getAttribute(targetAttr) != null && allSuspects[i].getAttribute(targetAttr).indexOf(fileName) != -1)
			allSuspects[i].parentNode.removeChild(allSuspects[i])								//remove element by calling parentNode.removeChild()
	}
}

function loadComboContent( content, aSelect ) {
	$.each(content, function(i, aJSON) { 
		$("<option>").attr("value", aJSON.id).text(aJSON.nombre).appendTo(aSelect); 
	});
}

Array.prototype.indexOf = function(s) {
	for (var x = 0; x < this.length; x++) if (this[x] == s) return x;
	return false;
}

Array.prototype.containsElement = function (element) {
	for (var i = 0; i < this.length; i++) if (this[i] == element) return true; 
	return false;
}

/*
CARACTERES UNICODE PARA LOS ACENTOS Y LAS Ñ DESDE ACA DE JAVASCRIPT

\u00e1 -> á 
\u00e9 -> é 
\u00ed -> í 
\u00f3 -> ó 
\u00fa -> ú 
\u00c1 -> Á 
\u00c9 -> É 
\u00cd -> Í 
\u00d3 -> Ó 
\u00da -> Ú 
\u00f1 -> ñ 
\u00d1 -> Ñ

*/