// JavaScript Document
Map = {
	showSingleMap: function(divID, icon) {
		if ((aJSObject.lat && aJSObject.lon) && (aJSObject.lat != "0.000000" && aJSObject.lon != "0.000000")) {
			//console.log(aJSObject.lat); console.log(aJSObject.lon);
			var map = new google.maps.Map($("#" + divID + "").get(0), {
				center: new google.maps.LatLng(aJSObject.lat, aJSObject.lon),
				zoom: 14,
				mapTypeId: 'roadmap',
				scrollwheel: false,
				backgroundColor:'#FDFDE8'
			});
			var infoWindow = new google.maps.InfoWindow;
			var html = aJSObject.adr + "<br />" + aJSObject.loc + "<br />" + aJSObject.pro;
			var point = new google.maps.LatLng(aJSObject.lat, aJSObject.lon);
			var marker = new google.maps.Marker({
				map: map,
				position: point,
				icon: icon
			});
			this.bindInfoWindow(marker, map, infoWindow, html);
		}
	},
	showMultiMap: function(divID, Icons, qs) {
		var map = new google.maps.Map($("#" + divID + "").get(0), {
			center: new google.maps.LatLng(-34.62332, -58.373108),
			zoom: 12,
			mapTypeId: 'roadmap',
			scrollwheel: false,
			backgroundColor:'#FFFFFF'
		});
	
		var infoWindow = new google.maps.InfoWindow;
		var bounds = new google.maps.LatLngBounds();
		$.get("markers.php?" + qs, function(xml) {
			$(xml).find("marca").each(function(k){
				if ($(this).attr("lat") != "" && $(this).attr("lng") != "") {
					var address = $(this).attr('calle') + " " + $(this).attr('num') + ", " + $(this).attr('loc') + "<br />" + $(this).attr('prov') + " - Argentina";
					var type = $(this).attr("tipo");
					var src = $(this).attr("thb");
					var point = new google.maps.LatLng(parseFloat($(this).attr("lat")), parseFloat($(this).attr("lng")));
					//console.log(point); console.log(address);
					var html = address + "<br /><a href='" + $(this).attr("lnk") + "'>Ver Ficha</a>";
					//var html = "<div class=\"infoWindow\"><ul><li><img src=\"" + src +"\" class=\"listedThumb\"</li><li>" + address + "</li></ul></div>";
					var icon = Icons[type.toLowerCase()] || {};
					var marker = new google.maps.Marker({
						map: map,
						position: point,
						icon: icon.icon
						//shadow: icon.shadow
					});
					Map.bindInfoWindow(marker, map, infoWindow, html);
					bounds.extend(point);
					map.fitBounds(bounds);
				}
			});
		}, "xml");		
	},
	bindInfoWindow: function (m, mp, iw, h) {
		google.maps.event.addListener(m, 'click', function() {
			iw.setContent(h);
			iw.open(mp, m);
		});	
	}
}