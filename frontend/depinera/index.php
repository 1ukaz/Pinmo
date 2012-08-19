<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\"> 

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<?php header('Content-Type: text/html; charset=utf-8');   ?>

<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->

<meta name="keywords" content="inmobiliaria, piñera, pinera, san telmo, buenos aires, argentina, rentals, apartments, propiedades, americo depiñera, sandra depiñera, esteban molla, adriana poggiolini" />
<META NAME="Description" CONTENT="Inmobiliaria De Piñera Servicios Inmobiliarios, Defensa 1309, San Telmo, Buenos Aires, Argentina"> 
<meta name="google-site-verification" content="z4AzmunKgou4oAcf-ph3AvL7ZbHzKHT8IP1AnKGJpiQ" />
<title>De Piñera Servicios Inmobiliarios </title>

<!--own styles -->
<link href="css/styles.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/detalle.css" rel="stylesheet" type="text/css" media="screen" >
<link href="css/calendar.css" rel="stylesheet" type="text/css" media="screen" />

<!--shared styles -->
<link href="http://www.pinmo.com.ar/css/plugins/jquery.sexy.css" rel="stylesheet" type="text/css" media="screen" />
<link href="http://www.pinmo.com.ar/css/UI-Panel/jquery-ui.css" rel="stylesheet" type="text/css" media="screen" />


<!--own js -->
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/calendar.js"></script>
<script type="text/javascript" src="scripts/form-submit.js"></script>
<script type="text/javascript" src="scripts/ajax.js"></script>

<!--shared js -->
<script type="text/javascript" src="http://www.pinmo.com.ar/js/overLib/overlib.js"></script>
<script type="text/javascript" src="http://www.pinmo.com.ar/js/JQuery/jquery.js"></script>
<script type="text/javascript" src="http://www.pinmo.com.ar/js/JQuery/jq.easing.js"></script>
<script type="text/javascript" src="http://www.pinmo.com.ar/js/JQuery/jquery-ui.js"></script>
<script type="text/javascript" src="http://www.pinmo.com.ar/js/JQuery/jq.validate.js"></script>
<script type="text/javascript" src="http://www.pinmo.com.ar/js/JQuery/jq.sexy.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
    	SexyLightbox.initialize({
			find			: 'lightbox',
			imagesdir		: 'http://www.pinmo.com.ar/css/images/black',
			OverlayStyles	: { 'background-color':'#000000', 'opacity': 0.8 }
		});
  	});
</script>


</head>

<?php
include("includes/startup.php"); 
?> 


<body>

<!--header-->
<div class="header">

<div id="logo"><img src="images/header970_fondo_gris_nr.jpg" /></div>

<div class="outernav">
<div class="nav">
<div class="innernav">
<ul>

<li > <a href="index.php?modulo=1&idioma=<?php echo($idioma);?>"><?php echo ( ETIQUETA_BOTON_INICIO); ?> </a></li>
<li > <a href="index.php?modulo=2&idioma=<?php echo($idioma);?>"><?php echo ( ETIQUETA_BOTON_PROPIEDADES); ?> </a></li>   
<li > <a href="index.php?modulo=6&idioma=<?php echo($idioma);?>"> <?php echo ( ETIQUETA_BOTON_LAEMPRESA); ?> </a></li>  
<li > <a href="index.php?modulo=3&idioma=<?php echo($idioma);?>"><?php echo ( ETIQUETA_BOTON_SERVICIOS); ?> </a></li>
<li > <a href="index.php?modulo=4&idioma=<?php echo($idioma);?>"><?php echo ( ETIQUETA_BOTON_LEGALES); ?> </a></li>
<li > <a href="index.php?modulo=5&idioma=<?php echo($idioma);?>"> <?php echo ( ETIQUETA_BOTON_CONTACTO); ?> </a></li>  

<li>
	<form id="formNav" action="#" enctype="multipart/form-data">
	<div id="barra_idioma">
	<img src="images/bandera-<?php echo($idioma)?>.png">
	

	<select name="idioma" id="idioma" onChange="javascript: cambiarIdioma(this);" >
	<option value="es" <?php echo($idioma=="es")? "selected":""; ?>  > Español </option>
	<option value="en" <?php echo($idioma=="en")? "selected":""; ?>  > Inglés </option>	
	</select>
	</div>
	</form>
</li>

</ul>
</div>
</div>
</div>
	
</div>
<!-- fin header -->



<div id="wrap">

<div class="pagewrapper">

<div class="innerpagewrapper">

<div class="page">

<div class="content">
	<?php include("includes/content_selector.php");	?> 
</div>

<div class="footer">		
	<p>  2010 - Diseño por: <a href="http://www.pinmo.com.ar"><img src="images/logopinmo_small.png">  </img></a> </p> 
</div>

</div>

</div>

</div>

</div>

</body>
</html>
