// JavaScript Document

$(document).ready(function() {

	$(document).keyup(function(event) {
		if(event.keyCode == 13) {
			$("#btnSubmit").trigger("click");
		}
	});
	
	$("input:text:visible:first").focus();
	
	$("#btnSubmit").hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	);	
	
	$("#btnSubmit").click(function(ev){
		$("#frmLogin").submit();
		ev.preventDefault();
	});

	if (!$.support.opacity) {
		$("#pass_LGN").focus(function(){
			var offSet = $("#btnSubmit").offset();
			var leftPos = parseInt(offSet.left + $("#btnSubmit").width());
			var topPos = parseInt(offSet.top + $("#btnSubmit").height());
			$("<div />")
					.attr("id", "toolTip")
					.html("Antes de Ingresar, tenga en cuenta que<br />Internet Explorer &copy; NO es Totalmente Compatible<br />con este Panel<br />Es por eso que algunas Funcionalidades pueden tener comportamientos indeseados, asi como NO verse adecuadamente.")
					.css({"left":leftPos, "top":"0"})
					.appendTo("body");	
			$("#toolTip").animate({top:topPos}, 500, function() { setTimeout(function(){ $("#toolTip").fadeOut(500); }, 10000); });
		});
	}

//  if(navigator.geolocation) {
//    navigator.geolocation.getCurrentPosition(function(position) {
//      $("#lat_LGN").val(position.coords.latitude); 
//	  $("#lon_LGN").val(position.coords.longitude);
//    });
//  }

});
