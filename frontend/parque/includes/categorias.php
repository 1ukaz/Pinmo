<fieldset>
    
    <legend><?php echo (ETIQUETA_CATEGORIAS);?></legend>	
    
	<div class="itemcategoria"> 
    	<a href ="#" onClick="cambiarCategoria(<?php echo(TIPO_PUBLICACION_TEMPORARIO); ?>, <?php echo($modulo); ?>, 'filtrar', '');"> <img src="imagenes/temporarios.png" /> </img></a>
    </div>
    
    <div class="itemcategoria">
       <a href ="#" onClick="cambiarCategoria(<?php echo(TIPO_PUBLICACION_ALQUILER); ?>, <?php echo($modulo); ?>, 'filtrar', '');"> <img src="imagenes/alquiler.png" /> </img></a>
    </div>
    
    <div class="itemcategoria"> 
        <a href ="#" onClick="cambiarCategoria(<?php echo(TIPO_PUBLICACION_VENTA); ?>, <?php echo($modulo); ?>, 'filtrar', '');"> <img src="imagenes/venta.png" /> </img></a>   
    </div>  
             
</fieldset>