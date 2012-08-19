// Javascript Document

$(document).ready(function(){

	$.getJSON("combosManager.php?acc=am", {ajax: "true"}, function(aJSON){ comboContents = aJSON; } );

	//var $gallery = $("#gallery"), $trash = $("#trash");

	$("#gallery").sortable({
		cancel: "a.ui-icon",
		revert: true, 
		helper: "clone",
		scroll: true,
		handle: $(".imgContainer").add(".imgContainer img"),
		cursor: "move",
		//opacity: 0.7,
		placeholder: 'ui-state-highlight',
		forcePlaceholderSize: true
	});

	/*
	// let the trash be droppable, accepting the gallery items
	$trash.droppable({
		accept: '#gallery > li',
		//activeClass: 'ui-state-highlight',
		drop: function(ev, ui) {
			deleteImage(ui.draggable);
		}
	});

	// let the gallery be droppable as well, accepting items from the trash
	$gallery.droppable({
		accept: '#trash li',
		//activeClass: 'custom-state-active',
		drop: function(ev, ui) {
			recycleImage(ui.draggable);
		}
	});
	*/

	function deleteImage($item, p) {
		var recycle_icon = '<a href="./propiedadPict.php?acc=rs&po='+p+'" title="Restaurar Esta Imagen" class="ui-icon ui-icon-refresh">Restaurar</a>';
		$item.fadeOut(function() {
			var $list = $('ul',$("#trash")).length ? $('ul',$("#trash")) : $('<ul class="gallery ui-helper-reset"/>').appendTo($("#trash"));
			$item.find('a.ui-icon-trash').remove();
			$item.append(recycle_icon).appendTo($list).fadeIn(function() {
				$item.animate({ width: '48px' }).find('img').animate({ height: '36px' });
				if ($("#gallery").children("li").length >= 18) {
					$("<span />").addClass("ui-icon ui-icon-minus").appendTo($("#btnOpenRadApp").text("Galeria Completa").addClass("ui-state-disabled"));
					$("#btnOpenRadApp").attr("href", "#");
				}
				else {
					$("<span />").addClass("ui-icon ui-icon-plus").appendTo($("#btnOpenRadApp").text("Agregar Imagenes").removeClass("ui-state-disabled"));
					$("#btnOpenRadApp").attr("href", "./propiedadPict.php?acc=ra");
				}
			});
		});
	}

	function recycleImage($item, p) {
		var trash_icon = '<a href="./propiedadPict.php?acc=de&po='+p+'" title="Eliminar Esta Imagen" class="ui-icon ui-icon-trash">Borrar</a>';
		$item.fadeOut(function() {
			$item.find('a.ui-icon-refresh').remove();
			$item.css('width','95px').append(trash_icon).find('img').css('height','95px').end().appendTo($("#gallery")).fadeIn();
			if ($("#gallery").children("li").length >= 18) {
				$("<span />").addClass("ui-icon ui-icon-minus").appendTo($("#btnOpenRadApp").text("Galeria Completa").addClass("ui-state-disabled"));
				$("#btnOpenRadApp").attr("href", "#");
			}
			else {
				$("<span />").addClass("ui-icon ui-icon-plus").appendTo($("#btnOpenRadApp").text("Agregar Imagenes").removeClass("ui-state-disabled"));
				$("#btnOpenRadApp").attr("href", "./propiedadPict.php?acc=ra");
			}
		});
	}

	function openModalDescription($link, p, i, l, y) {
		$(".imgDescription").each(function(){ $(this).dialog("destroy").remove(); });
		var href = $link.attr("href"); 
		var url = href.substring(0, href.indexOf("&"));
		var $elDesSel = $("<select>").attr("id", "imgDes");
		loadComboContent(comboContents, $elDesSel);
		if (i) $elDesSel.val(i);
		var $mod = $("<div />").addClass("imgDescription").append($elDesSel).appendTo("body");
		$mod.dialog({
			title: "Descripcion de Imagen " + (parseInt(p) + 1),
			width: 215,
			minHeight: 80,
			resizable: false,
			position: [y, l],
			close: function(event, ui){ $(this).dialog("destroy").remove(); }
		});
		$mod.dialog('open');
		$("#imgDes").change(function(){
			var idDes = $(this).val();
			var stDes = $(this).find('option:selected').text();
			url += "&po=" + p + "&ide=" + idDes + "&sde=" + encodeURIComponent(stDes);
			$.get( url, function(res){ 
				if (stDes.length > 9) stDes = stDes.substring(0,9) + "...";
				showMsg(res, "succesfull-alert", false); 
				$link.attr("href", url); 
				$("<span />").addClass("ui-icon ui-icon-check").appendTo($link.parent().find('h5').text(stDes));
			});
			$mod.dialog("close");
		});
	}

	$("ul.gallery > li").live("click", function(ev) {
		var $item = $(this);
		var $target = $(ev.target);

		if ($target.is('a')) {			
			var top = ev.clientY ; var left = ev.clientX;
			var pos = getUrlVars( $target.attr("href") )['po']; var idd = getUrlVars( $target.attr("href") )['ide'];
		}

		if ($target.is('a.ui-icon-trash')) {
			deleteImage($item, pos);
			$.get( $target.attr("href"), function(res){ showMsg(res, "succesfull-alert", false); } );
		} else if ($target.is('a.ui-icon-newwin') && $target.parents("ul").is("#gallery")) {
			openModalDescription($target, pos, idd, top, left);
		} else if ($target.is('a.ui-icon-refresh')) {
			recycleImage($item, pos);
			$.get( $target.attr("href"), function(res){ showMsg(res, "succesfull-alert", false); } );
		}

		return false;
	});

	$(".toolTip").tooltip({track:true, delay:500, showURL:false, fade:200});

}); // Fin ready function()