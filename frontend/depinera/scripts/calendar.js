var xmlHttp

function loadCalendar(url) {
	xmlHttp = GetXmlHttpObject()
	if (xmlHttp == null) {
		alert ("Cambia de navegador, o esto no va a ningun lado...")
    return
	}

	if (url == '') {
		url = "./modulos/propiedades/calendar.php";
	}

	url = url + "&sid="+Math.random();

	var objHTML = document.getElementById('calDisponibilidad');
    xmlHttp.open("GET", url, true); 
    xmlHttp.onreadystatechange = function()  {
		if(xmlHttp.readyState == 1) {
			objHTML.innerHTML = "<div style='padding-top:150px; text-align:center;'><img src='./imagenes/loading.gif' /><br />Cargando ...</div>";
		}
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
			//alert("Esta bastante lindo :)");
			if(xmlHttp.responseText != null)
				objHTML.innerHTML = xmlHttp.responseText;
			else
				objHTML.innerHTML = 'No se encontro nada';
		} 
    } 
	xmlHttp.send(null); 
}

// -------------------- Returns a XmlHttpRequest Object -------------

function GetXmlHttpObject() {
  var xmlHttp=null;
  try {
   // Firefox, Opera 8.0+, Safari
   xmlHttp=new XMLHttpRequest();
  }
  catch (e) {
    //Internet Explorer
    try {
      xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch (e) {
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
  return xmlHttp;
}
