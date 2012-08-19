<div id="fotos_header_inicio">
	<img src="imagenes/header/museo.jpg"/>
	<img src="imagenes/header/escaleras.jpg"/>
	<img src="imagenes/header/leon.jpg"/>
</div>


<div id="texto_inicio">

<?php 

switch ($idioma){ 
	
	case 'es': ?>
		 
		<div>
		<center><img width="615px" src="imagenes/bienvenidos.jpg"/></center>
		<p>Bienvenido a Parque Propiedades, lo invitamos a consultar el material disponible o bien visitar nuestras oficinas del barrio de San Telmo.-</p>		         
		</div>
	    
    <?php break;
    
    case 'en': ?>
    
		<div>
		<center><img width="615px" src="imagenes/bienvenidos.jpg"/></center>
		<p>Welcome to Parque Propiedades Website, you can look for properies online or visit us in our agency in San Telmo neighbourhood.-</p>		         
		</div>    
     <?php break;
    
    default: ?>
    
    	<p align="justify">  Sorry! This page is not available in this language now. Please try again in a few days.   </p>
    	
     <?php break;
     
} 

?>
    
</div>

