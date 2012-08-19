<img src="imagenes/darsenan.png"/>


<div id="texto_inicio">

<?php 

switch ($idioma){ 
	
	case 'es': ?>
	    <p align="justify">
	    Nuestro servicio inmobiliario llegó a San Telmo para asesorarlo en la compra, venta o alquiler de propiedades para viviendas,
	    locales comerciales o industriales, oficinas y terrenos, alquileres para vivienda, con o sin muebles y/o temporarios, 
	    contando para ello con personal profesional con importante trayectoria, experiencia, eficiencia, honestidad y ética.
	    </p>
	    
	    <p align="justify">
	    En Cesar's Propiedades además e disponer de personal y tecnología de avanzada podrá encontrar en nuestro departamento de ventas las 
	    herramientas necesarias para ayudarlo en la búsqueda de la propiedad que necesita y el lugar deseado.</p>
	    
	    <p align="justify">
	    Tenemos en cartera departamentos de 1, 2, 3, y 4 ambientes en San Telmo, Barracas, Belgrano y Palermo para venta o alquiler que Ud.
	    podrá apreciar en esta página.</p>
	    
    <?php break;
    
    case 'en': ?>
    
    	<p align="justify"> Cesar´s properties, our real state service, came  to San Telmo to advise you on the purchase, sale or rental of residential properties, commercial or industrial premises, offices and land, home for  rent  -temporary or not / furnished or unfurnished.	</p>
    	
    	<p align="justify">	We have professional staff with important background, experience, efficiency, honesty and ethics. Available staff and advanced technology can be found in our sales department with the necessary tools to assist you in finding the property and the place you need. </p>
    
    	<p align="justify"> Find in our portfolio departments with 1, 2, 3 and 4 rooms in San Telmo, Barracas, Belgrano and Palermo. For sale or rent have a look in our web page.</p>
    	
    	
     <?php break;
    
    default: ?>
    
    	<p align="justify">  &nbsp;  </p>
    	
     <?php break;
     
} ?>
    
</div>

<div id="imagen_inicio">


<?php 

if( strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')>0){ ?>
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="440" height="300">
      <param name="movie" value="imagenes/inicio.swf?xml_path=imagenes/inicio.xml" />
      <param name="quality" value="medium" />
    </object> 
<?php } else{ ?>
	
	<embed src="imagenes/inicio.swf?xml_path=imagenes/inicio.xml" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="440" height="300" quality="medium"></embed>
	
<?php } ?>
    
</div>