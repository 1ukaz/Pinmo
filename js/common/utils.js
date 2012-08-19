// JavaScript Document

var bookedPeriods;
var aJSObject = {};
var x = $("script[src*=?]:eq("+($("script[src*=?]").length-1)+")").attr("src").split("?")[1].split("=")[1].split(";");
var qs = (document.location.search).replace("?", "");
var queryString = (qs) ? qs : "_YzXFGT=" + (new String(Math.random())).substring(2, 18) + (((new Date()).getTime()));
$.maxZIndex = $.fn.maxZIndex = function(opt) {
    var def = { inc: 10, group: "*" };
    $.extend(def, opt);
    var zmax = 0;
    $(def.group).each(function() {
        var cur = parseInt($(this).css("z-index"), 10);
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

//$.fn.clearForm = function() {
//  return this.each(function() {
//	var type = this.type, tag = this.tagName.toLowerCase();
//    if (tag == "form")
//      return $(":input",this).clearForm();
//    if (type == "text" || type == "password" || tag == "textarea")
//      this.value = "";
//    else if (type == "checkbox" || type == "radio")
//      this.checked = false;
//    else if (tag == "select")
//      this.selectedIndex = -1;
//  });
//}

function isAvailable(date){
	var dateAsString = date.getFullYear().toString() + date.getTwoDigitsMonthAsString() + date.getTwoDigitsDateAsString();
	var bool = true;
	//console.log("Entrada a Funcion isAvailable con el DIA: " + date + " -> Convertida luego a String: " + dateAsString);
	$.each(bookedPeriods, function(i, aJSON) {
		if ((parseInt(dateAsString, 10) >= parseInt(aJSON.d, 10)) && (parseInt(dateAsString, 10) <= parseInt(aJSON.h, 10))) {
			bool = false; 
			//console.log("Si dateAsString("+dateAsString+") >= Fecha Desde JSON("+aJSON.d+") && dateAsString("+dateAsString+") <= Fecha Hasta JSON("+aJSON.h+")");
			//console.log("Dia Inhabilitado");
		}
	});
	return (bool) ? [true, "", "Dia Disponible"] : [false, "", "Dia NO Disponible"];
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

	return {width: parseInt(intW, 10), height: parseInt(intH, 10)};
}

function showAlertBox( aMsg, style, bool ) {
	$.jGrowl(aMsg, { theme:style, sticky:bool });
}

function parseTwoDigits( number ) {
	return (number.length == 1) ? "0" + number : number;
}

function loadComboContent( content, aSelect ) {
	$.each(content, function(i, aJSON) { 
		$("<option>").attr("value", aJSON.id).text(aJSON.nombre).appendTo(aSelect); 
	});
}

function getUrlVars( aUrlString ) {
	var map = {};
	var parts = aUrlString.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) { map[key] = value; });
	return map;
}

Date.prototype.getTwoDigitsMonthAsString = function() {
	return ((this.getMonth()+1).toString().length == 1) ? "0" + (this.getMonth()+1).toString() : (this.getMonth()+1).toString();
}

Date.prototype.getTwoDigitsDateAsString = function() {
	return (this.getDate().toString().length == 1) ? "0" + this.getDate().toString() : this.getDate().toString();	
}

Array.prototype.indexOf = function(s) {
	for (var x = 0; x < this.length; x++) if (this[x] == s) return x;
	return false;
}

Array.prototype.containsElement = function (element) {
	for (var i = 0; i < this.length; i++) if (this[i] == element) return true; 
	return false;
}

$(document).ready(function(){
	$("#mainLoader, #textLoader")
		.hide()
		.ajaxStart(function(){ 				
			var sizes = getBrowserWindowHeightAndWidth();
			$("#mainLoader, #textLoader").show();
			$("#mainLoader").css("top", parseInt(((sizes.height/2) - ($(this).outerHeight()/2)), 10) + "px");
 		})
		.ajaxStop(function(){ $("#mainLoader").fadeOut(500); $("#textLoader").hide() } );

	$("#overLay").live("click", function(){ 
		$(this).fadeOut(850);
		$("#modalWrapper").hide("drop",{direction:"up"});
		$("#mapWrapper").hide(250);
		$("#jGrowl").children().trigger("jGrowl.close");
	}); 	
});