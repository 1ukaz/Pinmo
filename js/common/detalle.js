// JavaScript Document

//<![CDATA[

$(document).ready(function(){
	setInterval(function(){$("#contactLink").effect("pulsate")}, 6000);
	$.datepicker.setDefaults($.datepicker.regional["es"]);
	var errs = [];
	var icon = "../../css/images/markers/mark4.png";
	if(parseInt(x[2], 10) && parseInt(x[3], 10))
		$.getJSON("../../detalle.php?acc=res&rk=" + x[1] + "&idProp=" + x[3], {ajax: "true"}, function(aJSON){ bookedPeriods = aJSON; createCalendar("#calDisponibilidad"); } );
	if (parseInt(x[3], 10))
		$.getJSON("../../detalle.php?acc=geo&gk=" + x[0] + "&idProp=" + x[3], {ajax: "true"}, function(aJSON){ aJSObject = aJSON; Map.showSingleMap("theMap", icon); } );
	$(".main .jCarouselLite").jCarouselLite({
		btnNext	: ".main .next",
		btnPrev	: ".main .prev",
		visible	: 3,
		scroll	: 2,
		speed	: 1500
	});
	SexyLightbox.initialize({
		find			: "lb",
		imagesdir		: "../../css/images",
		OverlayStyles	: { "background-color":"#000000", "opacity":0.8 }
	});
	var createCalendar = function(id){
		$(id).datepicker({ 
			numberOfMonths: 3,
			//showCurrentAtPos: 1,
			nextText: "Ver los tres meses Siguientes",
			prevText: "Ver los tres meses Anteriores",
			minDate: new Date(),
			beforeShowDay: isAvailable,
			stepMonths: 3		
		});
	}
	$("#contactLink").click(function(ev){
		var sizes = getBrowserWindowHeightAndWidth();
		$("#modalWrapper").css("top", parseInt((sizes.height/2) - ($("#modalWrapper").outerHeight()/2), 10) + "px").fadeIn(250);
		$("#overLay").fadeIn(100);
		$.getScript("../../js/common/consulta.js");
		return false;
	});
});

//]]>
