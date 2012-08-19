<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Parque Propiedades, Servicios Inmobiliarios, Parque Lezama, San Telmo, Buenos Aires, Argentina, Alquiler, Venta, Temporario" />
<meta name="description" content="Parque Propiedades Servicios Inmobiliarios" />
<title>Inmobiliaria Parque</title>


<!--own styles -->
<link href="css/default.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/detalle.css" rel="stylesheet" type="text/css" media="screen" >
<link href="css/calendar.css" rel="stylesheet" type="text/css" media="screen" />

<!--shared styles -->
<link href="css/sexylightbox.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css" media="screen" />

<!--own js -->
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/calendar.js"></script>
<script type="text/javascript" src="scripts/form-submit.js"></script>
<script type="text/javascript" src="scripts/ajax.js"></script>

<!--shared js -->
<script type="text/javascript" src="js/overLib/overlib.js"></script>
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript" src="js/sexylightbox.v2.3.jquery.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
    	SexyLightbox.initialize({
			find			: 'lightbox',
			imagesdir		: 'css/images',
			OverlayStyles	: { 'background-color':'#000000', 'opacity': 0.8 }
		});
  	});
</script>

</head>

<body >


<?php header('Content-Type: text/html; charset=utf-8'); ?> 

<?php 

/*realiza acciones de inicio de base de datos, configuraciones, idioma, etc.*/
include("includes/startup.php"); 
?>


<div id="page">

    <div id="header" >
    <?php include("includes/header.php"); ?>    
    </div>    

    <div id="content">
    	
        <div id="toppanel">
             <?php include("includes/nav_bar.php"); ?>    
        </div>
       
    
        <div id="mainpanel">          
            <?php include("includes/content_selector.php");	?>                  
        </div>
     
     </div>  
      
    <div id="footer">
        <?php include("includes/footer.php"); ?>
    </div>
        
    
</div>     

</body>
</html>
